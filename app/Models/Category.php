<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'category';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
