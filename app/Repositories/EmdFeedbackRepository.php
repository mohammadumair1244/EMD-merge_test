<?php

namespace App\Repositories;

use App\Interfaces\EmdFeedbackInterface;
use App\Models\EmdFeedback;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmdFeedbackRepository implements EmdFeedbackInterface
{
    public function __construct(protected EmdFeedback $emd_feedback_model)
    {
    }
    public function emd_feedback_page(): LengthAwarePaginator
    {
        return $this->emd_feedback_model->with('user', 'tool')->orderByDESC('id')->paginate(100);
    }
    public function emd_delete_feedback($id): bool
    {
        $this->emd_feedback_model->destroy($id);
        return true;
    }
    public function emd_restore_feedback($id): bool
    {
        $this->emd_feedback_model->withTrashed()->find($id)->restore();
        return true;
    }
    public function emd_trash_feedback_page(): EmdFeedback | Collection
    {
        return $this->emd_feedback_model->onlyTrashed()->get();
    }
    public function emd_feedback_req($request): array
    {
        if (auth()->guard('web_user_sess')->check()) {
            $request['user_id'] = auth()->guard('web_user_sess')->user()->id;
        }
        $this->emd_feedback_model->create($request);
        return [true, config('emd-response-string.feedback_send')];
    }
    public function emd_feedback_date_filter_page($start_date, $end_date): EmdFeedback | Collection
    {
        return $this->emd_feedback_model->with('user', 'tool')->whereBetween('created_at', [$start_date, $end_date])->get();
    }
}
