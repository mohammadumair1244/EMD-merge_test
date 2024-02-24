<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdPermissionInterface;

class EmdPermissionController extends Controller
{
    public function __construct(protected EmdPermissionInterface $emd_permission_interface)
    {

    }
    public function view_all_permission_page()
    {
        $this->authorize('view_all_permission_list');
        return view('admin.emd-permission.index')->with(['permissions' => $this->emd_permission_interface->view_all_permission_page()]);
    }
}
