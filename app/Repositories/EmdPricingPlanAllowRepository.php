<?php

namespace App\Repositories;

use App\Interfaces\EmdPricingPlanAllowInterface;
use App\Models\EmdPricingPlan;
use App\Models\EmdPricingPlanAllow;
use App\Models\Tool;

class EmdPricingPlanAllowRepository implements EmdPricingPlanAllowInterface
{
    public function __construct(
        protected EmdPricingPlanAllow $emd_pricing_plan_allow_model,
        protected Tool $tool_model,
        protected EmdPricingPlan $emd_pricing_plan_model) {
    }

    public function view_and_add_pricing_plan_allow($plan_id): array
    {
        return [
            $this->emd_pricing_plan_allow_model->with('tool')->where('emd_pricing_plan_id', $plan_id)->get(),
            $this->tool_model->select("id", "name", "parent_id")->whereColumn('id', 'parent_id')->get(),
            $this->emd_pricing_plan_model->where('id', $plan_id)->first()?->name ?: '',
        ];
    }
    public function create_pricing_plan_allow($request, $plan_id): bool
    {
        $already_added = $this->emd_pricing_plan_allow_model->where('tool_id', $request['tool_id'])->where('emd_pricing_plan_id', $plan_id)->first();
        if ($already_added) {
            return false;
        }
        $request['emd_pricing_plan_id'] = $plan_id;
        $allow_json = [];
        foreach ($request['keys'] as $key => $val) {
            $allow_json[$val] = (int) $request['default_values'][$key];
        }
        $request['allow_json'] = json_encode($allow_json);
        $request['tool_slug_key'] = str_replace('-', '_', $this->tool_model->where('id', $request['tool_id'])->first()?->slug ?? 'all-web-tool');
        $this->emd_pricing_plan_allow_model->create($request->except(["_token", 'keys', 'default_values', 'query']));
        return true;
    }
    public function destroy_pricing_plan_allow($plan_id, $id): bool
    {
        $this->emd_pricing_plan_allow_model->destroy($id);
        return true;
    }
    public function edit_pricing_plan_allow($plan_id, $id): array
    {
        return [
            'emd_pricing_plan_allow' => $this->emd_pricing_plan_allow_model->where('id', $id)->first(),
            'tools' => $this->tool_model->select("id", "name", "parent_id")->whereColumn('id', 'parent_id')->get(),
        ];
    }
    public function update_pricing_plan_allow($request, $plan_id, $id): bool
    {
        $allow_json = [];
        if (empty(@$request['keys'])) {
            return false;
        }
        foreach ($request['keys'] as $key => $val) {
            $allow_json[$val] = (int) $request['default_values'][$key];
        }
        $request['allow_json'] = json_encode($allow_json);
        $this->emd_pricing_plan_allow_model->where('id', $id)->update($request->except(["_token", 'keys', 'default_values']));
        return true;
    }
}
