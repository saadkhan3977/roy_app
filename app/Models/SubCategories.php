<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategories extends Model
{
    use HasFactory;
    protected $table = 'sub_categories';
    protected $guarded = [];
	
	public function child_categories()
    {
        return $this->hasMany(\App\Models\ChildCategories::class, "sub_cat_id" ,"id");
    }
}
