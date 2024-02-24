<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use OwenIt\Auditing\Contracts\Auditable;

// class EmdFeedback extends Model  implements Auditable
class EmdFeedback extends Model
{
    use HasFactory, SoftDeletes;
    // use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'user_id',
        'tool_id',
        'name',
        'email',
        'message',
        'rating'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
