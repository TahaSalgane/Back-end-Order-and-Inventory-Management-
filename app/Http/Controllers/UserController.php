<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function user(){
        return Auth::user();
    }
    public function updateProfile(){
        return response()->json([
            'message' => 'hello from edit profile page !'
        ]);
    }
}
