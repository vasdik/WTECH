@extends('layouts.shop')

@section('title', 'S&J - My Cart')

@section('content')
    @php
        $itemCount = $itemCount ?? collect($items ?? [])->sum(fn ($item) => $item['quantity'] ?? 0);
    @endphp

    <div class="container px-3 py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Main page</a></li>
                <li class="breadcrumb-item active">My Cart</li>
            </ol>
        </nav>
    </div>

    <main class="flex-grow-1">
        <div class="container px-3 py-4">
            @if (session('cart_success'))
                <div class="alert alert-success">
                    {{ session('cart_success') }}
                </div>
            @endif

            <div class="border rounded p-4">
                <div class="row g-4">
                    <div class="col-md-8">
                        <p class="mb-4">
                            <span class="fw-bold">My cart:</span>
                            &nbsp; {{ $itemCount }} product(s) &nbsp;
                            <span class="fw-bold">Total: {{ number_format($total ?? 0, 2) }} €</span>
                        </p>

                        @forelse (($items ?? []) as $item)
                            @php
                                $product = $item['product'] ?? null;
                                $image = $item['primary_image'] ?? null;

                                $quantity = $item['quantity'] ?? 1;

                                $variant = $product?->defaultVariant ?? $product?->variants?->first();

                                $priceGross = $item['price_gross']
                                    ?? $variant?->price_gross
                                    ?? $product?->price_gross
                                    ?? 0;

                                $lineTotal = $item['line_total'] ?? ($priceGross * $quantity);

                                $lineNet = $item['line_net'] ?? ($lineTotal / 1.23);
                            @endphp

                            @if ($product)
                                <div class="row g-3 align-items-center border-bottom pb-4 mb-4">
                                    <div class="col-auto">
                                        @if ($image)
                                            <img
                                                src="{{ asset($image->path) }}"
                                                alt="{{ $image->alt_text ?? $product->name }}"
                                                style="width: 130px; height: 130px; object-fit: scale-down;"
                                            >
                                        @else
                                            <div class="img-placeholder" style="width: 130px; height: 130px;">Product image</div>
                                        @endif
                                    </div>

                                    <div class="col">
                                        <p class="fw-bold mb-0">{{ $product->brand }}</p>
                                        <p class="text-muted small mb-1">{{ $product->name }}</p>

                                        <p class="text-success small fw-bold mb-1">
                                            {{ ($product->stock_qty ?? 0) > 0 ? 'In stock' : 'Out of stock' }}
                                        </p>

                                        <p class="mb-0 fw-bold">{{ number_format($priceGross, 2) }} €</p>
                                        <p class="text-muted small mb-2">
                                            before VAT: {{ number_format($lineNet / max($quantity, 1), 2) }} €
                                        </p>

                                        <div class="d-flex gap-2 flex-wrap">
                                            <form method="POST" action="{{ route('cart.remove', $product) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-custom btn-sm">
                                                    Remove from Cart
                                                </button>
                                            </form>

                                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-custom btn-sm">
                                                Product detail
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-auto text-end">
                                        <div class="qty-widget mb-2">
                                            <form method="POST" action="{{ route('cart.decrement', $product) }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="qty-btn btn btn-sm">−</button>
                                            </form>

                                            <span class="qty-label">{{ $quantity }} in cart</span>

                                            <form method="POST" action="{{ route('cart.increment', $product) }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="qty-btn btn btn-sm">+</button>
                                            </form>
                                        </div>

                                        <p class="fw-bold">Sum: {{ number_format($lineTotal, 2) }} €</p>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="alert alert-light border mb-0">
                                Your cart is empty.
                            </div>
                        @endforelse
                    </div>

                    <div class="col-md-4">
                        <h6 class="fw-bold mb-3">Cart overview:</h6>

                        <p class="small text-muted mb-1">Shipping Country:</p>
                        <select class="form-select form-select-sm mb-3" disabled>
                            <option selected>Slovakia</option>
                        </select>

                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Subtotal:</span>
                            <span>{{ number_format($subtotal ?? 0, 2) }} €</span>
                        </div>

                        <div class="d-flex justify-content-between small mb-3">
                            <span class="text-muted">Packaging and Shipping:</span>
                            <span>
                                {{ ($shipping ?? 0) > 0 ? number_format($shipping, 2) . ' €' : 'Free' }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-bold small">Total amount:</span>
                            <span class="fw-bold">{{ number_format($total ?? 0, 2) }} €</span>
                        </div>

                        <p class="text-muted small mb-3">Includes VAT: {{ number_format($vatTotal ?? 0, 2) }} €</p>

                        <button class="btn btn-custom w-100 mb-3" {{ $itemCount < 1 ? 'disabled' : '' }}>
                            Order now
                        </button>

                        <div class="img-placeholder" style="height: 90px;">Supported payment methods</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection