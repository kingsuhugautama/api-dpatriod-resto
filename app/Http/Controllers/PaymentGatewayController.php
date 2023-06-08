<?php

namespace App\Http\Controllers;

use App\Models\TransInvoice;
use App\Models\TransInvoiceNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentGatewayController extends Controller
{
    //
    public function registrasi(){
        
        try{
            $timeStamp = date('YmdHIs');
            $iMid = "IONPAYTEST";
            $merchantKey = "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A==";
            $reffno = "ord".$timeStamp;
            $amount = 15000;
            $merchantData = $timeStamp.$iMid.$reffno.$amount.$merchantKey;
            $merTok = hash('sha256',$merchantData);
            
            $cart =[
                "count" => "1", 
                "item" => [
                    [
                        "goods_id" => "BB12345678", 
                        "goods_detail" => "BB123456", 
                        "goods_name" => "iPhone5S", 
                        "goods_amt" => $amount, 
                        "goods_type" => "Smartphone", 
                        "goods_url" => "http://merchant.com/cellphones/iphone5s_64g", 
                        "goods_quantity" => 1 
                    ] 
                ] 
            ];
            
            $body = [
                "timeStamp" => $timeStamp, 
                "iMid" => $iMid, 
                "payMethod" => "00", 
                "bankCd" => "CENA", 
                "currency" => "IDR", 
                "amt" => $amount, 
                "referenceNo" => $reffno, 
                "merchantToken" => $merTok, 
                "callBackUrl" => "https://www.nicepay.co.id/IONPAY_CLIENT/paymentResult.jsp", 
                "dbProcessUrl" => "https://webhook.site/e15ef201-98a9-428c-85d4-a0c6458939c3", 
                "goodsNm" => "Goods", 
                "mitraCd" => "", 
                "vacctValidDt" => "20230610", 
                "vacctValidTm" => "235959", 
                "description" => "Testing API by Sibedul", 
                "billingNm" => "Hantu Kesorean", 
                "billingPhone" => "081288998899", 
                "billingEmail" => "abdul@example.com", 
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
            
            if(!$result->resultCd=='0000'){
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