<?php

namespace App\Http\Controllers;

use App\Models\transOrder;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function report(Request $request)
    {
        try {
            $data = transOrder::whereBetween('created_at', [$request->start_date, $request->end_date])->with('master_menu', 'trans_order.master_customer')->get();
            return response()->json(['status'=>true,'data'=>$data, "message" => "Success" ]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>null,'message'=>$ex->getMessage()]);
        }
    }
}
