<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'products';
    protected $guarded = [];

    public function category_info()
    {
        return $this->hasOne(\App\Models\Categories::class,"id","cat_id");
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
