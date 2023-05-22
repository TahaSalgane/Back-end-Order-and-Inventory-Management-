<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomSalle',
        'type '
    ] ;

    public function articles()
    {
        return $this->hasOne(articlesClass::class);
    }
    public function order()
{
    return $this->belongsTo(Order::class);
}
}
