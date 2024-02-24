<?php
namespace App\Interfaces;

interface EmdUserTransactionInterface
{
    public function view_all_transaction($type);
    public function view_single_transaction($id);
    public function emd_paypro_callback($request);
    public function emd_custom_premium($request,$user_id);
    public function emd_transaction_search_req($request);
    public function emd_transaction_date_filter_page($start_date, $end_date);
    public function emd_user_plan_change($request, $user_id);
}
