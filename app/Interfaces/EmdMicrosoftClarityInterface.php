<?php
namespace App\Interfaces;

interface EmdMicrosoftClarityInterface
{
    public function view_page();
    public function add_req($request);
    public function delete_link($id);
}
