<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\article;
use App\Models\User;
use App\Notifications\userNotif;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification ;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auth = Auth::user();

        if ($auth->role === 'magasinier' || $auth->role === 'directeur complexe') {
            $orders = Order::with('articles')->get();
        } elseif ($auth->role === 'directeur etablissement') {
            $orders = Order::with('articles')->where('etablissement', $auth->etablissement)->get();
        } else {
            $orders = [];
        }

        return response()->json(['orders' => $orders]);
    }
    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        try {
            $authUser = Auth::user();
            $request->validate([
                'titre' => 'required',
                'articles' => 'required|array',
                'articles.*' => 'exists:articles,id',
            ]);

            $order = Order::create([
                'titre' => $request->titre,
                'etablissement' => $authUser->etablissement,
                'user_id' => $authUser->id,
            ]);

            $articles = article::whereIn('id', $request->input('articles'))->get();
            $order->articles()->attach($articles);
            $orders = Order::with('articles')->get();

            $users = User::where([
                ['id','<>',$authUser->id] ,
                [function($query) use ($authUser) {
                    return $query
                           ->where('etablissement', $authUser->etablissement)
                           ->orWhere('role','directeur complexe')
                           ->orWhere('role','magasinier');
                   }]
            ])->get() ;
            $imagePath = 'https://static.thenounproject.com/png/1475504-200.png' ;
            $body = 'Vous avez une commande depuis letablissement ' ;
            $title = 'Une commande' ;
            $toPage = 'ordres' ;
            $maker = $authUser->etablissement ;
            Notification::send($users,new userNotif($order->id,$authUser->id,$imagePath,$body,$title,$toPage ,$maker)) ;

            return response()->json(['orders' => $orders]);
            // return response()->json(['order' => $order->load('articles')], 201);
        }
        catch (Exception $exp) {
            return response()->json([
                $exp->getMessage()
            ]) ;
        }
    }

    public function update(Request $request, Order $order)
    {
        $authUser = Auth::user();
        $order->status = $request->input('status');
        $order->save();
        $users = User::where([
            ['id','<>',$authUser->id] ,
            [function($query) use ($order) {
                return $query
                       ->where('etablissement', $order->etablissement)
                       ->orWhere('role','directeur complexe') ;
               }]
        ])->get() ;
        if($request->status == 'inProgress'){
            $imagePath = 'https://cdn.onlinewebfonts.com/svg/img_365328.png' ;
            $body = 'Votre commande est en cours de traitement' ;
            $title = 'Commande "'.$order->titre.' "' ;
            $toPage = 'ordres' ;
            $maker = $authUser->role ;
            Notification::send($users,new userNotif($order->id,$authUser->id,$imagePath,$body,$title,$toPage ,$maker)) ;
        }elseif($request->status == 'delivered'){
            $imagePath = 'https://icons.veryicon.com/png/o/business/common-icon-for-b-end-products/work-order-9.png' ;
            $body = 'Votre commande a été livré' ;
            $title = 'Commande "'.$order->titre.' "' ;
            $toPage = 'ordres' ;
            $maker = $authUser->role ;
            Notification::send($users,new userNotif($order->id,$authUser->id,$imagePath,$body,$title,$toPage ,$maker)) ;

        }

        $orders = Order::with('articles')->get();
        return response()->json(['orders' => $orders]);
    }
}
