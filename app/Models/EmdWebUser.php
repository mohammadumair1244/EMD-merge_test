<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmdWebUser extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'user_id',
        'register_from',
        'social_id',
        'api_key',
        'is_web_premium',
        'is_api_premium',
        'ip',
        'country',
        'city',
        'browser',
        'device',
        'last_login',
        'login_ip',
    ];

    public const FREE_USER = 0;
    public const PREMIUM_USER = 1;
    public const FREE_PLAN_USER = 2;
    public const PREMIUM_TYPE = ['Free', 'Premium', 'Free Plan'];

    public const REGISTER_FROM_WEB = 'web';
    public const REGISTER_FROM_MOBILE = 'mobile';
    public const REGISTER_FROM_GOOGLE = 'google';
    public const REGISTER_FROM_RANDOM = 'random';
    public const REGISTER_FROM_PAYMENT_TIME = 'running';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function emd_user_transactions()
    {
        return $this->hasMany(EmdUserTransaction::class, 'user_id', 'user_id');
    }
}
