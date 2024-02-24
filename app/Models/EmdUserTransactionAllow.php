<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use OwenIt\Auditing\Contracts\Auditable;

// class EmdUserTransactionAllow extends Model implements Auditable
class EmdUserTransactionAllow extends Model
{
    use HasFactory, SoftDeletes;
    // use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'user_id',
        'emd_user_transaction_id',
        'tool_id',
        'tool_slug_key',
        'queries_limit',
        'queries_used',
        'allow_json',
    ];

    public function emd_user_transaction()
    {
        return $this->belongsTo(EmdUserTransaction::class);
    }
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
