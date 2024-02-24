<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmdPermission extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    public const TYPE = User::USER_TYPE;
    public const WEB_REGISTER = User::WEB_REGISTER;
    public const ADMIN = User::ADMIN;
    public const SUPPORT = User::SUPPORT;
    public const PRODUCT_MANAGER = User::PRODUCT_MANAGER;
    public const DEVELOPER = User::DEVELOPER;
    public const OTHER = User::OTHER;
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
