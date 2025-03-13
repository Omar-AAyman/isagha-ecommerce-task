@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Order #{{ $order->id }}</h2>
    <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>
    <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
    <p><strong>Transaction ID:</strong> {{ $order->payment_reference }}</p>

    <h4>Items</h4>
    <ul>
        @foreach ($order->items as $item)
            <li>{{ $item->product->name }} - {{ $item->quantity }} x ${{ number_format($item->price, 2) }}</li>
        @endforeach
    </ul>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
</div>
@endsection
