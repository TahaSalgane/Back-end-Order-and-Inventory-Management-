<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgetMail;
class ForgetController extends Controller
{
    public function ForgetPassword(ForgotRequest $request){
        $email = $request->email;
        if(User::where('email',$email)->doesntExist()){
            return response([
                'message'=>'email invalid'
            ],401);
        }
        $token = rand(10,100000);
        $user = User::where('email',$email)->first();
        $name=$user->name;
        try{
           DB::table('password_reset_tokens')->insert([
            'email'=>$email,
            'token'=>$token
           ]);
           Mail::to($email)->send(new ForgetMail($token, $name));
           return response([
            'message'=> 'Rest password mail send on your email',
            'name'=>$name
        ],200);
        }catch(Exception $ex){
            return response([
                'message'=> $ex->getMessage()
            ],400);
        }
    }
}
