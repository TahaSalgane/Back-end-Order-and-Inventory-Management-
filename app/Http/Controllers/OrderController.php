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
                           ->orWhere('etablissement','complexe')
                           ->orWhere('role','magasinier');
                   }]
            ])->get() ;
            $imagePath = 'https://cdn.pixabay.com/photo/2013/07/12/16/51/internet-151384_1280.png' ;
            $body = 'Vous avez une commande depuis letablissement ' ;
            $title = 'Une commande a ete cree ' ;
            $routeName = 'readNotOrder' ;
            $toPage = 'orders' ;
            $maker = $authUser->etablissement ;
            Notification::send($users,new userNotif($order->id,$authUser->id,$imagePath,$body,$title,$routeName,$toPage ,$maker)) ;

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
        $order->status = $request->input('status');
        $order->save();

        $orders = Order::with('articles')->get();
        return response()->json(['orders' => $orders]);
    }
}
