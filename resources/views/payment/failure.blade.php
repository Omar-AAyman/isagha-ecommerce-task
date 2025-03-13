@extends('layouts.main')

@section('content')
<div class="container text-center">
    <h2>❌ Payment Failed</h2>
    <p>{{ session('error', 'Unfortunately, your payment was not successful. Please try again or contact support if the issue persists.') }}</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">Go Back to Shopping</a>
</div>
@endsection
