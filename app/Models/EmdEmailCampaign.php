<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmdEmailCampaign extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'emd_email_template_id',
        'from_email',
        'from_name',
        'from_subject',
        'title',
        'start_date',
        'user_status',
        'per_hour_emails',
        'status',
        'total_emails',
        'send_emails',
        'testing_email',
    ];
    public function emd_email_template()
    {
        return $this->belongsTo(EmdEmailTemplate::class);
    }
}
