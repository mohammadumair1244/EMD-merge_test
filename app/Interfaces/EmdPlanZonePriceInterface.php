<?php
namespace App\Interfaces;

interface EmdPlanZonePriceInterface
{
    public function view_and_add_zone_pricing($plan_id);
    public function create_zone_pricing($request, $plan_id);
    public function destroy_zone_pricing($id);
    public function update_zone_pricing($request, $id);
}
