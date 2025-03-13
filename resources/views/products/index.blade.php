@extends('layouts.main')

@section('title', 'Products')

@section('content')
    <h2 class="mb-4 text-center">Available Products</h2>
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4">
            <div class="card product-card mb-4 shadow-sm">
                <img src="{{ asset('images/' . $product->image) }}" class="product-img" alt="{{ $product->name }}">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                    <p class="card-text text-muted">EGP {{ number_format($product->price, 2) }}</p>
                    <button onclick="addToCart({{ $product->id }})" class="btn btn-primary w-100">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
