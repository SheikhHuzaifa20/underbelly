<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLogs extends Model
{
    protected $table = 'order_status_logs';

    protected $fillable = [
        'order_id', 'status'
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }
}
