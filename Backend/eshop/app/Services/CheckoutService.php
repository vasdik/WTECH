<?php

namespace App\Services;

use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CheckoutService
{
    private const SESSION_KEY = 'checkout';

    public function __construct(
        private CartService $cartService
    ) {
    }

    public function all(): array
    {
        return session(self::SESSION_KEY, $this->defaults());
    }

    public function defaults(): array
    {
        $user = Auth::user();

        $defaultBilling = $user?->addresses()
            ->where('is_default_billing', true)
            ->latest('id')
            ->first();

        $defaultDelivery = $user?->addresses()
            ->where('is_default_delivery', true)
            ->latest('id')
            ->first();

        return [
            'customer' => [
                'first_name' => $defaultBilling?->first_name ?? '',
                'last_name' => $defaultBilling?->last_name ?? '',
                'email' => $user?->email ?? '',
                'phone' => $defaultBilling?->phone ?? $defaultDelivery?->phone ?? '',
            ],
            'billing_address' => $defaultBilling
                ? $this->addressPayload($defaultBilling)
                : [
                    'country' => '',
                    'street' => '',
                    'house_number' => '',
                    'city' => '',
                    'postal_code' => '',
                ],
            'delivery_address' => $defaultDelivery
                ? array_merge(['mode' => 'saved'], $this->addressPayload($defaultDelivery))
                : [
                    'mode' => 'same_as_billing',
                    'country' => '',
                    'street' => '',
                    'house_number' => '',
                    'city' => '',
                    'postal_code' => '',
                ],
            'payment' => [
                'code' => null,
            ],
            'shipping' => [
                'code' => null,
                'label' => null,
                'price' => null,
                'eta_label' => null,
            ],
            'coupon' => [
                'code' => null,
                'discount_amount' => 0,
            ],
        ];
    }

    public function savedAddresses(): Collection
    {
        if (! Auth::check()) {
            return collect();
        }

        return Auth::user()
            ->addresses()
            ->latest('is_default_delivery')
            ->latest('is_default_billing')
            ->latest('id')
            ->get();
    }

    public function addressPayload(UserAddress $address): array
    {
        return [
            'country' => $address->country,
            'street' => $address->street,
            'house_number' => $address->house_number,
            'city' => $address->city,
            'postal_code' => $address->postal_code,
        ];
    }

    public function putStep1(array $data): void
    {
        $checkout = $this->all();

        $checkout['customer'] = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ];

        $checkout['billing_address'] = [
            'country' => $data['country'],
            'street' => $data['street'],
            'house_number' => $data['house_number'],
            'city' => $data['city'],
            'postal_code' => $data['postal_code'],
        ];

        session([self::SESSION_KEY => $checkout]);
    }

    public function putResolvedStep2(array $billingAddress, array $deliveryAddress): void
    {
        $checkout = $this->all();

        $checkout['billing_address'] = $billingAddress;
        $checkout['delivery_address'] = $deliveryAddress;

        session([self::SESSION_KEY => $checkout]);
    }

    public function putStep3(array $data): void
    {
        $checkout = $this->all();

        $checkout['payment'] = [
            'code' => $data['payment_code'],
        ];

        // Keď sa zmení payment, nech sa prípadne zresetuje shipping z predošlého pokusu
        $checkout['shipping'] = [
            'code' => null,
            'label' => null,
            'price' => null,
            'eta_label' => null,
        ];

        session([self::SESSION_KEY => $checkout]);
    }

    public function putStep4(array $data): void
    {
        $checkout = $this->all();

        $selectedShipping = collect($this->shippingMethods())
            ->firstWhere('code', $data['shipping_code']);

        if (! $selectedShipping) {
            return;
        }

        $checkout['shipping'] = [
            'code' => $selectedShipping['code'],
            'label' => $selectedShipping['label'],
            'price' => $selectedShipping['price'],
            'eta_label' => $selectedShipping['eta_label'],
        ];

        session([self::SESSION_KEY => $checkout]);
    }

    public function paymentMethods(): array
    {
        return [
            ['code' => 'bank_transfer', 'label' => 'Bank Transfer'],
            ['code' => 'cash_on_delivery', 'label' => 'Cash on Delivery'],
            ['code' => 'google_pay', 'label' => 'Google Pay'],
            ['code' => 'apple_pay', 'label' => 'Apple Pay'],
            ['code' => 'credit_card', 'label' => 'Credit Card'],
        ];
    }

    public function shippingMethods(): array
    {
        return [
            [
                'code' => 'dpd',
                'label' => 'DPD',
                'price' => 6.70,
                'eta_label' => 'Delivery: after March 19th',
                'features' => ['Online tracking of shipment'],
            ],
            [
                'code' => 'fedex_priority',
                'label' => 'Fedex Priority Shipping',
                'price' => 16.30,
                'eta_label' => 'Delivery: after March 13th',
                'features' => ['Online tracking of shipment'],
            ],
            [
                'code' => 'dhl_express',
                'label' => 'DHL Express',
                'price' => 28.48,
                'eta_label' => 'Delivery: on March 10th',
                'features' => ['Online tracking of shipment'],
            ],
        ];
    }

    public function summary(): array
    {
        $checkout = $this->all();
        $cart = $this->cartService->raw();

        $products = Product::query()
            ->with(['images', 'color', 'weight', 'diameter'])
            ->whereIn('id', $this->cartService->productIds())
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $items = collect($cart)
            ->map(function (array $row) use ($products) {
                $product = $products->get($row['product_id'] ?? null);

                if (! $product) {
                    return null;
                }

                $quantity = max(1, (int) ($row['quantity'] ?? 1));
                $priceGross = (float) $product->price_gross;
                $lineTotal = round($quantity * $priceGross, 2);
                $lineNet = round($lineTotal / 1.23, 2);
                $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price_gross' => $priceGross,
                    'line_total' => $lineTotal,
                    'line_net' => $lineNet,
                    'primary_image' => $primaryImage,
                ];
            })
            ->filter()
            ->values();

        $subtotal = round($items->sum('line_total'), 2);
        $discount = (float) ($checkout['coupon']['discount_amount'] ?? 0);
        $subtotalAfterDiscount = max(0, round($subtotal - $discount, 2));
        $shipping = (float) ($checkout['shipping']['price'] ?? 0);
        $total = round($subtotalAfterDiscount + $shipping, 2);
        $vatTotal = round($total - ($total / 1.23), 2);

        return [
            'items' => $items,
            'itemCount' => (int) $items->sum('quantity'),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'subtotal_after_discount' => $subtotalAfterDiscount,
            'shipping' => $shipping,
            'total' => $total,
            'vat_total' => $vatTotal,
        ];
    }

    public function ensureCartNotEmpty(): ?RedirectResponse
    {
        if ($this->cartService->count() < 1) {
            return redirect()->route('cart')
                ->with('cart_success', 'Your cart is empty.');
        }

        return null;
    }

    public function stepCompleted(int $step): bool
    {
        $checkout = $this->all();

        return match ($step) {
            1 => filled($checkout['customer']['email']),
            2 => filled($checkout['delivery_address']['country']),
            3 => filled($checkout['payment']['code']),
            4 => filled($checkout['shipping']['code']),
            default => false,
        };
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }
}