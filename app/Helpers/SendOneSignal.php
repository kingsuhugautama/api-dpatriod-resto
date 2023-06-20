<?php


namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class SendOneSignal
{
    public static function SendByExternalId($externalId=[],$judul='',$content='')
    {
        $content = array(
            "en" => $content
        );

        $heading = array(
            "en" => $judul
        );
        
        $fields = array(
            'app_id' => "3d9c396d-b9b1-45c4-9cb8-e04ee673f4da",
            'include_external_user_ids' => $externalId,
            'data' => array("foo" => "bar"),
            'contents' => $content,
			'headings' => $heading
        );
        //   dd($fields);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ZDk3ZTk2MTMtYjJlZS00NmRjLTk0OTItNzUwYWQ2NzVkMWYz',
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', $fields);
        
        return $response->object();
    }
}