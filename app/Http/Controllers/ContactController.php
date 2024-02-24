<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\EmdEmailSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view_contact_us');
        $contacts = Contact::with('user', 'user.emd_web_user')->orderBy("id", "DESC")->get();
        return view('admin.contacts.index')->with([
            'contacts' => $contacts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|string',
            'email' => 'required|email',
            'message' => 'required|max:500',
        ]);
        $data = [];
        try {
            $response = Http::get('http://ip-api.com/json/' . @$request->header()[config('constants.user_ip_get')][0] ?? '127.0.0.1');
            $result = $response->collect()->only(['query', 'country', 'city', 'org'])->toJson();
            $data['country'] = json_decode($result)->country;
            $data['query'] = json_decode($result)->query;
            $data['city'] = json_decode($result)->city;
            $data['org'] = json_decode($result)->org;
        } catch (\Throwable $th) {
            $data['country'] = 'none';
            $data['query'] = @$request->header()[config('constants.user_ip_get')][0] ?? '127.0.0.1';
            $data['city'] = 'none';
            $data['org'] = 'none';
        }
        $detail_res = json_decode(json_encode($data));
        $html_body = "<ul>";
        $html_body .= "<li>" . @$detail_res?->country . "</li>";
        $html_body .= "<li>" . @$detail_res?->city . "</li>";
        $html_body .= "<li>" . @$detail_res?->org . "</li>";
        $html_body .= "<li>" . @$detail_res?->query . "</li>";
        $html_body .= "</ul>";
        $emd_email_setting = EmdEmailSetting::where('email_type', EmdEmailSetting::CONTACT_EMAIL)->first();
        if ($emd_email_setting->is_active) {
            $email_body = @$emd_email_setting->template ?: "<p>Name @name</p> <p>Email @email</p>  <p>Message @message</p>   <p>@body</p>";
            $email_body = str_replace("@name", @$request->name, $email_body);
            $email_body = str_replace("@email", @$request->email, $email_body);
            $email_body = str_replace("@message", @$request->message, $email_body);
            $email_body = str_replace("@body", $html_body, $email_body);
            EmdSendEmailController::sendToEmail(@$request->email, $emd_email_setting->send_from, $emd_email_setting->subject, $email_body, $emd_email_setting->receiver_email);
        }
        $user_id = 0;
        $user_get = User::where('email', @$request->email)->first();
        if (@$user_get) {
            $user_id = $user_get->id;
        }
        Contact::create([
            'name' => @$request->name,
            'email' => @$request->email,
            'message' => @$request->message,
            'ip_info' => json_encode(@$detail_res),
            'user_id' => $user_id,
        ]);
        return back();
        // return response()->json(['message' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $this->authorize('delete_contact_us');
        $contact->delete();
        return back();
    }

    public function contact_permanent_destroy($id)
    {
        $this->authorize('delete_contact_us');
        $contact = Contact::onlyTrashed()->find($id);
        $contact->forceDelete();
        return back();
    }

    public function trash()
    {
        $this->authorize('view_trash_contact_us');
        $contacts = Contact::onlyTrashed()->get();
        return view('admin.contacts.trash')->with([
            'contacts' => $contacts,
        ]);
    }

    public function restore($id)
    {
        $this->authorize('restore_contact_us');
        Contact::withTrashed()->find($id)->restore();
        return back();
    }
    public function emd_contact_date_filter_page($start_date, $end_date)
    {
        $this->authorize('view_contact_us');
        $contacts = Contact::with('user', 'user.emd_web_user')->whereBetween('created_at', [$start_date, $end_date])->get();
        return view('admin.contacts.date-filter')->with([
            'contacts' => $contacts,
        ]);
    }
}
