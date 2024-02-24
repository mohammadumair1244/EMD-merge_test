<?php
namespace App\Interfaces;

interface EmdEmailCampaignInterface
{
    public function view_users_page();
    public function add_page();
    public function create_page($request);
    public function change_status($id, $status);
    public function send_test_email($request);
}
