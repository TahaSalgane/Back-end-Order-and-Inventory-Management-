<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
// use App\Models\image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

// use Laravel\Passport\Passport;
// use Laravel\Sanctum\HasApiTokens;
class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        try {
            $credentials = ['email' => $request->email, 'password' => $request->password];
    
            if (Auth()->attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('app')->accessToken;
                // $profilePicture = $user->image ? Storage::url($user->image) : null;
                $profilePicture = $user->image()->where([
                    'imageable_id' => $user->id
                ])->select("path")->first();

                return response()->json([
                    '_id' => $user->id,
                    'role' => $user->role,
                    'username' => $user->name,
                    'token' => $token,
                    'profile_image' =>$profilePicture
                ]);
            } else {
                return response()->json(['error' => 'Incorrect Email or Password!'], 401);
            }
        } catch (Exception $ex) {
            return response([
                'message' => $ex->getMessage()
            ], 400);
        }
    }
    

    public function Register(Request $request){
        try{
            $user=User::create([
                'name'=>$request->name,
                'role' => $request->role ,
                'email'=>$request->email,
                'password'=> Hash::make($request->password)
            ]);
            $token=$user->createToken('app')->accessToken;
            return response([
                'message'=>'registration succefull',
                'token'=>$token,
                'user'=>$user
            ],200);
        }
        catch(Exception $ex){
            return response([
                'message'=> $ex->getMessage()
            ],400);
        }

    }
}
