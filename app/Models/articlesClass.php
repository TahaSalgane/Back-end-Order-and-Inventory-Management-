<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articlesClass extends Model
{
    use HasFactory;

    protected $table = 'articles_classes';
    protected $fillable = ['classe_id','articles'];
    protected $casts = [
        'articles' => 'json',
    ];


    public function classe()
    {
        return $this->belongsTo(classe::class);
    }

}
