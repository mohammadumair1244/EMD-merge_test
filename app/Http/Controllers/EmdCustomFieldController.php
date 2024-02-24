<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdCustomFieldInterface;
use Illuminate\Http\Request;

class EmdCustomFieldController extends Controller
{
    public function __construct(protected EmdCustomFieldInterface $emd_custom_field_interface)
    {

    }
    public function view_page()
    {
        $this->authorize('view_custom_field');
        return view('admin.emd-custom-field.view')->with([
            'custom_fields' => $this->emd_custom_field_interface->view_page(),
        ]);
    }
    public function add_page()
    {
        $this->authorize('add_custom_field');
        $add_page_data = $this->emd_custom_field_interface->add_page();
        return view('admin.emd-custom-field.add')->with([
            'emd_custom_pages' => $add_page_data['custom_pages'],
            'tools' => $add_page_data['tools'],
        ]);
    }
    public function add_req(Request $request)
    {
        $this->authorize('add_custom_field');
        $this->emd_custom_field_interface->add_req($request);
        return back();
    }
    public function edit_page($id)
    {
        $this->authorize('edit_custom_field');
        $edit_page_data = $this->emd_custom_field_interface->edit_page($id);
        return view('admin.emd-custom-field.edit')->with([
            'emd_custom_pages' => $edit_page_data['custom_pages'],
            'tools' => $edit_page_data['tools'],
            'emd_custom_field' => $edit_page_data['emd_custom_field'],
        ]);
    }
    public function edit_req(Request $request, $id)
    {
        $this->authorize('edit_custom_field');
        $this->emd_custom_field_interface->edit_req($request, $id);
        return redirect()->route('custom_field.view_page');
    }
    public function trash_view_page()
    {
        $this->authorize('trash_view_custom_field');
        return view('admin.emd-custom-field.trash')->with([
            'custom_fields' => $this->emd_custom_field_interface->trash_view_page(),
        ]);
    }
    public function delete_link($id)
    {
        $this->authorize('delete_custom_field');
        $this->emd_custom_field_interface->delete_link($id);
        return back();
    }
    public function restore_link($id)
    {
        $this->authorize('restore_custom_field');
        $this->emd_custom_field_interface->restore_link($id);
        return back();
    }
    public function get_key_tool_filter(Request $request)
    {
        $data = $this->emd_custom_field_interface->get_key_tool_filter($request);
        return response()->json(['data' => $data]);
    }
}
