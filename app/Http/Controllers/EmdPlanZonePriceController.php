<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdPlanZonePriceInterface;
use Illuminate\Http\Request;

class EmdPlanZonePriceController extends Controller
{
    public function __construct(protected EmdPlanZonePriceInterface $emd_plan_zone_price_interface)
    {
    }
    public function view_and_add_zone_pricing($plan_id)
    {
        $this->authorize('add_pricing_plan');
        $emd_zone_pricing = $this->emd_plan_zone_price_interface->view_and_add_zone_pricing($plan_id);
        return view('admin.emd-pricing-plan.set-zone-pricing')->with([
            'emd_zone_pricings' => $emd_zone_pricing[0],
            'countries' => $emd_zone_pricing[1],
            'plan_name' => $emd_zone_pricing[2],
        ]);
    }
    public function create_zone_pricing(Request $request, $plan_id)
    {
        $this->authorize('add_pricing_plan');
        $this->emd_plan_zone_price_interface->create_zone_pricing($request, $plan_id);
        return back();
    }
    public function destroy_zone_pricing($id)
    {
        $this->authorize('delete_pricing_plan');
        $this->emd_plan_zone_price_interface->destroy_zone_pricing($id);
        return back();
    }
    public function update_zone_pricing(Request $request, $id)
    {
        $this->authorize('edit_pricing_plan');
        $this->emd_plan_zone_price_interface->update_zone_pricing($request, $id);
        return back();
    }
}
