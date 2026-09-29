<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressAuditLog extends Model
{
    protected $fillable = [
        'address_id',
        'user_id',
        'old_data',
        'new_data',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
