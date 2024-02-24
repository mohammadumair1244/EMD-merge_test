<?php
namespace App\Repositories;

use App\Interfaces\EmdTransactionLogInterface;
use App\Models\EmdTransactionLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmdTransactionLogRepository implements EmdTransactionLogInterface
{
    public function __construct(protected EmdTransactionLog $emd_transaction_log_model)
    {
    }

    public function view_trans_log($order_no): LengthAwarePaginator
    {
        if ($order_no != null) {
            $trans_logs = $this->emd_transaction_log_model->where('order_no', $order_no)->orderBy('id', 'DESC')->paginate(100);
        } else {
            $trans_logs = $this->emd_transaction_log_model->orderBy('id', 'DESC')->paginate(100);
        }
        return $trans_logs;
    }
    public function view_trans_log_detail($id): ?EmdTransactionLog
    {
        return $this->emd_transaction_log_model->where('id', $id)->first();
    }
    public function download_log_json($id): ?EmdTransactionLog
    {
        return $this->emd_transaction_log_model->where('id', $id)->first();
    }
}
