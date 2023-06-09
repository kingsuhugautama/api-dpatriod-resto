<?php

namespace App\Http\Controllers;

use App\Models\MasterCounter;
use App\Models\TransInvoice;
use App\Models\TransInvoiceNotif;
use App\Models\transOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentGatewayController extends Controller
{
    //
    
    public function registrasi(Request $request){
        
        try{
        
            $order = transOrder::where('uuid',$request->uuid)
            ->with('transOrderDetail.master_menu','master_customer')->first();
            
            $timeStamp = date('YmdHIs');
            $iMid = "IONPAYTEST";
            $merchantKey = "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A==";
            $reffno = $order->nomor_order;
            $amount = $order->total_price;
            $merchantData = $timeStamp.$iMid.$reffno.$amount.$merchantKey;
            $merTok = hash('sha256',$merchantData);
            // dd($order->toArray());
            $item = [];
            foreach($order->transOrderDetail as $detail){
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
            
            $cart =[
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
                "vacctValidDt" => date('Ymd',$date), 
                "vacctValidTm" => date('His'), 
                "description" => "Pesanan Makanan Dan Minuman", 
                "billingNm" => $order->master_customer->name_customer, 
                "billingPhone" => $order->master_customer->phone_customer, 
                "billingEmail" => $order->master_customer->email_customer, 
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
            ])->post('https://dev.nicepay.co.id/nicepay/redirect/v2/registration',$body);
            
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
                "paymentURL"=>$result->paymentURL.'?tXid='.$result->tXid,
            ];

            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }
    
    public function notification(Request $request){
        try{
            $notif = new TransInvoiceNotif();
            $notif->uuid = Str::uuid();
            $notif->referenceNo =$request->referenceNo;
            $notif->tXid =$request->tXid;
            $notif->payMethod = $request->payMethod;
            $notif->body =json_encode($request->all());
            $notif->save();
            
            return response()->json(['status'=>true,'data'=>$notif]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }
    
    public function callBackUrl(Request $request){
        dd($request->all());
    }
}