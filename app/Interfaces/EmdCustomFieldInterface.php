<?php
namespace App\Interfaces;

interface EmdCustomFieldInterface
{
    public function view_page();
    public function add_page();
    public function add_req($request);
    public function delete_link($id);
    public function restore_link($id);
    public function trash_view_page();
    public function edit_page($id);
    public function edit_req($request, $id);
    public function get_key_tool_filter($request);
}
