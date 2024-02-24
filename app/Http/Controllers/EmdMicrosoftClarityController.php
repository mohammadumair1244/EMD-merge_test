<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdMicrosoftClarityInterface;
use Illuminate\Http\Request;

class EmdMicrosoftClarityController extends Controller
{
    public function __construct(protected EmdMicrosoftClarityInterface $emd_microsoft_clarity_interface)
    {

    }
    public function view_page()
    {
        $this->authorize('view_microsoft_clarity');
        $add_page_data = $this->emd_microsoft_clarity_interface->view_page();
        return view('admin.emd-microsoft-clarity.view')->with([
            'emd_custom_pages' => $add_page_data['custom_pages'],
            'tools' => $add_page_data['tools'],
            'emd_microsoft_clarity' => $add_page_data['emd_microsoft_clarity'],
            'availabilities' => [
                'Desktop',
                'Tablet',
                'Mobile',
                'PremiumUser'
            ],
        ]);
    }
    public function add_req(Request $request)
    {
        $this->authorize('add_microsoft_clarity');
        $this->emd_microsoft_clarity_interface->add_req($request);
        return back();
    }
    public function delete_link($id)
    {
        $this->authorize('delete_microsoft_clarity');
        $this->emd_microsoft_clarity_interface->delete_link($id);
        return back();
    }
}
