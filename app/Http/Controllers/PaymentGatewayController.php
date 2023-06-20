<?php

namespace App\Http\Controllers;

use App\Helpers\SendOneSignal;
use App\Models\MasterCounter;
use App\Models\TransInvoice;
use App\Models\TransInvoiceNotif;
use App\Models\TransInvoiceNotifErr;
use App\Models\transOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;

class PaymentGatewayController extends Controller
{
    //

    public function registrasi(Request $request)
    {
        DB::beginTransaction();
        try {

            $order = transOrder::where('uuid', $request->uuid)
                ->with('transOrderDetail.master_menu', 'user')->first();
            // dd($order);
            $timeStamp = date('YmdHIs');
            $iMid = "IONPAYTEST";
            $merchantKey = "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A==";
            $reffno = $order->nomor_order;
            $amount = $order->total_price;
            $merchantData = $timeStamp . $iMid . $reffno . $amount . $merchantKey;
            $merTok = hash('sha256', $merchantData);
            // dd($order->toArray());
            $item = [];
            foreach ($order->transOrderDetail as $detail) {
                $item[] = [
                    "goods_id" => $detail->id_menu,
                    "goods_detail" => $detail->master_menu->name,
                    "goods_name" => $detail->master_menu->name,
                    "goods_amt" => $detail->total_price,
                    "goods_type" => $detail->master_menu->name,
                    "goods_url" => $detail->master_menu->url_image,
                    "goods_quantity" => 1
                ];
            }

            $cart = [
                "count" => count($order->transOrderDetail),
                "item" => $item
            ];
            $date = strtotime("+1 day");
            $body = [
                "timeStamp" => $timeStamp,
                "iMid" => $iMid,
                "payMethod" => "00",
                "bankCd" => "CENA",
                "currency" => "IDR",
                "amt" => $amount,
                "referenceNo" => $reffno,
                "merchantToken" => $merTok,
                "callBackUrl" => "https://dpatriotcafe.com/api/payment/callBackUrl",
                "dbProcessUrl" => "https://dpatriotcafe.com/api/payment/notification",
                "goodsNm" => "makanan/minuman",
                "mitraCd" => "",
                "vacctValidDt" => date('Ymd', $date),
                "vacctValidTm" => date('His'),
                "description" => "Pesanan Makanan Dan Minuman",
                "billingNm" => $order->user->name,
                "billingPhone" => $order->user->phone,
                "billingEmail" => $order->user->email,
                "billingAddr" => "",
                "billingCity" => "semarang",
                "billingState" => "jawa tengah",
                "billingPostCd" => "50198",
                "billingCountry" => "indonesia",
                "userIP" => "127.0.0.1",
                "cartData" => json_encode($cart),
                "deliveryNm" => "",
                "deliveryPhone" => "",
                "deliveryAddr" => "",
                "deliveryCity" => "",
                "deliveryState" => "",
                "deliveryPostCd" => "",
                "deliveryCountry" => "",
                "vat" => "",
                "fee" => "",
                "notaxAmt" => "",
                "reqDt" => "",
                "reqTm" => "",
                "reqDomain" => "",
                "reqServerIP" => "",
                "reqClientVer" => "",
                "userSessionID" => "",
                "userAgent" => "",
                "userLanguage" => ""
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://dev.nicepay.co.id/nicepay/redirect/v2/registration', $body);

            $result = $response->object();

            if ($result->resultCd != '0000') {
                throw new \Exception('error payment gateway ' . $result->resultMsg);
            }

            $notif = new TransInvoice;
            $notif->uuid = Str::uuid();
            $notif->referenceNo = $result->referenceNo;
            $notif->tXid = $result->tXid;
            $notif->payMethod = '';
            $notif->body = json_encode($result);
            $notif->save();

            $data = [
                "result" => $result,
                "paymentURL" => $result->paymentURL . '?tXid=' . $result->tXid,
            ];
            DB::commit();
            
            return response()->json(['status' => true, 'data' => $data]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['status' => false, 'data' => [], 'message' => $ex->getMessage()]);
        }
    }

    public function qris(Request $request)
    {

        try {

            $order = transOrder::where('uuid', $request->uuid)
                ->with('transOrderDetail.master_menu', 'master_customer')->first();

            $timeStamp = date('YmdHIs');
            $iMid = "IONPAYTEST";
            $merchantKey = "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A==";
            $reffno = $order->nomor_order;
            $amount = $order->total_price;
            $merchantData = $timeStamp . $iMid . $reffno . $amount . $merchantKey;
            $merTok = hash('sha256', $merchantData);
            // dd($order->toArray());
            $item = [];
            foreach ($order->transOrderDetail as $detail) {
                $item[] = [
                    "goods_id" => $detail->id_menu,
                    "goods_detail" => $detail->master_menu->name,
                    "goods_name" => $detail->master_menu->name,
                    "goods_amt" => $detail->total_price,
                    "goods_type" => $detail->master_menu->name,
                    "goods_url" => $detail->master_menu->url_image,
                    "goods_quantity" => 1
                ];
            }

            $cart = [
                "count" => count($order->transOrderDetail),
                "item" => $item
            ];

            $body = [
                "timeStamp" => $timeStamp,
                "iMid" => $iMid,
                "payMethod" => "08",
                "currency" => "IDR",
                "amt" => $amount,
                "referenceNo" => $reffno,
                "goodsNm" => "Pesanan Makanan Dan Minuman",
                "billingNm" => $order->user->name,
                "billingPhone" => $order->user->phone,
                "billingEmail" => $order->user->email,
                "billingAddr" => "",
                "billingCity" => "semarang",
                "billingState" => "jawa tengah",
                "billingPostCd" => "50198",
                "billingCountry" => "indonesia",
                "dbProcessUrl" => "https://dpatriotcafe.com/api/payment/notification",
                "merchantToken" => $merTok,
                "paymentExpDt" => "",
                "paymentExpTm" => "",
                "userIP" => "127.0.0.1",
                "cartData" => json_encode($cart),
                "mitraCd" => "QSHP",
                "shopId" => "NICEPAY"
            ];
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://dev.nicepay.co.id/nicepay/direct/v2/registration',$body);
            
            $result = $response->object();
            
            if($result->resultCd!='0000'){
                throw new \Exception('error payment gateway '.$result->resultMsg);
            }
                        
            $notif = new TransInvoice;
            $notif->uuid = Str::uuid();
            $notif->referenceNo =$result->referenceNo;
            $notif->tXid =$result->tXid;
            $notif->payMethod = '';
            $notif->body =json_encode($result);
            $notif->save();
            
            $data = [
                "result" =>$result,
                "QRIS"=>$result->qrContent,
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://dev.nicepay.co.id/nicepay/direct/v2/registration', $body);

            $result = $response->object();

            if ($result->resultCd != '0000') {
                throw new \Exception('error payment gateway ' . $result->resultMsg);
            }

            $notif = new TransInvoice;
            $notif->uuid = Str::uuid();
            $notif->referenceNo = $result->referenceNo;
            $notif->tXid = $result->tXid;
            $notif->payMethod = '';
            $notif->body = json_encode($result);
            $notif->save();

            $data = [
                "result" => $result,
                "QRIS" => $result->qrContent,
            ];

            return response()->json(['status' => true, 'data' => $data]);
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'data' => [], 'message' => $ex->getMessage()]);
        }
    }

    public function notification(Request $request)
    {
            DB::beginTransaction();
        try {
            $notif = new TransInvoiceNotif();
            $notif->uuid = Str::uuid();
            $notif->referenceNo = $request->referenceNo;
            $notif->tXid = $request->tXid;
            $notif->payMethod = $request->payMethod;
            $notif->body = json_encode($request->all());
            $notif->save();
            if($request->status==0){
                transOrder::where('nomor_order',$request->referenceNo)->update([
                    'is_paid' => true
                ]);
            }
            //======= socket
            $status = ($request->status==0)?true:false;
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'my-username' => 'dpatriot-resto',
                    'my-password' => 'dimas123'
                ])->post('http://128.199.75.235:3000/notifikasi_payment', [
                    'nomor_order'   => $request->referenceNo,
                    'status'        => $status
                ]);
                $result = $response->object();
            } catch(\Exception $ex){
                $notif_err = new TransInvoiceNotifErr();
                $notif_err->message = 'error socket';
                $notif_err->body = json_encode([
                    'nomor_order'   => $request->referenceNo,
                    'status'        => $status
                ]);
                $notif_err->save();
            }
            //======== notification
            if($request->status==0){
                try {
                    $order = transOrder::where('nomor_order',$request->referenceNo)->first();
                    $payerId = (string)$order->id_customer;
                    
                    $data = SendOneSignal::SendByExternalId([$payerId],'Pesanan Sudah Terbayar','pesanan anda dengan nomor order '.$request->referenceNo.' telah terbayar');
                    $notif_err = new TransInvoiceNotifErr();
                    $notif_err->message = 'log one signal';
                    $notif_err->body = json_encode($data);
                    $notif_err->save();
                    
                } catch(\Exception $ex){
                
                    $notif_err = new TransInvoiceNotifErr();
                    $notif_err->message = 'error one signal';
                    $notif_err->body = json_encode($request->all());
                    $notif_err->save();
                    
                }
            }
            DB::commit();
            
            return response()->json(['status' => true, 'data' => $notif]);
        } catch (\Exception $ex) {
            DB::rollback();
            $notif_err = new TransInvoiceNotifErr();
            $notif_err->message = $ex->getMessage();
            $notif_err->body = json_encode($request->all());
            $notif_err->save();
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }

    public function callBackUrl(Request $request)
    {
        $data = $request->all();
        $data['amountCurrency'] = number_format($data['amount'], 0, ',', '.');

        return view('paymentGateway/callbackUrl', ['data' => $data]);
    }
    
    public function socket()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'my-username' => 'dpatriot-resto',
            'my-password' => 'dimas123'
        ])->post('http://128.199.75.235:3000/notifikasi_payment', [
            'nomor_order'   => '123243',
            'status'        => true
        ]);
        $result = $response->object();
        dd($result);
    }
    
    public function onesignal()
    {
        $data = SendOneSignal::SendByExternalId(['1'],'testing','keterangan coba testing');
        dd($data);
    }
}
