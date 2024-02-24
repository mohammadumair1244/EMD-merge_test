<?php
namespace App\Repositories;

use App\Interfaces\EmdUserPermissionInterface;
use App\Models\User;

class EmdUserPermissionRepository implements EmdUserPermissionInterface
{
    public function __construct(protected User $user_model)
    {
    }

    public function allow_team_permision_req($request, $user_id): bool
    {
        $user = $this->user_model->find($user_id);
        $user->emd_permission()->sync($request->input("emd_permissions"));
        return true;
    }
}
