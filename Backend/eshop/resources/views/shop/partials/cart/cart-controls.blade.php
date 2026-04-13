@php
    $cart = session('cart', []);
    $cartQty = (int) ($cart[$product->id]['quantity'] ?? 0);
    $buttonClass = $buttonClass ?? 'btn btn-custom';
    $fullWidth = $fullWidth ?? false;
@endphp

<div
    class="cart-control"
    data-product-id="{{ $product->id }}"
    data-add-url="{{ route('cart.add', $product) }}"
    data-increment-url="{{ route('cart.increment', $product) }}"
    data-decrement-url="{{ route('cart.decrement', $product) }}"
    data-full-width="{{ $fullWidth ? '1' : '0' }}"
>
    @if ($cartQty > 0)
        <div class="qty-widget {{ $fullWidth ? 'w-100' : '' }}">
            <form method="POST" action="{{ route('cart.decrement', $product) }}" class="m-0 cart-action-form">
                @csrf
                <button type="submit" class="qty-btn btn btn-sm">−</button>
            </form>

            <span class="qty-label">{{ $cartQty }} in cart</span>

            <form method="POST" action="{{ route('cart.increment', $product) }}" class="m-0 cart-action-form">
                @csrf
                <button type="submit" class="qty-btn btn btn-sm">+</button>
            </form>
        </div>
    @else
        <form method="POST" action="{{ route('cart.add', $product) }}" class="m-0 {{ $fullWidth ? 'w-100' : '' }} cart-action-form">
            @csrf
            <button type="submit" class="{{ $buttonClass }} {{ $fullWidth ? 'w-100' : '' }}">
                Add to Cart
            </button>
        </form>
    @endif
</div>