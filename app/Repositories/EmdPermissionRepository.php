<?php
namespace App\Repositories;

use App\Interfaces\EmdPermissionInterface;
use App\Models\EmdPermission;
use Illuminate\Database\Eloquent\Collection;

class EmdPermissionRepository implements EmdPermissionInterface
{
    public function __construct(protected EmdPermission $emd_permission_model)
    {
    }

    public function view_all_permission_page(): EmdPermission | Collection
    {
        return $this->emd_permission_model->orderBy("type","ASC")->get();
    }
}
