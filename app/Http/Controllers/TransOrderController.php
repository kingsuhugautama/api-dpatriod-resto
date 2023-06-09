<?php

namespace App\Http\Controllers;

use App\Models\MasterCounter;
use App\Models\transOrder;
use App\Models\transOrderDetail;
use App\Models\masterTypePayment;
use App\Models\masterMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
    // public function order(Request $request)
    // {
    //     DB::beginTransaction();
    //     try{   
    //         $order = new transOrder;
    //         $order->uuid = Str::uuid();
    //         $order->id_customer = $request->input('id_customer');
    //         $order->total_qty = $request->input('total_qty');
    //         $order->total_price = $request->input('total_price');
    //         $order->name_user = $request->input('name_user');
    //         $order->id_type_payment = $request->input('id_type_payment');
    //         $order->price_user = $request->input('price_user');
    //         $order->return_price_user = $request->input('return_price_user');
    //         $order->discount = $request->input('discount');
    //         $order->is_paid = $request->input('is_paid');
            
    //         $data_order = $request->input('data_order');


    //         foreach ($data_order as $item) {
    //             $menuTersedia = MasterMenu::where('id_menu', $item['id_menu'])->first();
    //             if($menuTersedia){
    //                 if( $menuTersedia->stok != NULL || $item['qty'] <= $menuTersedia->stok){
    //                     transOrderDetail::create([
    //                         'id_order' => $order->id_order,
    //                         'id_menu' => $item['id_menu'],
    //                         'qty' => $item['qty'],
    //                         'total_price' => $item['total_price'],
    //                         'note' => $item['note'],
    //                         'status' => $item['status'],
    //                         'is_paid' => $request->input('is_paid')
    //                     ]);
    //                 }else{
    //                     $isValid = false;
    //                 }
    //             }
    //         }
    //         if($isValid){
    //             $order->save();
    //         }else{
    //             echo('Stok tidak mencukupi');
    //         }
    //         DB::commit();
    //         return response()->json(['status'=>true,'data'=>$order]);
    //     } catch (\Exception $ex) {
    //         DB::rollback();
    //         return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
    //     }
    // }
    
    public function no_order(){
        $update_master_counter = MasterCounter::where('id',2)->where('keterangan','')->lockForUpdate()->first();
        $update_master_counter->urut    = $update_master_counter->urut+1;
        $update_master_counter->tanggal = date('Y-m-d');
        $update_master_counter->save();
        return $update_master_counter->prefix.sprintf('%08s', $update_master_counter->urut);
    }
    
    public function order(Request $request)
    {
        DB::beginTransaction();
        try{   

            $dataMenuNull  = [];

            // checking qty menu
            foreach ($request->input('data_order') as $item) {
                $menu = MasterMenu::find($item['id_menu']);
                if($menu->stok == NULL ||$menu->stok < $item['qty']){
                    $dataMenuNull[] = $menu;
                }
            }

            if(count($dataMenuNull) > 0){
                return response()->json(['status'=>false,'data'=>$dataMenuNull,'message'=>"Beberapa Stock Menu Habis" ]);
            }
            else{

            $order = new transOrder;
            $order->uuid = Str::uuid();
            $order->id_customer = $request->input('id_customer');
            $order->nomor_order = $this->no_order();
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
                $trans = MasterMenu::find($item['id_menu']);
                transOrderDetail::create([
                    'id_order' => $order->id_order,
                    'id_menu' => $item['id_menu'],
                    'qty' => $item['qty'],
                    'total_price' => $item['total_price'],
                    'note' => $item['note'],
                    'status' => $item['status'],
                    'is_paid' => $request->input('is_paid')
                ]);
                $trans->stok -= $item['qty'];
                $trans->save();
            }
            DB::commit();
            return response()->json(['status'=>true,'data'=>$order]);
            }
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
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            $data = transOrder::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->with([
                'master_type_payment',
                'transOrderDetail.master_customer', 
                'transOrderDetail.master_menu',
                ])->where('is_paid', true)->get()->toArray();
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
            $totalOrderedMenu = 0;
            $totalPayment = [];
            $totalMenu = [];
            foreach($data as $order){
                foreach ($order->transOrderDetail as $orderDetail) {
                    $namaMenu = $orderDetail->master_menu->name;
                    if(!isset($totalMenu[$namaMenu])){
                        $totalMenu[$namaMenu] = 1;
                    }else{
                        $totalMenu[$namaMenu]++;
                    }
                    $totalOrderedMenu += $orderDetail->qty;
                }

                //untuk menghitung total pendapatan
                $totalRevenue += $order->total_price;
            }
            $hasilTotalMenu=[];
            foreach ($totalMenu as $totalMenu) {
                $menuId = masterMenu::where('name', $totalMenu)->first();
                if ($menuId) {
                    $totalQty = transOrderDetail::whereHas('trans_order', function ($query) use ($request) {
                        $query->whereDate('created_at', $request->today)
                            ->where('is_paid', true);
                    })
                    ->where('id_menu', $menuId->id_menu)
                    ->sum('qty');
                    
                    $hasilTotalMenu[] = [
                        "id_menu" => $menuId->id_menu,
                        "name" => $totalMenu,
                        "total" => $totalQty
                    ];
                    $totalOrderedMenu += $totalQty;
                }
            }

            $totalPayment = transOrder::select('id_type_payment', DB::raw('COUNT(*) as total')) //mengambil id_type_payment pada tabel trans_order dan menjumlahkan total munculnya id_type_payment
            ->where('is_paid', true)
            ->whereDate('created_at', $request->today)
            ->groupBy('id_type_payment') //ORM untuk mengelompokan perhitungan sesuai id yang dipilih
            ->get();
            $hasilTotalBayar=[];
            foreach ($totalPayment as $totalPayment) {
                $paymentType = masterTypePayment::find($totalPayment->id_type_payment); //mengambil data type payment sesuai id
                if ($paymentType !== null) {
                    $hasilTotalBayar[] = [
                        "id_type_payment" => $totalPayment->id_type_payment,
                        "name_payment" => $paymentType->name_payment,
                        "total" => $totalPayment->total
                    ];
                }   
            }

            $result = [
                "total_pendapatan" => $totalRevenue,
                "total_jenis_pembayaran" => $hasilTotalBayar,
                "total_menu" => $hasilTotalMenu,
                "history_order" => $data,
            ];
            return response()->json(['status'=>true,    'data'=>$result, "message" => "Success" ]);
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