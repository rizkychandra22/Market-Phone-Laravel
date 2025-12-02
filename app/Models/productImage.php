<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class productImage extends Model
{
    protected $fillable = [
        'product_id',
        'image',
        'is_primary',
    ];

    public function product()
    {
        return $this->belongsTo(product::class);
    }
}
