<?php

namespace App\Interfaces;

interface EmdFeedbackInterface
{
    public function emd_feedback_page();
    public function emd_delete_feedback($id);
    public function emd_restore_feedback($id);
    public function emd_trash_feedback_page();
    public function emd_feedback_req($request);
    public function emd_feedback_date_filter_page($start_date, $end_date);
}
