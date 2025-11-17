<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class specification extends Model
{
    protected $fillable = [
        'product_id', 'processor', 'memori', 'display','camera','baterai','software','konektivitas'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
