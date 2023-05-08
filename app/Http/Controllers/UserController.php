<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function user(){
        return Auth::user();
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
    
        // Update name, email, and password
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
    
        // Handle profile image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
    
            // Delete previous image if exists
            if ($user->image && Storage::exists($user->image)) {
                Storage::delete($user->image);
            }
    
            // Store new image
            $imagePath = $image->store('profile-images', 'public');
            $user->image = $imagePath;
        }
    
        $user->save();
    
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
    
}
