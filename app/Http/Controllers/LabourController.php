<?php

namespace App\Http\Controllers;

use App\Models\LaborCategory;
use App\Models\Labour;
use App\Models\Log;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabourController extends Controller
{

    public function SaveLabours(Request $request, $encodedOrderId)
    {
        $decodedOrderId = base64_decode($encodedOrderId);
    
        $validatedData = $request->validate([
            'date' => 'required|date',
            'labor_category' => 'nullable|array', 
            'labor_category.*' => 'nullable|string|max:255', 
            'per_labor_amount' => 'nullable|array', 
            'per_labor_amount.*' => 'nullable|required_if:labor_category.*,true|numeric|min:0', 
            'number_of_labors' => 'nullable|array', 
            'number_of_labors.*' => 'nullable|required_if:labor_category.*,true|numeric|min:0', 
            'total' => 'nullable|array', 
            'total.*' => 'nullable|required_if:labor_category.*,true|numeric|min:0', 
    
            'alt_labour_id' => 'nullable|array',
            'alt_labour_id.*' => 'nullable|integer|exists:labors,id',
            
            'is_labour_delete' => 'nullable|array',
            'is_labour_delete.*' => 'nullable|integer|exists:labors,id',
    
            'alt_labor_category' => 'nullable|array', 
            'alt_labor_category.*' => 'nullable|string|max:255', 
            'alt_per_labor_amount' => 'nullable|array', 
            'alt_per_labor_amount.*' => 'nullable|required_if:alt_labor_category.*,true|numeric|min:0', 
            'alt_number_of_labors' => 'nullable|array', 
            'alt_number_of_labors.*' => 'nullable|required_if:alt_labor_category.*,true|numeric|min:0', 
            'alt_total' => 'nullable|array', 
            'alt_total.*' => 'nullable|required_if:alt_labor_category.*,true|numeric|min:0', 
        ]);
    
        try {
            DB::beginTransaction();
    
            // Process new labor entries
            if ($request->labor_category) {
                foreach ($request->labor_category as $index => $labor_category) {
                    $laborCategory = LaborCategory::firstOrCreate(['name' => ucfirst($labor_category)]);
                    Labour::create([
                        'order_id' => $decodedOrderId,
                        'labor_category_id' => $laborCategory->id,
                        'number_of_labors' => $request->number_of_labors[$index],
                        'per_labor_amount' => $request->per_labor_amount[$index],
                        'total_amount' => $request->total[$index],
                        'date' => $request->date,
                    ]);
                }
            }
    
            // Process updates or deletions for existing labor entries
            if ($request->alt_labour_id) {
                foreach ($request->alt_labour_id as $index => $already_labour_id) {
                    $labor = Labour::find($already_labour_id);
                    if ($labor) {
                        if (isset($request->is_labour_delete) && in_array($already_labour_id, $request->is_labour_delete)) {
                            $labor->delete();
                        } else {
                            $laborCategory = LaborCategory::firstOrCreate(['name' => ucfirst($request->alt_labor_category[$index])]);
                            $labor->update([
                                'order_id' => $decodedOrderId,
                                'labor_category_id' => $laborCategory->id,
                                'number_of_labors' => $request->alt_number_of_labors[$index],
                                'per_labor_amount' => $request->alt_per_labor_amount[$index],
                                'total_amount' => $request->alt_total[$index],
                                'date' => $request->date,
                            ]);
                        }
                    }
                }
            }
    
            DB::commit();
            return redirect()->route( Auth::user()->role .'.view.order',['encodedId' => $encodedOrderId])->with('message', 'Labor details saved successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::create([
                'message' => 'An error occurred while saving labor details.',
                'level' => 'error',
                'type' => 'labor',
                'ip_address' => $request->ip(),
                'context' => 'web',
                'source' => 'labor_form',
                'extra_info' => json_encode(['user_agent' => $request->header('User-Agent'),'error_message' => $e->getMessage()])
            ]);
            return back()->with('error', 'An error occurred while saving labor details.');
        }
    }
    
}
