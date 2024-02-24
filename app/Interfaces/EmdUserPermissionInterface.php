<?php
namespace App\Interfaces;

interface EmdUserPermissionInterface
{
    public function allow_team_permision_req($request, $user_id);
}
