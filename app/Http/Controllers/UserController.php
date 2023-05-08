<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function user(){
        return Auth::user();
    }
    public function updateProfile(Request $request){
        try {
            $user = User::find(Auth::user()->id);
            $request->validate([
                'name' => ['max:50'] ,
                'email' => ['email','max:256'] ,
                'password' => ['required','min:6','max:50']
            ]) ;
            // check if the password is correct
            if(Hash::check($request->password, Auth::user()->password)) {

                // check if the request has an image
                if ($request->hasFile('image')) {
                    $image = $request->file('image') ;
                    $existedImage = Auth::user()->image->first->path ;
                    // check if there is an existed image or not
                    if($existedImage){
                        // if exist => update
                        $path = $image->store('public/images');
                        $user->image()->where([
                            'imageable_id' => $user->id
                        ])->update([
                            'path' => $path
                        ]) ;
                        // delete the existed image from Storage disk
                        Storage::delete($existedImage->path) ;
                        // return response()->json([
                        //     'deleted'
                        // ]) ;
                    }else{
                        // if not => create a new one
                        $path = $image->store('public/images');
                        $user->image()->create([
                            'path' => $path ,
                            'type' => 'profile'
                        ]) ;
                        // return response()->json([
                        //     'updated'
                        // ]) ;
                    }
                    $user->update([
                        'name' => $request->name ,
                        'email' => $request->email
                    ]) ;
                    $existedImage = Auth::user()->image->first->path ;
                    $path = Storage::url($existedImage->path);
                    $url = url($path);
                    return response()->json([
                        'image_url' => $url 
                    ]) ;
                }else{
                    $user->update([
                        'name' => $request->name ,
                        'email' => $request->email
                    ]) ;
                    return response()->json([
                        'update without image'
                    ]) ;
                }

            } else {
                return response()->json(['error' => 'incorrect Password !'],401) ;
            }


        } catch (\Throwable $th) {
            return response()->json([
                // 'error' => $th->getMessage()
                'error' => $th->getMessage()
            ]) ;
        }

}
}
