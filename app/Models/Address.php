<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $fillable = [
        'user_id', 'type', 'recipient', 'street', 'city',
        'state', 'zip', 'country', 'phone'
    ];

    public function auditLogs()
    {
        return $this->hasMany(AddressAuditLog::class, 'address_id');
    }
}
