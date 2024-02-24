<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdPricingPlanInterface;
use App\Models\EmdPricingPlan;
use Illuminate\Http\Request;

class EmdPricingPlanController extends Controller
{
    public function __construct(protected EmdPricingPlanInterface $emd_pricing_plan_interface)
    {
    }
    public function view_pricing_plan()
    {
        $this->authorize('view_pricing_plan');
        $emd_pricing_plans = $this->emd_pricing_plan_interface->view_pricing_plan();
        return view('admin.emd-pricing-plan.index')->with([
            'web_pricing_plans' => $emd_pricing_plans->whereIn('is_custom', [EmdPricingPlan::SIMPLE_PLAN, EmdPricingPlan::REGISTERED_PLAN]),
            'custom_pricing_plans' => $emd_pricing_plans->where('is_custom', EmdPricingPlan::CUSTOM_PLAN),
            'dynamic_pricing_plans' => $emd_pricing_plans->where('is_custom', EmdPricingPlan::DYNAMIC_PLAN),
        ]);

    }
    public function add_pricing_plan()
    {
        $this->authorize('add_pricing_plan');
        return view('admin.emd-pricing-plan.add');
    }
    public function create_pricing_plan(Request $request)
    {
        $this->authorize('add_pricing_plan');
        $this->emd_pricing_plan_interface->create_pricing_plan($request);
        return redirect()->route('emd_pricing_plan_view');
    }
    public function edit_pricing_plan($id)
    {
        $this->authorize('edit_pricing_plan');
        return view('admin.emd-pricing-plan.edit')->with([
            'emd_pricing_plan' => $this->emd_pricing_plan_interface->edit_pricing_plan($id),
        ]);
    }
    public function update_pricing_plan(Request $request, $id)
    {
        $this->authorize('edit_pricing_plan');
        $this->emd_pricing_plan_interface->update_pricing_plan($request, $id);
        return redirect()->route('emd_pricing_plan_view');
    }
    public function trash_pricing_plan()
    {
        $this->authorize('view_trash_pricing_plan');
        return view('admin.emd-pricing-plan.trash')->with([
            'emd_pricing_plans' => $this->emd_pricing_plan_interface->trash_pricing_plan(),
        ]);
    }

    public function destroy_pricing_plan($id)
    {
        $this->authorize('delete_pricing_plan');
        $this->emd_pricing_plan_interface->destroy_pricing_plan($id);
        return redirect()->back();
    }
    public function restore_pricing_plan($id)
    {
        $this->authorize('restore_pricing_plan');
        $this->emd_pricing_plan_interface->restore_pricing_plan($id);
        return redirect()->back();
    }
    public function permanent_delete_pricing_plan($id)
    {
        $this->authorize('delete_pricing_plan');
        $this->emd_pricing_plan_interface->permanent_delete_pricing_plan($id);
        return redirect()->back();
    }
    public function ordering_no_pricing_plan(Request $request)
    {
        $this->authorize('edit_pricing_plan');
        $this->emd_pricing_plan_interface->ordering_no_pricing_plan($request);
        return response()->json(true);
    }
    public function emd_pricing_plan_show_hide($id, $is_active)
    {
        $this->authorize('edit_pricing_plan');
        $this->emd_pricing_plan_interface->emd_pricing_plan_show_hide($id, $is_active);
        return back();
    }

    // for emd website
    public function emd_our_pricing_plans(Request $request)
    {
        return $this->emd_pricing_plan_interface->emd_our_pricing_plans(@$request->header()['x-real-ip'][0] ?: '127.0.0.1');
    }
    public function emd_our_custom_pricing_plans(Request $request)
    {
        return $this->emd_pricing_plan_interface->emd_our_custom_pricing_plans($request);
    }
}
