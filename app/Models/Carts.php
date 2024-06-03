<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $guarded = [];
	
	public function product_info()
    {
        return $this->hasOne(\App\Models\Product::class,"id","product_id");
    }
	public function category_info()
    {
        return $this->hasOne(\App\Models\Categories::class,"id","category");
    }
    public function sub_cat_info()
    {
        return $this->hasOne(\App\Models\SubCategories::class,"id","sub_cat_id");
    }
    public function child_cat_info()
    {
        return $this->hasOne(\App\Models\ChildCategories::class,"id","child_cat_id");
    }
    public function varation()
    {
        return $this->hasMany(\App\Models\ProductVarations::class,"product_id","id");
    }
}
