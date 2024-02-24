<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use OwenIt\Auditing\Contracts\Auditable;

// class Contact extends Model implements Auditable
class Contact extends Model
{
    use HasFactory, SoftDeletes;
    // use \OwenIt\Auditing\Auditable;
    protected $fillable = ['name', 'email', 'message', 'ip_info', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
