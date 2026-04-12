@php
    $quantityInCart = \App\Support\Cart::quantity($product->id);
    $fullWidth = $fullWidth ?? false;
@endphp

@if ($quantityInCart > 0)
    <div class="qty-widget {{ $fullWidth ? 'w-100' : '' }}">
        <form method="POST" action="{{ route('cart.decrement', $product) }}" class="d-inline">
            @csrf
            <button type="submit" class="qty-btn btn btn-sm">−</button>
        </form>

        <span class="qty-label">{{ $quantityInCart }} in cart</span>

        <form method="POST" action="{{ route('cart.add', $product) }}" class="d-inline">
            @csrf
            <button type="submit" class="qty-btn btn btn-sm">+</button>
        </form>
    </div>
@else
    <form method="POST" action="{{ route('cart.add', $product) }}" class="{{ $fullWidth ? 'w-100' : 'd-inline' }}">
        @csrf
        <button type="submit" class="btn btn-custom btn-sm {{ $fullWidth ? 'w-100' : '' }}">
            Add to Cart
        </button>
    </form>
@endif