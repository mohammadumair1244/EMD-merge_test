<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdPricingPlanAllowInterface;
use Illuminate\Http\Request;

class EmdPricingPlanAllowController extends Controller
{
    public function __construct(protected EmdPricingPlanAllowInterface $emd_pricing_plan_allow_interface)
    {
    }
    public function view_and_add_pricing_plan_allow($plan_id)
    {
        $this->authorize('add_pricing_plan');
        $emd_pricing_plan_allow = $this->emd_pricing_plan_allow_interface->view_and_add_pricing_plan_allow($plan_id);
        return view('admin.emd-pricing-plan.set-query')->with([
            'emd_pricing_plan_allows' => $emd_pricing_plan_allow[0],
            'tools' => $emd_pricing_plan_allow[1],
            'plan_name' => $emd_pricing_plan_allow[2],
        ]);
    }
    public function create_pricing_plan_allow(Request $request, $plan_id)
    {
        if (!$request->has('default_values')) {
            return back()->with('error', 'Custom Fields Missing');
        }
        $this->authorize('add_pricing_plan');
        $res_val = $this->emd_pricing_plan_allow_interface->create_pricing_plan_allow($request, $plan_id);
        return back()->with('error', $res_val ? 'Successfully Added' : 'Already Added');
    }
    public function destroy_pricing_plan_allow($plan_id, $id)
    {
        $this->authorize('delete_pricing_plan');
        $this->emd_pricing_plan_allow_interface->destroy_pricing_plan_allow($plan_id, $id);
        return back()->with('error', 'Successfully deleted');
    }
    public function edit_pricing_plan_allow($plan_id, $id)
    {
        $this->authorize('edit_pricing_plan');
        $emd_pricing_plan_allow = $this->emd_pricing_plan_allow_interface->edit_pricing_plan_allow($plan_id, $id);
        return view('admin.emd-pricing-plan.edit-query')->with([
            'emd_pricing_plan_allow' => $emd_pricing_plan_allow['emd_pricing_plan_allow'],
            'tools' => $emd_pricing_plan_allow['tools'],
        ]);
    }
    public function update_pricing_plan_allow(Request $request, $plan_id, $id)
    {
        $this->authorize('edit_pricing_plan');
        $this->emd_pricing_plan_allow_interface->update_pricing_plan_allow($request, $plan_id, $id);
        return redirect()->route('emd_view_and_add_pricing_plan_allow', ['plan_id' => $plan_id])->with('error', 'Successfully Updated');
    }
}
