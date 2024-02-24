<?php
namespace App\Repositories;

use App\Interfaces\EmdUserProfileCommentInterface;
use App\Models\EmdUserProfileComment;

class EmdUserProfileCommentRepository implements EmdUserProfileCommentInterface
{
    public function __construct(protected EmdUserProfileComment $emd_user_profile_comment_model)
    {
    }

    public function add_profile_comment($request): bool
    {
        $request['action_user_id'] = auth()->guard('admin_sess')->user()->id;
        $this->emd_user_profile_comment_model->create($request);
        return true;
    }
}
