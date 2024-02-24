<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdUserPermissionInterface;
use Illuminate\Http\Request;

class EmdUserPermissionController extends Controller
{
    public function __construct(protected EmdUserPermissionInterface $emd_user_permission_interface)
    {

    }
    public function allow_team_permision_req(Request $request, $user_id)
    {
        $this->authorize('allow_permission');
        $this->emd_user_permission_interface->allow_team_permision_req($request, $user_id);
        return back();
    }
}
