<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $table = 'blog';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'description','long_text', 'image', 'status', 'sort_order'];
    protected $dates = ['deleted_at'];
}