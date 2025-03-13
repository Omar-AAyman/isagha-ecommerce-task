@extends('layouts.main')

@section('title', 'Shopping Cart')

@section('content')
<h2 class="text-center mb-4">Your Shopping Cart</h2>

@if($cartItems->isNotEmpty())
<div class="table-responsive">
    <table class="table table-bordered bg-white text-center">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cartItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>EGP {{ number_format($item->product->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>EGP {{ number_format($item->product->price * $item->quantity, 2) }}</td>
                <td>
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h4 class="text-end">Total: <strong>EGP {{ number_format($total, 2) }}</strong></h4>

<!-- Checkout Button -->
<div class="text-end my-4">
    <form action="{{ route('checkout') }}" method="POST">
        @csrf
        <input type="hidden" name="amount" value="{{ $total }}">
        <button type="submit" class="btn btn-success btn-lg px-4 py-2 shadow">
            <i class="fas fa-credit-card"></i> Checkout
        </button>
    </form>
</div>

@else
<p class="text-center">Your cart is empty. <a href="{{ route('products.index') }}">Shop now</a></p>
@endif
@endsection
