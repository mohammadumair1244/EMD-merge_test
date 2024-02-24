<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmdPricingPlanAllow extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'emd_pricing_plan_id',
        'tool_id',
        'queries_limit',
        'allow_modes',
        'allow_json',
    ];
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
