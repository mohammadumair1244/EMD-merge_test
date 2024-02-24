<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdTransactionLogInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EmdTransactionLogController extends Controller
{
    public function __construct(protected EmdTransactionLogInterface $emd_transaction_log_interface)
    {

    }
    public function view_trans_log($order_no = null)
    {
        $this->authorize('view_all_transactions');
        $trans_logs = $this->emd_transaction_log_interface->view_trans_log($order_no);
        return view('admin.emd-transaction.trans-logs')->with(['trans_logs' => $trans_logs]);
    }
    public function view_trans_log_search(Request $request)
    {
        $this->authorize('view_all_transactions');
        return redirect()->route('emd_transaction_logs_page', ['order_no' => $request->order_no]);
    }
    public function view_trans_log_detail($id)
    {
        $this->authorize('view_transaction_detail');
        $emd_trans_log = $this->emd_transaction_log_interface->view_trans_log_detail($id);
        return view('admin.emd-transaction.trans-log-detail')->with(['emd_trans_log' => $emd_trans_log]);
    }
    public function download_log_json($id)
    {
        $this->authorize('download_transaction_log_json');
        $emd_trans_log = $this->emd_transaction_log_interface->download_log_json($id);
        return Response::make($emd_trans_log->trans_log, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename=' . $emd_trans_log->order_no . '.json',
        ]);
    }
}
