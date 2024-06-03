<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $guarded = [];

   public function item_info()
    {
        return $this->hasMany(\App\Models\Carts::class,"order_id","id");
    }
}
