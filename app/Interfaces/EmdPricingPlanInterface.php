<?php
namespace App\Interfaces;

interface EmdPricingPlanInterface
{
    public function view_pricing_plan();
    public function create_pricing_plan($request);
    public function edit_pricing_plan($id);
    public function update_pricing_plan($request, $id);
    public function trash_pricing_plan();
    public function destroy_pricing_plan($id);
    public function restore_pricing_plan($id);
    public function permanent_delete_pricing_plan($id);
    public function emd_our_pricing_plans($ip);
    public function emd_our_custom_pricing_plans($request);
    public function ordering_no_pricing_plan($request);
    public function emd_pricing_plan_show_hide($id, $is_active);
}
