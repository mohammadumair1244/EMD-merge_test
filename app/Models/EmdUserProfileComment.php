<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmdUserProfileComment extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'action_user_id',
        'action_type',
        'user_id',
        'detail',
    ];

    public function action_user()
    {
        return $this->belongsTo(User::class, 'action_user_id');
    }
}
