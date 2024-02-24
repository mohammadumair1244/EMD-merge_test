<?php
namespace App\Interfaces;

interface EmdEmailTemplateInterface
{
    public function view_page();
    public function create_page($request);
}
