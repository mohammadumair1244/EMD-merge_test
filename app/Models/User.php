<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'hash',
        'admin_level',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public const WEB_REGISTER = 0;
    public const ADMIN = 1;
    public const SUPPORT = 2;
    public const PRODUCT_MANAGER = 3;
    public const DEVELOPER = 4;
    public const OTHER = 5;

    public const USER_TYPE = ['Web User', 'Admin', 'Support', 'Product Manager', 'Developer', 'Other'];
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
        );
    }
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => strtolower($value),
        );
    }
    public function emd_permission()
    {
        return $this->belongsToMany(EmdPermission::class);
    }
    public function emd_web_user()
    {
        return $this->hasOne(EmdWebUser::class);
    }
    public function emd_user_transactions()
    {
        return $this->hasMany(EmdUserTransaction::class);
    }
}
