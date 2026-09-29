<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>

    <style>
        body { font-family: DejaVu Sans; font-size: 12px; color: #333 }
        .header { display: flex; justify-content: space-between }
        .badge { padding: 4px 8px; background: #eee; border-radius: 4px }
        table { width: 100%; border-collapse: collapse; margin-top: 20px }
        th, td { border: 1px solid #ddd; padding: 8px }
        th { background: #f9fafb }
        .text-right { text-align: right }
    </style>
</head>

<body>

{{-- Header --}}
<div class="header">
    <div>
        <h2>INVOICE</h2>
        <p><strong>Order #:</strong> {{ $order->id }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
    </div>

    <div>
        <strong>Xtend Systems</strong><br>
        support@xtend-systems.com<br>
        +1 234 567 890
    </div>
</div>

<hr>

{{-- Customer --}}
<p>
    <strong>Billed To:</strong><br>
    {{ $order->billingAddress->recipient ?? '-' }}<br>
    {{ $order->billingAddress->street ?? '-' }}<br>
    {{ $order->billingAddress->city ?? '' }},
    {{ $order->billingAddress->country ?? '' }}
</p>

{{-- Products --}}
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th class="text-right">Price</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->order_products as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product->name }}</td>
                <td class="text-right">${{ number_format($item->product->price, 2) }}</td>
                <td class="text-right">{{ $item->order_products_qty }}</td>
                <td class="text-right">
                    ${{ number_format($item->product->price * $item->order_products_qty, 2) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Totals --}}
<table style="width:40%; margin-left:auto; margin-top:20px">
    <tr>
        <th>Subtotal</th>
        <td class="text-right">${{ $order->order_products->sum(fn($op) => ($op->product?->price ?? 0) * $op->order_products_qty) }}</td>
    </tr>
    <tr>
        <th>Tax</th>
        <td class="text-right">${{ $order->shipping_tax ?? 0.00 }}</td>
    </tr>
    <tr>
        <th>Total</th>
        <td class="text-right">
            <strong>${{ $order->order_products->sum(fn($op) => ($op->product?->base_price ?? 0) * $op->order_products_qty) + ($order->discount_amount ?? 0.00) + ($order->shipping_tax ?? 0.00) }}</strong>
        </td>
    </tr>
</table>

<p style="margin-top:40px; font-size:11px">
    Thank you for your business.
</p>

</body>
</html>
