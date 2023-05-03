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
            $emailExist = User::where('email', $request->input('email'))->exists();
            if($emailExist){
                return response()->json(['status'=>false,'data'=>null,'message'=>'Email Telah Terdaftar']);
            }
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
            return response()->json(['status'=>false,'data'=>null,'message'=>$ex->getMessage()]);
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
            if($user->is_verifikasi_email==0){
                throw new \Exception('Email is not verified.');
            }
            auth('web')->login($user);
            request()->session()->regenerate();
            $success = array_merge($user->toArray(), [
                'token' => $user->createToken(config('app.name'))->plainTextToken
            ]);
            return response()->json(['status'=>true,'data'=>$success]);
        } catch (\Exception $ex) {     
            return response()->json(['status'=>false,'data'=>null,'message'=>$ex->getMessage()]);
        }
    }
    
    public function logout(){
        try{
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return response()->json(['status'=>true,'data'=>[]]);
        } catch (\Exception $ex) {                    
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }
    public function verifikasiEmail($token_email)
    {
        $user = User::where('verifikasi_email', $token_email)->first();
        $user->is_verifikasi_email = 1;
        $user->save();
        auth('web')->login($user);
        request()->session()->regenerate();
        return view('email.verifikasi');
    }
}