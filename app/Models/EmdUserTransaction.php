<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmdUserTransaction extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'user_id',
        'emd_pricing_plan_id',
        'order_no',
        'product_no',
        'order_status',
        'order_currency_code',
        'order_item_price',
        'payment_method_name',
        'payment_from',
        'purchase_date',
        'plan_days',
        'expiry_date',
        'is_refund',
        'renewal_type',
        'is_test_mode',
        'all_json_transaction',
    ];
    public const TRAN_RUNNING = 0;
    public const TRAN_REFUNDED = 1;
    public const TRAN_EXP_USED = 2;
    public const TRAN_STATUS = ['Running', 'Refunded', 'Expired or Used'];

    public const ORIGINAL_MODE = 0;
    public const TEST_MODE = 1;
    public const REGISTER_MODE = 2;
    public const TEST_MODE_TYPE = ['Original', 'Test', 'Register'];

    public const OS_PROCESSED = 'Processed';
    public const OS_CANCELED = 'Canceled';
    public const OS_REFUNDED = 'Refunded';
    public const OS_WAITING = 'Waiting';
    public const OS_CHARGE_BACK = 'Chargeback';
    public const OS_CHANGE_PLAN = 'ChangePlan';
    public const OS_REGISTER_PLAN = 'RegisterPlan';

    public const RENEWAL_AUTO="Auto";
    public const RENEWAL_MANUAL="Manual";
    public const RENEWAL_NONE="None";
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function emd_pricing_plan()
    {
        return $this->belongsTo(EmdPricingPlan::class);
    }
    public function emd_user_transaction_allows()
    {
        return $this->hasMany(EmdUserTransactionAllow::class);
    }
}
