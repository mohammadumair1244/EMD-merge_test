<?php
namespace App\Interfaces;

interface EmdPricingPlanAllowInterface
{
    public function view_and_add_pricing_plan_allow($plan_id);
    public function create_pricing_plan_allow($request, $plan_id);
    public function destroy_pricing_plan_allow($plan_id, $id);
    public function edit_pricing_plan_allow($plan_id, $id);
    public function update_pricing_plan_allow($request, $plan_id, $id);
}
