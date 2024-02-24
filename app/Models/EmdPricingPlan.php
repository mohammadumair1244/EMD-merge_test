<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmdPricingPlan extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'user_id',
        'name',
        'label',
        'short_detail',
        'plan_type',
        'recurring_detail',
        'price',
        'sale_price',
        'discount_percentage',
        'duration',
        'duration_type',
        'coupan_paypro',
        'paypro_product_id',
        'mobile_app_product_id',
        'is_api',
        'is_popular',
        'is_custom',
        'is_mobile',
        'ordering_no',
        'unique_key',
    ];

    public const PLAN_TYPE = ['Other', 'Basic', 'Enterprise', 'Business', 'API Basic', 'API Enterprise', 'API Business'];
    public const DURATION_TYPE = ['Other', 'Weekly', 'Monthly', 'Bi Monthly', 'Tri Monthly', 'Quart Yearly ', 'Semi Yearly', 'Yearly', 'Bi Yearly'];
    public const IS_API = ['Web', 'API', 'Web & API'];
    public const WEB_PLAN = 0;
    public const API_PLAN = 1;
    public const WEB_AND_API_PLAN = 2;
    public const CUSTOM_TYPE = ['Simple Plan', 'Custom Plan', 'Dynamic Plan', 'User Created Plan', 'Registered Plan'];
    public const SIMPLE_PLAN = 0;
    public const CUSTOM_PLAN = 1;
    public const DYNAMIC_PLAN = 2;
    public const USER_CREATED_PLAN = 3;
    public const REGISTERED_PLAN = 4;
    public const MOBILE_OR_WEB = ['Web', 'Mobile'];

    public function emd_pricing_plan_allows()
    {
        return $this->hasMany(EmdPricingPlanAllow::class);
    }
    public function emd_plan_zone_price()
    {
        return $this->hasMany(EmdPlanZonePrice::class);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    public function scopeMobile($query)
    {
        return $query->where('is_mobile', 1);
    }
    public function scopeWebsite($query)
    {
        return $query->where('is_mobile', 0);
    }
    public function emd_user_transactions()
    {
        return $this->hasMany(EmdUserTransaction::class);
    }
}
