<?php
namespace App\Interfaces;

interface EmdCustomPageInterface
{
    public function view_page();
    public function create_page($request);
    public function trash_page();
    public function destroy($id);
    public function restore($id);
    public function edit_page($id);
    public function update_page($request, $id);
    public function download_content($id);
    public function upload_content($request, $id);
}
