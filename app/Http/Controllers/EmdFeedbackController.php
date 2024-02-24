<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdFeedbackInterface;
use Illuminate\Http\Request;

class EmdFeedbackController extends Controller
{
    public function __construct(protected EmdFeedbackInterface $emd_feedback_interface)
    {
    }
    public function emd_feedback_page()
    {
        $this->authorize('view_feedback_list');
        return view('admin.emd-feedback.index')->with(['emd_feedbacks' => $this->emd_feedback_interface->emd_feedback_page()]);
    }
    public function emd_delete_feedback($id)
    {
        $this->authorize('delete_feedback');
        $this->emd_feedback_interface->emd_delete_feedback($id);
        return back();
    }
    public function emd_restore_feedback($id)
    {
        $this->authorize('restore_feedback');
        $this->emd_feedback_interface->emd_restore_feedback($id);
        return back();
    }
    public function emd_trash_feedback_page()
    {
        $this->authorize('view_trash_feedback_list');
        return view('admin.emd-feedback.trash')->with(['emd_feedbacks' => $this->emd_feedback_interface->emd_trash_feedback_page()]);
    }

    // for website function
    public function emd_feedback_req(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|string',
            'message' => 'required|min:3|max:300',
            'rating' => 'required|integer',
        ]);
        $feedback = $this->emd_feedback_interface->emd_feedback_req($request->except('_token'));
        if ($feedback[0]) {
            return response()->json(['status' => false, 'mess' => $feedback[1]]);
        } else {
            return response()->json(['status' => true, 'mess' => $feedback[1]]);
            // return redirect()->route('home');
        }
    }
    public function emd_feedback_date_filter_page($start_date, $end_date)
    {
        $this->authorize('view_feedback_list');
        return view('admin.emd-feedback.date-filter')->with(['emd_feedbacks' => $this->emd_feedback_interface->emd_feedback_date_filter_page($start_date, $end_date)]);
    }
}
