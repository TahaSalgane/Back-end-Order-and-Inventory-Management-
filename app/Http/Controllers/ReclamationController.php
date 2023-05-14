<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    public function addReclamation(Request $request){
        try {
            $authUser = Auth::user() ;
            if($authUser->role != 'magasinier'){ // only users of type complexe etablissement can add reclamation
                Order::find($request->order_id)->reclamation()->create([
                    'titre' => $request->titre,
                    'description' => $request->description?$request->description:null
                ]) ;
                return response()->json([
                    'reclamation added with success ! '
                ]) ;
            }
            return response()->json([
                'error' => 'magasinier can not do this action  ! '
            ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }
    public function repReclamation(Request $request){
        try {
            $authUser = Auth::user() ;
            $order = Order::find($request->order_id) ;
            if($authUser->role == 'magasinier'){
                $order->reclamation()->update([
                    'reponse' => $request->reponse ,
                    'status' => 'answered'
                ]) ;
                return response()->json([
                    'message' => 'Your answer has been sent !'
                ]) ;
            }
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }

    }

    public function editReclamation(Request $request){
        try {
            $authUser = Auth::user() ;
            $order = Order::find($request->order_id)->reclamation() ;
            if ($authUser->role !=  'magasinier') {
                if ($order->first()->status == 'answered') {
                    return response()->json([
                        'error' => 'You can not edit your reclamation , already answered !'
                    ]) ;
                }
                $order->update([
                    'titre' => $request->titre ,
                    'description' => $request->description,
                ]) ;
                return response()->json([
                    'message' => 'reclamation updated seuccessfuly !'
                ]) ;
            }
            return response()->json([
                'error'=> ' Magasinier can not do this aciton '
            ],400) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function deleteReclamation(Request $request){
        try {
            $authUser = Auth::user() ;
            $order = Order::find($request->order_id)->reclamation() ;
            if ($authUser->role !=  'magasinier') {
                $order->delete() ;
                return response()->json([
                    'message' => 'reclamation deleted seuccessfuly !'
                ]) ;
            }
            return response()->json([
                'error'=> ' Magasinier can not do this aciton '
            ],400) ;
        } catch (Exception $exp) {
            return response()->json([
                $exp->getMessage()
            ]) ;
        }
    }
}
