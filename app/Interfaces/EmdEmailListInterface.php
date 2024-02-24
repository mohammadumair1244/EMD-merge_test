<?php
namespace App\Interfaces;

interface EmdEmailListInterface
{
    public function emd_email_list_page();
    public function emd_email_list_create($request);
    public function emd_email_list_delete($id);
}
