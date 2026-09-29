<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use SoftDeletes;

    protected $table = 'testimonial';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'description', 'rating', 'image', 'status', 'sort_order'];
    protected $dates = ['deleted_at'];
}
