<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class brand extends Model
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'name', 'logo_brand'
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
