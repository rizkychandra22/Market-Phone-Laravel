<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class productVariant extends Model
{
    protected $fillable = [
        'product_id',
        'color',
        'memori',
        'price',
        'stock',
    ];

    public function product()
    {
        return $this->belongsTo(product::class);
    }
}
