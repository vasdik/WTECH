<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {
    }

    public function index(): View
    {
        $cart = $this->cartService->raw();

        $products = Product::query()
            ->with(['images', 'color', 'weight', 'diameter'])
            ->whereIn('id', $this->cartService->productIds())
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $items = collect($cart)
            ->map(function (array $item) use ($products) {
                $product = $products->get($item['product_id'] ?? null);

                if (! $product) {
                    return null;
                }

                $quantity = max(1, (int) ($item['quantity'] ?? 1));

                $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();

                $priceGross = (float) $product->price_gross;
                $lineTotal = round($quantity * $priceGross, 2);
                $lineNet = round($lineTotal / 1.23, 2);

                return [
                    'product' => $product,
                    'primary_image' => $primaryImage,
                    'quantity' => $quantity,
                    'price_gross' => $priceGross,
                    'line_net' => $lineNet,
                    'line_total' => $lineTotal,
                ];
            })
            ->filter()
            ->values();

        $subtotal = round($items->sum('line_total'), 2);
        $shipping = $items->isNotEmpty() ? 6.70 : 0.00;
        $total = round($subtotal + $shipping, 2);
        $vatTotal = round($total - ($total / 1.23), 2);
        $itemCount = (int) $items->sum('quantity');

        return view('shop.cart', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'vatTotal' => $vatTotal,
            'itemCount' => $itemCount,
        ]);
    }

    public function add(Product $product, Request $request): RedirectResponse|JsonResponse
    {
        abort_unless($product->is_active, 404);

        $this->cartService->add($product, max(1, (int) $request->input('quantity', 1)));

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Product added to cart.',
                'product_id' => $product->id,
                'quantity' => $this->cartService->quantityForProduct($product),
                'cart_count' => $this->cartService->count(),
                'cart' => $this->cartService->raw(),
            ]);
        }

        return back()->with('cart_success', 'Product added to cart.');
    }

    public function increment(Product $product, Request $request): RedirectResponse|JsonResponse
    {
        abort_unless($product->is_active, 404);

        $this->cartService->add($product, 1);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Product quantity increased.',
                'product_id' => $product->id,
                'quantity' => $this->cartService->quantityForProduct($product),
                'cart_count' => $this->cartService->count(),
                'cart' => $this->cartService->raw(),
            ]);
        }

        return back()->with('cart_success', 'Product quantity increased.');
    }

    public function decrement(Product $product, Request $request): RedirectResponse|JsonResponse
    {
        $this->cartService->decrement($product, 1);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Product quantity decreased.',
                'product_id' => $product->id,
                'quantity' => $this->cartService->quantityForProduct($product),
                'cart_count' => $this->cartService->count(),
                'cart' => $this->cartService->raw(),
            ]);
        }

        return back()->with('cart_success', 'Product quantity decreased.');
    }

    public function update(Product $product, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $this->cartService->update($product, (int) $validated['quantity']);

        return back()->with('cart_success', 'Cart updated.');
    }

    public function remove(Product $product, Request $request): RedirectResponse|JsonResponse
    {
        $this->cartService->remove($product);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Product removed from cart.',
                'product_id' => $product->id,
                'quantity' => 0,
                'cart_count' => $this->cartService->count(),
                'cart' => $this->cartService->raw(),
            ]);
        }

        return back()->with('cart_success', 'Product removed from cart.');
    }
}