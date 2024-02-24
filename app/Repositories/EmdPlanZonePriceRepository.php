<?php
namespace App\Repositories;

use App\Interfaces\EmdPlanZonePriceInterface;
use App\Models\EmdCountry;
use App\Models\EmdPlanZonePrice;
use App\Models\EmdPricingPlan;

class EmdPlanZonePriceRepository implements EmdPlanZonePriceInterface
{
    public function __construct(
        protected EmdPlanZonePrice $emd_plan_zone_price_model,
        protected EmdCountry $emd_country_model,
        protected EmdPricingPlan $emd_pricing_plan_model) {
    }

    public function view_and_add_zone_pricing($plan_id): array
    {
        return [
            $this->emd_plan_zone_price_model->with('emd_country')->where('emd_pricing_plan_id', $plan_id)->get(),
            $this->emd_country_model->select("id", "name", "code")->get(),
            $this->emd_pricing_plan_model->where('id', $plan_id)->first()?->name ?: '',
        ];
    }
    public function create_zone_pricing($request, $plan_id): bool
    {
        $request['emd_pricing_plan_id'] = $plan_id;
        $this->emd_plan_zone_price_model->create($request->except(['_token']));
        return true;
    }
    public function destroy_zone_pricing($id): bool
    {
        $this->emd_plan_zone_price_model->destroy($id);
        return true;
    }
    public function update_zone_pricing($request, $id): bool
    {
        $this->emd_plan_zone_price_model->where('id', $id)->update($request->except(['_token']));
        return true;
    }
}
