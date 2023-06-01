<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api') ;
    }

    public function getUnreadNotifications(){
        try {
            $unreadNotif = Auth::user()->unreadNotifications->all() ;
            return  response()->json([
                'notificationsList' => $unreadNotif
            ]) ;
        } catch (Exception $exp) {
            return  response()->json([
                $exp->getMessage()
            ]) ;
        }

    }
    public function markOneAsRead($id){
        DB::table('notifications')->where('id',$id)->delete() ;
        return response()->json([
            'message' => 'ok'
        ]) ;
    }
}
