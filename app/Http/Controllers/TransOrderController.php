<?php

namespace App\Http\Controllers;

use App\Models\transOrder;
use App\Models\transOrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = transOrder::with('transOrderDetail.master_menu')
            ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
            ->get();
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function order(Request $request)
    {
        DB::beginTransaction();
        try{   
            $order = new transOrder;
            $order->uuid = Str::uuid();
            $order->id_customer = $request->input('id_customer');
            $order->total_qty = $request->input('total_qty');
            $order->total_price = $request->input('total_price');
            $order->name_user = $request->input('name_user');
            $order->id_type_payment = $request->input('id_type_payment');
            $order->price_user = $request->input('price_user');
            $order->return_price_user = $request->input('return_price_user');
            $order->discount = $request->input('discount');
            $order->is_paid = false;

            $order->save();

            $data_order = $request->input('data_order');

            foreach ($data_order as $item) {
                transOrderDetail::create([
                    'id_order' => $order->id_order,
                    'id_menu' => $item['id_menu'],
                    'qty' => $item['qty'],
                    'total_price' => $item['total_price'],
                    'note' => $item['note'],
                    'status' => $item['status'],
                    'is_paid' => $request->input('is_paid')
                ]);
            }
            DB::commit();
            return response()->json(['status'=>true,'data'=>$order]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function update(Request $request, $id)
    {
        try{
            $detail = transOrderDetail::find($id);
            $detail->update([
                'status' => $request->status
            ]);
            return response()->json(['status'=>true,'data'=>$detail]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function detail(Request $request)
    {
        // try{
        //     $detail = transOrderDetail::where('status', $status)
        //     ->with('master_menu','trans_order.master_customer')
        //     ->get();
        //     return response()->json(['status'=>true,'data'=>$detail]);
        // } catch (\Exception $ex) {
        //     return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        // }
        try{
            $detail = [];
            for( $i = 1; $i< 7; $i++ ){
                $a = [
                    "id_status" => $i,
                    "data_order" => transOrderDetail::where('status', $i)
                    ->with('master_menu', 'trans_order.master_customer')
                    ->whereDate('created_at', now())
                    ->get()
                    ];
                $detail[] = $a;
            }
            return response()->json(['status'=>true,'data'=>$detail]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(transOrder $transOrder)
    {
        //
    }
}
