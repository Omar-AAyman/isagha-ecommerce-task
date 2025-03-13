@extends('layouts.main')

@section('content')
<div class="container">
    <h2>My Orders</h2>
    <div class="table-responsive">
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Total Price</th>
                    <th>Payment Status</th>
                    <th>Transaction ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>EGP {{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ ucfirst($order->payment_status) }}</td>
                    <td>{{ $order->payment_reference }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
