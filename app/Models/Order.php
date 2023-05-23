<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre' ,
        'articles' ,
        'etablissement' ,
        'status' ,
        'user_id'
    ] ;

    public function articles()
    {
        return $this->hasMany(articles::class);
    }
    public function reclamation(){
        return $this->hasOne(Reclamation::class) ;
    }
    public function user(){
        return $this->belongsTo(User::class) ;
    }
}
