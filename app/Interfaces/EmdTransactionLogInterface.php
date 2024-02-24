<?php
namespace App\Interfaces;

interface EmdTransactionLogInterface
{
    public function view_trans_log($order_no);
    public function view_trans_log_detail($id);
    public function download_log_json($id);
}
