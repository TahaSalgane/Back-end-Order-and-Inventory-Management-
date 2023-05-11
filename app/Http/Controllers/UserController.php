<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $authenticatedUser = Auth::user();
        $allowedRoles = [$authenticatedUser->role];

        if ($authenticatedUser->role === 'directeur complexe') {
            $allowedRoles[] = 'directeur etablissement';
            $allowedRoles[] = 'magasinier';
        }

        $users = User::whereIn('role', $allowedRoles)->get();

        return response()->json($users);
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $allowedRoles = [$user->role];
        if ($user->role === 'directeur complexe') {
            $allowedRoles[] = 'directeur etablissement';
            $allowedRoles[] = 'magasinier';
        }
        $this->validate($request, [
            'role' => 'required|in:' . implode(',', $allowedRoles),
        ]);
        $newUser = new User();
        $newUser->username = $request->input('username');
        $newUser->email = $request->input('email');
        $newUser->password = Hash::make($request->password);
        $newUser->role = $request->input('role');
        $newUser->save();

        return response()->json($newUser);
    }
    public function update(Request $request, User $user)
    {
        $authenticatedUser = Auth::user();
        $allowedRoles = [$authenticatedUser->role];
        if ($authenticatedUser->role === 'directeur complexe') {
            $allowedRoles[] = 'directeur etablissement';
            $allowedRoles[] = 'magasinier';
        }
        $this->validate($request, [
            'role' => 'required|in:' . implode(',', $allowedRoles),
        ]);
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->password);
        $user->role = $request->input('role');

        $user->save();

        return response()->json($user);
    }
    public function destroy(User $user)
    {
        $authenticatedUser = Auth::user();
    
        if ($authenticatedUser->role === 'magasinier' && $user->role !== 'magasinier') {
            return response()->json(['message' => 'You can only delete magasinier users.'], 403);
        }
    
        if ($authenticatedUser->role === 'directeur etablissement' && $user->role !== 'directeur etablissement') {
            return response()->json(['message' => 'You can only delete directeur etablissement users.'], 403);
        }
    
        if ($authenticatedUser->role === 'directeur complexe' && $user->role !== 'directeur complexe' && $user->role !== 'directeur etablissement' && $user->role !== 'magasinier') {
            return response()->json(['message' => 'You can only delete directeur complexe, directeur etablissement, and magasinier users.'], 403);
        }
    
        $user->delete();
    
        return response()->json(['message' => 'User deleted successfully.']);
    }
    public function user(){
        return Auth::user();
    }
    public function updateProfile(Request $request){
        try {
            $user = User::find(Auth::user()->id);
            // $request->validate([
            //     'name' => ['max:50'] ,
            //     'email' => ['email','max:256'] ,
            //     'password' => ['required','min:5','max:50']
            // ]) ;
            // check if the password is correct
                // check if the request has an image
                if ($request->hasFile('image')) {
                    $image = $request->file('image') ;
                    $existedImage = Auth::user()->image->first() ;
                    // check if there is an existed image or not
                    if($existedImage){
                        // if exist => update
                        $path = $image->store('public/images');
                        $url = Storage::url($path);
                        // 
                        $PathToDelete = str_replace("/storage", "public", $existedImage->path);

                        $user->image()->where([
                            'imageable_id' => $user->id
                        ])->update([
                            'path' => $url
                        ]) ;
                        // delete the existed image from Storage disk
                        Storage::delete($PathToDelete) ;
                        return response()->json([
                            'profile_image'=> $url
                        ]) ;
                    }else{
                        // if not => create a new one
                        $path = $image->store('public/images');
                        $url = Storage::url($path);
                        $user->image()->create([
                            'path' => $url ,
                            'type' => 'profile'
                        ]) ;
                        // return response()->json([
                        //     'updated'
                        // ]) ;
                    }
                    $existedImage = Auth::user()->image->first()->path ;
                    $path = Storage::url($existedImage->path);
                    $url = url($path);
                    return response()->json([
                        'image_url' => $url
                    ]) ;
                }else{
                    return response()->json([
                        'update without image'
                    ]) ;
                }



        } catch (\Throwable $th) {
            return response()->json([
                // 'error' => $th->getMessage()
                'error' => $th->getMessage()
            ]) ;
        }

}
}