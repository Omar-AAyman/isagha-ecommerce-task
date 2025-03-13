@extends('layouts.main')

@section('content')
<div class="container text-center">
    <h2>🎉 Payment Successful!</h2>
    <p>{{ session('success', 'Thank you for your purchase. Your order has been processed successfully.') }}</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
</div>
@endsection