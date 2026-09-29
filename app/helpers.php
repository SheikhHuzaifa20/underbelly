<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('log_activity')) {
    function log_activity($action, $entity, $entityId, $description = null, $changes = null)
    {
        ActivityLog::create([
            'admin_id'     => Auth::id(),
            'entity_type'  => is_object($entity) ? get_class($entity) : $entity,
            'entity_id'    => $entityId,
            'action'       => $action,
            'description'  => $description,
            'ip_address'   => request()->ip(),
            'changes'      => $changes,
        ]);
    }
}

function payment_method_info($method)
{
    $array = [
        'stripe' => [
            'name' => 'Stripe',
            'logo' => asset('assets/imgs/stripe.png'),
        ],

        'paypal' => [
            'name' => 'PayPal',
            'logo' => asset('assets/imgs/paypal.png'),
        ],

        'cod' => [
            'name' => 'Cash on Delivery',
            'logo' => asset('assets/imgs/cod.png'),
        ],
    ];
    return $array[$method] ?? [
        'name' => ucfirst(str_replace('_', ' ', $method)),
        'logo' => asset('assets/imgs/noimage.png'),
    ];
}
?>
