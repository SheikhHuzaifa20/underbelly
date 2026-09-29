<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
	protected $table = 'orders_products';

    public function order()
    {
        return $this->belongsTo(Orders::class, 'orders_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'order_products_product_id', 'id')->withDefault([
            'name' => 'Product Deleted',
            'price' => 0
        ]);
    }
}
