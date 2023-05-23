<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orders()
    {
        $authUser = Auth::user();
        try {
            $orders = Order::with('articles')->get();
            return response()->json([
                "orders" => $orders
            ]);
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]);
        }
    }

    public function addOrder(Request $request){
        try {
            $authUser = Auth::user() ;
            // return response()->json([
            //     $authUser
            // ]) ;
            if($authUser->role == 'directeur etablissement'){ // Check if the user role is not equal to directeur etablissement => add order
                Order::create([
                    'titre' => $request->titre,
                    'articles' => $request->articles, // Assign the array directly
                    'etablissement' => $authUser->etablissement,
                    'user_id' => $authUser->id,
                ]);
                
                $orders = Order::with('articles')->get();
                return  response()->json([
                    "orders"=>$orders
                ]);
            }
            return  response()->json([
                'error' => 'User of type magasinier can not add orders !'
            ] ,400);
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function editOrder(Request $request){
        try {
            $authUser = Auth::user() ;
            if($authUser->role != 'magasinier'){
                $authUser->orders()->find($request->order_id)->update([
                    'titre' => $request->titre ,
                    'articles' => json_encode($request->articles) ,
                ]) ;
                return response()->json([
                    'message' => 'Order has been updated successfuly !'
                ]) ;
            }
            return  response()->json([
                'error' => 'User of type magasinier can not make action on orders !'
            ] ,400);
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function deleteOrder(Request $request){
        try {
            $authUser = Auth::user() ;
            if($authUser->role != 'magasinier'){
                Order::find($request->order_id)->delete() ;
                return response()->json([
                    'message' => 'order '.$request->order_id.' deleted successfuly !'
                ]) ;
            }
            return  response()->json([
                'error' => 'User of type magasinier can not make actions on orders !'
            ] ,400);
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }
}
