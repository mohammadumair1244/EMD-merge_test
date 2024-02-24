<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmdTransactionLog extends Model
{
    use HasFactory;
    protected $fillable=[
        'order_no',
        'trans_log',
        'status',
        'status_message',
        'paypro_ip'
    ];
}
