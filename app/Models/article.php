<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class article extends Model
{
    use HasFactory;
    protected $fillable = [
        'designationArticle'
    ] ;
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
