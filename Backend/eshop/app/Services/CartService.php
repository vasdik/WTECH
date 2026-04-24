<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartService
{
    private ?array $rawCache = null;
    private ?int $cachedUserId = null;

    public function raw(): array
    {
        if (Auth::check()) {
            return $this->databaseRaw(Auth::user());
        }

        return $this->sessionRaw();
    }

    public function productIds(): array
    {
        return array_map('intval', array_keys($this->raw()));
    }

    public function count(): int
    {
        return array_sum(
            array_map(
                fn (array $row) => (int) ($row['quantity'] ?? 0),
                $this->raw()
            )
        );
    }

    public function quantityForProduct(Product|int $product): int
    {
        $productId = $product instanceof Product ? $product->id : (int) $product;

        return (int) ($this->raw()[$productId]['quantity'] ?? 0);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $quantity = max(1, $quantity);

        if (Auth::check()) {
            $item = CartItem::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            $item->quantity = (int) $item->quantity + $quantity;
            $item->save();

            $this->forgetCache();
            return;
        }

        $cart = $this->sessionRaw();
        $currentQty = (int) ($cart[$product->id]['quantity'] ?? 0);

        $cart[$product->id] = [
            'product_id' => $product->id,
            'quantity' => $currentQty + $quantity,
        ];

        session(['cart' => $cart]);
    }

    public function decrement(Product $product, int $quantity = 1): void
    {
        $quantity = max(1, $quantity);

        if (Auth::check()) {
            $item = CartItem::query()
                ->where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if (! $item) {
                return;
            }

            $newQty = (int) $item->quantity - $quantity;

            if ($newQty <= 0) {
                $item->delete();
            } else {
                $item->quantity = $newQty;
                $item->save();
            }

            $this->forgetCache();
            return;
        }

        $cart = $this->sessionRaw();

        if (! isset($cart[$product->id])) {
            return;
        }

        $newQty = (int) $cart[$product->id]['quantity'] - $quantity;

        if ($newQty <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id]['quantity'] = $newQty;
        }

        session(['cart' => $cart]);
    }

    public function update(Product $product, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($product);
            return;
        }

        if (Auth::check()) {
            CartItem::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                ],
                [
                    'quantity' => $quantity,
                ]
            );

            $this->forgetCache();
            return;
        }

        $cart = $this->sessionRaw();

        $cart[$product->id] = [
            'product_id' => $product->id,
            'quantity' => $quantity,
        ];

        session(['cart' => $cart]);
    }

    public function remove(Product $product): void
    {
        if (Auth::check()) {
            CartItem::query()
                ->where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->delete();

            $this->forgetCache();
            return;
        }

        $cart = $this->sessionRaw();
        unset($cart[$product->id]);

        session(['cart' => $cart]);
    }

    public function mergeSessionIntoUserCart(User $user): void
    {
        $guestCart = $this->sessionRaw();

        if (empty($guestCart)) {
            return;
        }

        foreach ($guestCart as $row) {
            $productId = (int) ($row['product_id'] ?? 0);
            $quantity = max(1, (int) ($row['quantity'] ?? 1));

            if ($productId < 1) {
                continue;
            }

            $product = Product::query()
                ->whereKey($productId)
                ->where('is_active', true)
                ->first();

            if (! $product) {
                continue;
            }

            $item = CartItem::firstOrNew([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);

            $item->quantity = (int) $item->quantity + $quantity;
            $item->save();
        }

        session()->forget('cart');
        $this->forgetCache();
    }

    private function sessionRaw(): array
    {
        return session('cart', []);
    }

    private function databaseRaw(User $user): array
    {
        if ($this->rawCache !== null && $this->cachedUserId === $user->id) {
            return $this->rawCache;
        }

        $raw = CartItem::query()
            ->where('user_id', $user->id)
            ->get(['product_id', 'quantity'])
            ->mapWithKeys(fn (CartItem $item) => [
                (int) $item->product_id => [
                    'product_id' => (int) $item->product_id,
                    'quantity' => (int) $item->quantity,
                ],
            ])
            ->all();

        $this->rawCache = $raw;
        $this->cachedUserId = $user->id;

        return $raw;
    }

    public function clear(): void
    {
        if (auth()->check()) {
            \App\Models\CartItem::query()
                ->where('user_id', auth()->id())
                ->delete();

            $this->forgetCache();
            return;
        }

        session()->forget('cart');
    }


    private function forgetCache(): void
    {
        $this->rawCache = null;
        $this->cachedUserId = null;
    }
}