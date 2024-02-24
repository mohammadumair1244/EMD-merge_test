<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdEmailTemplateInterface;
use Illuminate\Http\Request;

class EmdEmailTemplateController extends Controller
{
    public function __construct(protected EmdEmailTemplateInterface $emd_email_template_interface)
    {

    }
    public function view_page()
    {
        $this->authorize('email_template_view');
        return view('admin.emd-email-template.view')->with([
            'templates' => $this->emd_email_template_interface->view_page(),
        ]);
    }
    public function add_page()
    {
        $this->authorize('email_template_add');
        return view('admin.emd-email-template.add');
    }
    public function create_page(Request $request)
    {
        $this->authorize('email_template_add');
        $this->emd_email_template_interface->create_page($request);
        return back();
    }
}
