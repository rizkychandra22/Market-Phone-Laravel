<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable = [
        'user_id', 'brand_id', 'slug', 'name', 'description', 'image_product', 'stock', 'price', 'status', 'processor', 'memori', 'display','camera','baterai','software','konektivitas'
    ];

    public function brand()
    {
        return $this->belongsTo(\App\Models\brand::class);
    }
}
