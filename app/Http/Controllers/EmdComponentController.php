<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdComponentInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmdComponentController extends Controller
{
    public function __construct(protected EmdComponentInterface $emd_component_interface)
    {

    }
    public function view_page()
    {
        $this->authorize('view_component');
        return view('admin.emd-component.view')->with([
            'emd_components' => $this->emd_component_interface->view_page(),
        ]);
    }
    public function add_page()
    {
        $this->authorize('add_component');
        return view('admin.emd-component.add')->with([
            'emd_components' => $this->emd_component_interface->add_page(),
        ]);
    }
    public function add_req(Request $request)
    {
        $this->authorize('add_component');
        $this->emd_component_interface->add_req($request);
        return back();
    }
    public function edit_page($id)
    {
        $this->authorize('edit_component');
        return view('admin.emd-component.edit')->with([
            'emd_component' => $this->emd_component_interface->edit_page($id),
        ]);
    }
    public function edit_req(Request $request, $id)
    {
        $this->authorize('edit_component');
        $this->emd_component_interface->edit_req($request, $id);
        return redirect()->route('custom_field.view_page');
    }
    public function trash_view_page()
    {
        $this->authorize('trash_view_component');
        return view('admin.emd-component.trash')->with([
            'emd_components' => $this->emd_component_interface->trash_view_page(),
        ]);
    }
    public function delete_link($id)
    {
        $this->authorize('delete_component');
        $this->emd_component_interface->delete_link($id);
        return back();
    }
    public function restore_link($id)
    {
        $this->authorize('restore_component');
        $this->emd_component_interface->restore_link($id);
        return back();
    }
    public function child_page($id)
    {
        $this->authorize('view_component');
        return view('admin.emd-component.child')->with([
            'emd_components' => $this->emd_component_interface->child_page($id),
        ]);
    }

    // for website functionality
    public function get_component(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lang' => 'required|min:2|max:2|string',
            'key' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $get_component_row = $this->emd_component_interface->get_component($request);
        return response()->json(['error' => $get_component_row['error'], 'result' => json_decode($get_component_row['result']->json_body)]);
    }
}
