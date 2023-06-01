<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'titre', 'description'];


    public function order(){
        return $this->belongsTo(Order::class) ;
    }
}
