<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable = [
        'brand_id', 'slug', 'name', 'description', 'image_product', 'stock', 'price', 'status'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function specification()
    {
        return $this->hasOne( Specification::class );
    }
}
