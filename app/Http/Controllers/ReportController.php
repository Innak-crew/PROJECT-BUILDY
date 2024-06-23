<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Orders;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{


    public function export(Request $request) 
    {


        $isAll = $request->query('is_all', false);
        $message_return_type = $request->query('message_return_type');
        $filterBy = $request->query('filter_by', 'order');
        $status = $request->query('status');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = Orders::query();

        
        if ($filterBy === 'order') {
            if ($status) {
                $query->where('status', $status);
            }
            if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            }
        }

        // Fetch data based on conditions
        $orders = $query->get();

        if($orders->isEmpty()){
            if($message_return_type == "json"){
                return response()->json(['message' => 'No data found for the selected filters'], 404);
            }else{
                return redirect()->back()->with('error', 'No data found for the selected filters');
            }
        }else{
            return Excel::download(new ReportExport($orders), 'orders.xlsx');
        }
    }

    public function report(){

    }
}
