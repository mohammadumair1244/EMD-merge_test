<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmdEmailSetting extends Model  implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'email_type',
        'receiver_email',
        'send_from',
        'subject',
        'template',
    ];
    public const CONTACT_EMAIL = 1;
    public const FORGOT_EMAIL = 2;
    public const NEW_ACCOUNT_EMAIL = 3;
    public const DELETE_ACCOUNT = 4;
    public const CANCEL_MEMBERSHIP = 5;
    public const TYPE_OF_EMAIL = ['', 'Contact Us', 'Forgot Password', 'New Account', 'Delete User Account', 'Cancel Plan Membership'];
}
