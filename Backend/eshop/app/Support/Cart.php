<?php

namespace App\Support;

use App\Models\Product;

class Cart
{
    private const SESSION_KEY = 'cart';

    public static function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public static function productIds(): array
    {
        return array_map('intval', array_keys(self::raw()));
    }

    public static function quantity(int $productId): int
    {
        return (int) (self::raw()[$productId]['quantity'] ?? 0);
    }

    public static function count(): int
    {
        return array_sum(array_column(self::raw(), 'quantity'));
    }

    public static function add(Product $product, int $quantity = 1): void
    {
        $cart = self::raw();
        $productId = $product->id;

        if (! isset($cart[$productId])) {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => 0,
            ];
        }

        $cart[$productId]['quantity'] += max(1, $quantity);

        session([self::SESSION_KEY => $cart]);
    }

    public static function decrement(Product $product, int $quantity = 1): void
    {
        $cart = self::raw();
        $productId = $product->id;

        if (! isset($cart[$productId])) {
            return;
        }

        $cart[$productId]['quantity'] -= max(1, $quantity);

        if ($cart[$productId]['quantity'] <= 0) {
            unset($cart[$productId]);
        }

        session([self::SESSION_KEY => $cart]);
    }

    public static function update(Product $product, int $quantity): void
    {
        $cart = self::raw();
        $productId = $product->id;

        if ($quantity <= 0) {
            unset($cart[$productId]);
            session([self::SESSION_KEY => $cart]);
            return;
        }

        $cart[$productId] = [
            'product_id' => $productId,
            'quantity' => $quantity,
        ];

        session([self::SESSION_KEY => $cart]);
    }

    public static function remove(Product $product): void
    {
        $cart = self::raw();
        unset($cart[$product->id]);

        session([self::SESSION_KEY => $cart]);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }
}