<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable = [
        'user_id',
        'brand_id',
        'name',
        'description',
        'chipset',
        'software',
        'display',
        'dimensi',
        'camera',
        'baterai',
        'network',
        'konektivitas',
    ];

    public function brand()
    {
        return $this->belongsTo(\App\Models\brand::class);
    }

    public function variants()
    {
        return $this->hasMany(productVariant::class);
    }

    public function images()
    {
        return $this->hasMany(productImage::class);
    }
}
