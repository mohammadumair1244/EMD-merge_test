<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmdPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view_team_member');
        $users = User::whereNotIn('admin_level', [User::ADMIN, User::WEB_REGISTER])->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('team_member_add');
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->authorize('team_member_add');
        $cookie = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 16);
        $validator = $request->validate([
            'email' => 'unique:users',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'hash' => $cookie,
            'admin_level' => $request->admin_level,
        ]);
        if ($user) {
            return to_route('user.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('team_member_detail');
        if ($user->admin_level == User::ADMIN || $user->admin_level == User::WEB_REGISTER || $user->id == auth()->guard('admin_sess')->id()) {
            return to_route('admin.dashboard');
        }
        $emd_permissions = EmdPermission::orderBy("type", "ASC")->orderBy("name", "ASC")->get();
        $user = $user->load(['emd_permission']);
        $currentUser = User::with('emd_permission')->find(auth()->guard('admin_sess')->id());
        return view('admin.users.edit')->with(['user' => $user, 'emd_permissions' => $emd_permissions, 'currentUser' => $currentUser]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('team_member_edit');
        $cookie = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 16);
        $user->name = $request->name;
        // $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->hash = $cookie;
        $user->save();
        return back()->with('message', 'User Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('team_member_delete');
        $user->delete();
        return back()->with('warning', 'User Trashed!');
    }

    public function trash_list()
    {
        $this->authorize('view_trash_team_member');
        $users = User::onlyTrashed()->whereNotIn('admin_level', [User::ADMIN, User::WEB_REGISTER])->get();
        return view('admin.users.trash', compact('users'));
    }

    public function user_permanent_destroy($id)
    {
        $this->authorize('team_member_delete');
        User::onlyTrashed()->find($id)->forceDelete();
        return back()->with('error', 'User Delete Permanently!');
    }
    public function user_restore($id)
    {
        $this->authorize('team_member_restore');
        User::withTrashed()->find($id)->restore();
        return back()->with('message', 'User Restored!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $cookie = $request->cookie('admin_hash');
        $user = User::where('email', $request->email)->whereNot('admin_level', User::WEB_REGISTER)->where('hash', $cookie)->first();
        if ($user) {
            $credentials = $request->only('email', 'password');
            if (Auth::guard('admin_sess')->attempt($credentials)) {
                return to_route('admin.dashboard');
            }
            return redirect("admin/login")->with('info', 'Login details are not valid');
        }
        return redirect("admin/login")->with('info', 'Login details are not valid');
    }
    public function logout()
    {
        // Session::flush();
        Auth::guard('admin_sess')->logout();
        return to_route('home');
    }
}
