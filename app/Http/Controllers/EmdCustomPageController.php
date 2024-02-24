<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdCustomPageInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmdCustomPageController extends Controller
{
    public function __construct(protected EmdCustomPageInterface $emd_custom_page_interface)
    {

    }
    public function view_page()
    {
        $this->authorize('custom_page_view');
        return view('admin.emd-custom-page.view')->with([
            'custom_pages' => $this->emd_custom_page_interface->view_page(),
        ]);
    }
    public function add_page()
    {
        $this->authorize('custom_page_add');
        return view('admin.emd-custom-page.add');
    }
    public function create_page(Request $request)
    {
        $this->authorize('custom_page_add');
        $request->validate([
            'page_key' => 'unique:emd_custom_pages,page_key',
            'slug' => 'unique:emd_custom_pages,slug',
            'blade_file' => 'unique:emd_custom_pages,blade_file',
        ]);
        $this->emd_custom_page_interface->create_page($request);
        return back();
    }
    public function trash_page()
    {
        $this->authorize('custom_page_view');
        return view('admin.emd-custom-page.trash')->with([
            'custom_pages' => $this->emd_custom_page_interface->trash_page(),
        ]);
    }
    public function destroy($id)
    {
        $this->authorize('custom_page_delete');
        $this->emd_custom_page_interface->destroy($id);
        return back();
    }
    public function restore($id)
    {
        $this->authorize('custom_page_restore');
        $this->emd_custom_page_interface->restore($id);
        return back();
    }
    public function edit_page($id)
    {
        $this->authorize('custom_page_edit');
        return view('admin.emd-custom-page.edit')->with([
            'custom_page' => $this->emd_custom_page_interface->edit_page($id),
        ]);
    }
    public function update_page(Request $request, $id)
    {
        $this->authorize('custom_page_edit');
        $request->validate([
            'page_key' => 'unique:emd_custom_pages,page_key,' . $id,
            'slug' => 'unique:emd_custom_pages,slug,' . $id,
            'blade_file' => 'unique:emd_custom_pages,blade_file,' . $id,
        ]);
        $this->emd_custom_page_interface->update_page($request, $id);
        return back();
    }
    public function download_content($id)
    {
        $download_content_respo = $this->emd_custom_page_interface->download_content($id);
        if (Storage::put($download_content_respo['target'] . '/' . $download_content_respo['fileName'], $download_content_respo['content_to_store'])) {
            return Storage::download($download_content_respo['path']);
        } else {
            return back();
        }
    }
    public function upload_content(Request $request, $id)
    {
        $this->authorize('custom_page_edit');
        $this->emd_custom_page_interface->upload_content($request, $id);
        return back();
    }
}
