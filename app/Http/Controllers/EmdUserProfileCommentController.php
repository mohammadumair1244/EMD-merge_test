<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdUserProfileCommentInterface;

class EmdUserProfileCommentController extends Controller
{
    public function __construct(protected EmdUserProfileCommentInterface $emd_user_profile_comment_interface)
    {
    }
    public function add_profile_comment($request)
    {
        $this->emd_user_profile_comment_interface->add_profile_comment($request);
    }
}
