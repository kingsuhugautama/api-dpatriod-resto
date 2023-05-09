<?php

namespace App\Http\Controllers;

use App\Models\transOrder;
use App\Models\transOrderDetail;
use App\Models\masterTypePayment;
use App\Models\masterMenu;
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
            $order->is_paid = $request->input('is_paid');

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

    public function update_paid(Request $request, $id_order)
    {
        try{
            $order = transOrder::find($id_order);
            $order->update([
                'is_paid' => true,
                'price_user' => $request->price_user,
                'return_price_user' => $request->return_price_user,
                $detail = $request->detail
            ]);
            $detail = transOrderDetail::where('id_order', $id_order)->get();
            foreach($detail as $detail){
                $detail->update([
                    'is_paid' => true,
                    'status' => 2
                ]);
            }
            return response()->json(['status'=>true,'data'=>$detail]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>null,'message'=>$ex->getMessage()]);
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

    public function history($id_customer)
    {
        try {
            $data = transOrder::where('id_customer', $id_customer)->with('transOrderDetail.master_menu')->get();
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>null,'message'=>$ex->getMessage()]);
        }
    }

    public function get_detail_by_id_order($id_order)
    {
        try {
            $data = transOrder::where('id_order', $id_order)->with('transOrderDetail.master_menu')->first();
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>null,'message'=>$ex->getMessage()]);
        }
    }

    public function report(Request $request)
    {
        try {
            $data = transOrder::whereBetween('created_at', [$request->start_date, $request->end_date])
            ->with([
                'master_type_payment',
                'transOrderDetail.master_customer', 
                'transOrderDetail.master_menu',
                ])->get();
            return response()->json(['status'=>true,'data'=>$data, "message" => "Success" ]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>null,'message'=>$ex->getMessage()]);
        }
    }

    public function reportToday(Request $request)
    {
        try {
            $data = transOrder::whereDate('created_at', $request->today)->where('is_paid', true)
            ->with([
                'master_type_payment:id_type_payment,name_payment,created_at,updated_at',
                'transOrderDetail.master_customer', 
                'transOrderDetail.master_menu:id_menu,id_category,name,price',
                ])->get();
            $totalRevenue = 0;
            $totalPayment = [];
            $totalMenu = [];
            foreach($data as $order){
                $jenisBayar = $order->master_type_payment->name_payment;
                foreach ($order->transOrderDetail as $orderDetail) {
                    $namaMenu = $orderDetail->master_menu->name;
                    if(!isset($totalMenu[$namaMenu])){
                        $totalMenu[$namaMenu] = 1;
                    }else{
                        $totalMenu[$namaMenu]++;
                    }
                }
                $totalRevenue += $order->total_price;
                if (!isset($totalPayment[$jenisBayar])) {
                    $totalPayment[$jenisBayar] = 1;
                } else {
                    $totalPayment[$jenisBayar]++;
                }
            }
            foreach ($totalMenu as $totalMenu => $count) {
                $menuId = masterMenu::where('name', $totalMenu)->first();
                if ($totalMenu) {
                    $hasilTotalMenu[] = [
                        "id_menu" => $menuId->id_menu,
                        "name" => $namaMenu,
                        "total" => $count
                    ];
                }
            }
            foreach ($totalPayment as $totalPayment => $count) {
                $paymentType = masterTypePayment::where('name_payment', $totalPayment)->first();
                if ($totalPayment) {
                    $hasilTotalBayar[] = [
                        "id_type_payment" => $paymentType->id_type_payment,
                        "name_payment" => $totalPayment,
                        "total" => $count
                    ];
                }
            }

            $result = [
                "total_pendapatan" => $totalRevenue,
                "total_jenis_pembayaran" => $hasilTotalBayar,
                "total_menu" => $hasilTotalMenu,
                "history_order" => $data,
            ];
            return response()->json(['status'=>true,'data'=>$result, "message" => "Success" ]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>null,'message'=>$ex->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(transOrder $transOrder)
    {
        
    }
}
