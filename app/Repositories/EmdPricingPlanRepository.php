<?php
namespace App\Repositories;

use App\Interfaces\EmdPricingPlanInterface;
use App\Models\EmdPlanZonePrice;
use App\Models\EmdPricingPlan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EmdPricingPlanRepository implements EmdPricingPlanInterface
{
    public function __construct(
        protected EmdPricingPlan $emd_pricing_plan_model,
        protected EmdPlanZonePrice $emd_plan_zone_price_model) {
    }

    public function view_pricing_plan(): EmdPricingPlan | Collection
    {
        return $this->emd_pricing_plan_model->withSum('emd_pricing_plan_allows', 'queries_limit')->get();
    }
    public function create_pricing_plan($request): bool
    {
        $request['unique_key'] = Str::lower(str_replace(" ", "_", $request['unique_key']));
        $this->emd_pricing_plan_model->create($request->except("_token"));
        return true;
    }
    public function edit_pricing_plan($id): ?EmdPricingPlan
    {
        return $this->emd_pricing_plan_model->find($id);
    }
    public function update_pricing_plan($request, $id): bool
    {
        $request['unique_key'] = Str::lower(str_replace(" ", "_", $request['unique_key']));
        $this->emd_pricing_plan_model->where('id', $id)->update($request->except("_token"));
        return true;
    }
    public function trash_pricing_plan(): EmdPricingPlan | Collection
    {
        return $this->emd_pricing_plan_model->onlyTrashed()->get();
    }
    public function destroy_pricing_plan($id): bool
    {
        $this->emd_pricing_plan_model->destroy($id);
        return true;
    }
    public function restore_pricing_plan($id): bool
    {
        $this->emd_pricing_plan_model->withTrashed()->find($id)->restore();
        return true;
    }
    public function permanent_delete_pricing_plan($id): bool
    {
        $this->emd_pricing_plan_model->onlyTrashed()->find($id)->forceDelete();
        return true;
    }
    public function ordering_no_pricing_plan($request): bool
    {
        $this->emd_pricing_plan_model->where('id', $request->id)->update(['ordering_no' => $request->ordering_no]);
        return true;
    }
    public function emd_our_pricing_plans($ip): EmdPricingPlan | Collection
    {
        return $this->emd_our_pricing_plans_static(ip: $ip);
    }
    public static function emd_our_pricing_plans_static(string $ip, null | int | string $id = null, null | string $unique_key = null): Collection | EmdPricingPlan | null
    {
        $emd_plan_zone_price_model_rows = EmdPlanZonePrice::get();
        $emd_pricing_plans = EmdPricingPlan::with('emd_pricing_plan_allows')->withSum('emd_pricing_plan_allows AS max_queries_limit', 'queries_limit')->withCount('emd_plan_zone_price AS is_zone_price');
        $country_code = "None";
        if (count($emd_plan_zone_price_model_rows) > 0) {
            $response = Http::get('http://ip-api.com/json/' . $ip);
            $res = @$response->collect()->only(['countryCode'])->toJson();
            $detail_res = @json_decode($res);
            $country_code = @$detail_res->countryCode ?: $country_code;
            $emd_pricing_plans = $emd_pricing_plans->with('emd_plan_zone_price', function ($query) use ($country_code) {
                return $query->whereHas('emd_country', function ($query1) use ($country_code) {
                    return $query1->where('code', $country_code);
                });
            });
        }
        $emd_pricing_plans = $emd_pricing_plans->where('is_custom', EmdPricingPlan::SIMPLE_PLAN)->active();
        if ($id != null) {
            return $emd_pricing_plans->where('id', $id)->first();
        } elseif ($unique_key != null) {
            return $emd_pricing_plans->where('unique_key', $unique_key)->first();
        } else {
            return $emd_pricing_plans->orderBy("ordering_no")->get();
        }
    }

    public function emd_our_custom_pricing_plans($request): EmdPricingPlan | Collection
    {
        return $this->emd_pricing_plan_model->with('emd_pricing_plan_allows')->withSum('emd_pricing_plan_allows AS max_queries_limit', 'queries_limit')->where('is_custom', $this->emd_pricing_plan_model::SIMPLE_PLAN)->get();
    }
    public function emd_pricing_plan_show_hide($id, $is_active): bool
    {
        $this->emd_pricing_plan_model->where('id', $id)->update(['is_active' => (int) $is_active]);
        return true;
    }
}
