<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\User;
use App\Notifications\userNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReclamationController extends Controller
{
    public function index()
    {
        $reclamations = Reclamation::with('order')->get();

        return response()->json(['reclamations' => $reclamations]);
    }

    public function store(Request $request)
    {
        $authUser = Auth::user() ;
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'titre' => 'required',
            'description' => 'required',
        ]);
        $reclamation = Reclamation::create([
            'order_id' => $request->order_id,
            'titre' => $request->titre,
            'description' => $request->description,
        ]);
        $users = User::where([
            ['id','<>',$authUser->id] ,
            [function($query) use ($authUser) {
                return $query
                       ->where('etablissement', $authUser->etablissement)
                       ->orWhere('etablissement','complexe')
                       ->orWhere('role','magasinier');
               }]
        ])->get() ;
        $imagePath = 'https://cdn-icons-png.flaticon.com/512/5875/5875289.png' ;
        $body = 'Vous avez une reclamation sur une commande depuis l\'etablissement ' ;
        $title = 'Une reclamation' ;
        $toPage = 'réclamation' ;
        $maker = $authUser->etablissement ;
        if($maker === null){
            $maker = $authUser->role ;
        }
        Notification::send($users,new userNotif($reclamation->id,$authUser->id,$imagePath,$body,$title,$toPage,$maker)) ;
        return response()->json(['reclamation' => $reclamation], 201);
    }

    public function destroy(Reclamation $reclamation)
    {
        $reclamation->delete();
        return response()->json(['message' => 'Reclamation deleted successfully']);
    }
}

