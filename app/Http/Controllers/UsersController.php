<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function register(Request $request){
        try{
            $token_email = Str::random(30);
            $data = request()->all();
            $data['is_active'] = 1 ;
            $data['verifikasi_email']=$token_email;
            $data['is_verifikasi_email']=0;
            $data['jenis_user']=1;
            $data['password'] = bcrypt($data['password']);
            
            $user =User::create($data);
            auth('web')->login($user);
            request()->session()->regenerate();
            $hasil =  array_merge($user->toArray(), [
                'token' => $user->createToken(config('app.name'))->plainTextToken
            ]);
            //================== Verifikasi Email
            $email = [
                'email'=> $data['email'],
                'data' => $data,
                // 'path' => "app{$ds}{$filepath}"
            ];

            $data_email = array(
                'nama' => $data['name'],
                'token' => $token_email,
                'url' => url('/').'/verifikasi/'.$token_email,
            );

            $send = Mail::send('email.verifikasi-email',$data_email, function($mail) use($email){
                $mail->from('oisgvp@gmail.com','Admin DPATRIOT RESTO');
                $mail->to($email['email'])->subject("Email Verifikasi Akun");
            });
            //==================
            return response()->json(['status'=>true,'data'=>$hasil,'send'=>$send]);
        } catch (\Exception $ex) {                    
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }
    
    public function login(Request $request){
        // $validated = _validate($request->all(), ['email' => 'required','password'=>'required']);
        try {
            $user = User::where('email', $request['email'])->first();
            if (!$user || !Hash::check($request['password'], $user->password)) {
                throw new \Exception('email and password not mach.');
            }
            if (!$user->is_active) {
                throw new \Exception('User is not active.');
            }
            auth('web')->login($user);
            request()->session()->regenerate();
            return array_merge($user->toArray(), [
                'token' => $user->createToken(config('app.name'))->plainTextToken
            ]);
        } catch (\Exception $ex) {     
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }
    
    public function logout(){
        try{
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return response()->json(['status'=>true,'data'=>$hasil]);
        } catch (\Exception $ex) {                    
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }
}