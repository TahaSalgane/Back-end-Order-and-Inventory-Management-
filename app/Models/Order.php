<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\article;
use App\Models\Reclamation;
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
        return $this->belongsToMany(article::class);
    }
    public function reclamation(){
        return $this->hasMany(Reclamation::class) ;
    }
    public function user(){
        return $this->belongsTo(User::class) ;
    }
}
