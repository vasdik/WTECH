<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(
        private CheckoutService $checkoutService,
        private CartService $cartService
    ) {
    }

    public function step1(): View|RedirectResponse
    {
        if ($redirect = $this->checkoutService->ensureCartNotEmpty()) {
            return $redirect;
        }

        return view('shop.checkout.step1', [
            'checkout' => $this->checkoutService->all(),
            'currentStep' => 1,
        ]);
    }

    public function storeStep1(Request $request): RedirectResponse
    {
        if ($redirect = $this->checkoutService->ensureCartNotEmpty()) {
            return $redirect;
        }

        $request->merge([
            'first_name' => trim((string) $request->input('first_name')),
            'last_name' => trim((string) $request->input('last_name')),
            'email' => trim((string) $request->input('email')),
            'phone' => trim((string) $request->input('phone')),
            'country' => trim((string) $request->input('country')),
            'street' => trim((string) $request->input('street')),
            'house_number' => trim((string) $request->input('house_number')),
            'city' => trim((string) $request->input('city')),
            'postal_code' => trim((string) $request->input('postal_code')),
        ]);

        $validated = $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-zÀ-ž\s\'’-]{2,100}$/u',
            ],
            'last_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-zÀ-ž\s\'’-]{2,100}$/u',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],          //'email' => ['required', 'email', 'max:255'], ak by to deploynute robilo problemy, naradit tymto
            'phone' => [
                'required',
                'string',
                'max:30',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $normalized = preg_replace('/[\s\-().]/', '', (string) $value);

                    if (! preg_match('/^\+?[1-9]\d{8,14}$/', $normalized)) {
                        $fail('Enter a valid phone number.');
                    }
                },
            ],
            'country' => [
                'required',
                'string',
                'in:Slovakia,Czech Republic,Austria,Germany,Poland',
            ],
            'street' => [
                'required',
                'string',
                'max:120',
                'regex:/^[A-Za-zÀ-ž0-9\s.\'’\/-]{2,120}$/u',
            ],
            'house_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Za-z0-9\/\- ]{1,20}$/',
            ],
            'city' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-zÀ-ž\s\'’-]{2,100}$/u',
            ],
            'postal_code' => [
                'required',
                'string',
                'max:10',
                function (string $attribute, mixed $value, \Closure $fail) use ($request) {
                    $country = (string) $request->input('country');
                    $postalCode = trim((string) $value);

                    $patterns = [
                        'Slovakia' => '/^\d{3}\s?\d{2}$/',
                        'Czech Republic' => '/^\d{3}\s?\d{2}$/',
                        'Austria' => '/^\d{4}$/',
                        'Germany' => '/^\d{5}$/',
                        'Poland' => '/^\d{2}-\d{3}$/',
                    ];

                    $messages = [
                        'Slovakia' => 'Use format 12345 or 123 45.',
                        'Czech Republic' => 'Use format 12345 or 123 45.',
                        'Austria' => 'Use 4 digits.',
                        'Germany' => 'Use 5 digits.',
                        'Poland' => 'Use format 12-345.',
                    ];

                    $pattern = $patterns[$country] ?? '/^[A-Za-z0-9][A-Za-z0-9\- ]{2,10}$/';

                    if (! preg_match($pattern, $postalCode)) {
                        $fail($messages[$country] ?? 'Enter a valid postal code.');
                    }
                },
            ],
        ], [
            'first_name.regex' => 'Enter a valid first name.',
            'last_name.regex' => 'Enter a valid last name.',
            'street.regex' => 'Enter a valid street name.',
            'house_number.regex' => 'Enter a valid house number.',
            'city.regex' => 'Enter a valid city.',
        ]);

        $validated['phone'] = preg_replace('/[\s\-().]/', '', $validated['phone']);

        $this->checkoutService->putStep1($validated);

        return redirect()->route('checkout.step2');
    }

    public function step2(): View|RedirectResponse
    {
        if ($redirect = $this->checkoutService->ensureCartNotEmpty()) {
            return $redirect;
        }

        if (! $this->checkoutService->stepCompleted(1)) {
            return redirect()->route('checkout.step1');
        }

        return view('shop.checkout.step2', [
            'checkout' => $this->checkoutService->all(),
            'savedAddresses' => $this->checkoutService->savedAddresses(),
            'currentStep' => 2,
        ]);
    }

    public function storeStep2(Request $request): RedirectResponse
    {
        if ($redirect = $this->checkoutService->ensureCartNotEmpty()) {
            return $redirect;
        }

        if (! $this->checkoutService->stepCompleted(1)) {
            return redirect()->route('checkout.step1');
        }

        $validated = $request->validate([
            'billing_source' => ['required', 'in:entered,saved'],
            'saved_billing_address_id' => ['nullable', 'integer'],
            'delivery_mode' => ['required', 'in:same_as_billing,saved,custom'],
            'saved_delivery_address_id' => ['nullable', 'integer'],
            'delivery_country' => ['nullable', 'string', 'max:255'],
            'delivery_street' => ['nullable', 'string', 'max:255'],
            'delivery_house_number' => ['nullable', 'string', 'max:50'],
            'delivery_city' => ['nullable', 'string', 'max:255'],
            'delivery_postal_code' => ['nullable', 'string', 'max:50'],
        ]);

        $checkout = $this->checkoutService->all();
        $savedAddresses = $this->checkoutService->savedAddresses()->keyBy('id');

        $billingAddress = $checkout['billing_address'];

        if ($validated['billing_source'] === 'saved') {
            if (! Auth::check()) {
                return back()->withErrors([
                    'saved_billing_address_id' => 'You must be logged in to use a saved billing address.',
                ])->withInput();
            }

            $savedBilling = $savedAddresses->get((int) $validated['saved_billing_address_id']);

            if (! $savedBilling) {
                return back()->withErrors([
                    'saved_billing_address_id' => 'Please select a valid saved billing address.',
                ])->withInput();
            }

            $billingAddress = $this->checkoutService->addressPayload($savedBilling);
        }

        $deliveryAddress = $billingAddress;

        if ($validated['delivery_mode'] === 'saved') {
            if (! Auth::check()) {
                return back()->withErrors([
                    'saved_delivery_address_id' => 'You must be logged in to use a saved delivery address.',
                ])->withInput();
            }

            $savedDelivery = $savedAddresses->get((int) $validated['saved_delivery_address_id']);

            if (! $savedDelivery) {
                return back()->withErrors([
                    'saved_delivery_address_id' => 'Please select a valid saved delivery address.',
                ])->withInput();
            }

            $deliveryAddress = $this->checkoutService->addressPayload($savedDelivery);
            $deliveryAddress['mode'] = 'saved';
        }

        if ($validated['delivery_mode'] === 'custom') {
            $custom = $request->validate([
                'delivery_country' => ['required', 'string', 'max:255'],
                'delivery_street' => ['required', 'string', 'max:255'],
                'delivery_house_number' => ['required', 'string', 'max:50'],
                'delivery_city' => ['required', 'string', 'max:255'],
                'delivery_postal_code' => ['required', 'string', 'max:50'],
            ]);

            $deliveryAddress = [
                'mode' => 'custom',
                'country' => $custom['delivery_country'],
                'street' => $custom['delivery_street'],
                'house_number' => $custom['delivery_house_number'],
                'city' => $custom['delivery_city'],
                'postal_code' => $custom['delivery_postal_code'],
            ];
        }

        if ($validated['delivery_mode'] === 'same_as_billing') {
            $deliveryAddress = array_merge(['mode' => 'same_as_billing'], $billingAddress);
        }

        $this->checkoutService->putResolvedStep2($billingAddress, $deliveryAddress);

        return redirect()->route('checkout.step3');
    }

    public function step3(): View|RedirectResponse
    {
        if ($redirect = $this->checkoutService->ensureCartNotEmpty()) {
            return $redirect;
        }

        if (! $this->checkoutService->stepCompleted(2)) {
            return redirect()->route('checkout.step2');
        }

        return view('shop.checkout.step3', [
            'checkout' => $this->checkoutService->all(),
            'paymentMethods' => $this->checkoutService->paymentMethods(),
            'currentStep' => 3,
        ]);
    }

    public function storeStep3(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_code' => ['required', 'string'],
        ]);

        $this->checkoutService->putStep3($validated);

        return redirect()->route('checkout.step4');
    }

    public function step4(): View|RedirectResponse
    {
        if ($redirect = $this->checkoutService->ensureCartNotEmpty()) {
            return $redirect;
        }

        if (! $this->checkoutService->stepCompleted(3)) {
            return redirect()->route('checkout.step3');
        }

        return view('shop.checkout.step4', [
            'checkout' => $this->checkoutService->all(),
            'shippingMethods' => $this->checkoutService->shippingMethods(),
            'currentStep' => 4,
        ]);
    }

    public function storeStep4(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_code' => ['required', 'string'],
        ]);

        $this->checkoutService->putStep4($validated);

        return redirect()->route('checkout.step5');
    }

    public function step5(): View|RedirectResponse
    {
        if ($redirect = $this->checkoutService->ensureCartNotEmpty()) {
            return $redirect;
        }

        if (! $this->checkoutService->stepCompleted(4)) {
            return redirect()->route('checkout.step4');
        }

        return view('shop.checkout.step5', [
            'checkout' => $this->checkoutService->all(),
            'summary' => $this->checkoutService->summary(),
            'currentStep' => 5,
        ]);
    }

    public function complete(): RedirectResponse
    {
        if ($redirect = $this->checkoutService->ensureCartNotEmpty()) {
            return $redirect;
        }

        if (! $this->checkoutService->stepCompleted(4)) {
            return redirect()->route('checkout.step4');
        }

        $checkout = $this->checkoutService->all();
        $summary = $this->checkoutService->summary();

        if ($summary['items']->isEmpty()) {
            return redirect()->route('cart')->with('cart_success', 'Your cart is empty.');
        }

        $paymentLabel = collect($this->checkoutService->paymentMethods())
            ->firstWhere('code', $checkout['payment']['code'])['label'] ?? $checkout['payment']['code'];

        $order = DB::transaction(function () use ($checkout, $summary, $paymentLabel) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(8)),
                'status' => 'placed',

                'customer_first_name' => $checkout['customer']['first_name'],
                'customer_last_name' => $checkout['customer']['last_name'],
                'customer_email' => $checkout['customer']['email'],
                'customer_phone' => $checkout['customer']['phone'],

                'billing_country' => $checkout['billing_address']['country'],
                'billing_street' => $checkout['billing_address']['street'],
                'billing_house_number' => $checkout['billing_address']['house_number'],
                'billing_city' => $checkout['billing_address']['city'],
                'billing_postal_code' => $checkout['billing_address']['postal_code'],

                'delivery_country' => $checkout['delivery_address']['country'],
                'delivery_street' => $checkout['delivery_address']['street'],
                'delivery_house_number' => $checkout['delivery_address']['house_number'],
                'delivery_city' => $checkout['delivery_address']['city'],
                'delivery_postal_code' => $checkout['delivery_address']['postal_code'],

                'payment_code' => $checkout['payment']['code'],
                'payment_label' => $paymentLabel,

                'shipping_code' => $checkout['shipping']['code'],
                'shipping_label' => $checkout['shipping']['label'],
                'shipping_eta_label' => $checkout['shipping']['eta_label'],

                'subtotal' => $summary['subtotal'],
                'discount_amount' => $summary['discount'],
                'shipping_amount' => $summary['shipping'],
                'total_amount' => $summary['total'],
                'vat_total' => $summary['vat_total'],
                'placed_at' => now(),
            ]);

            foreach ($summary['items'] as $item) {
                $product = $item['product'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_brand' => $product->brand,
                    'quantity' => $item['quantity'],
                    'unit_price_gross' => $item['price_gross'],
                    'unit_price_net' => round($item['line_net'] / max($item['quantity'], 1), 2),
                    'line_total' => $item['line_total'],
                ]);
            }

            if (Auth::check()) {
                $this->storeUserAddressesFromCheckout($checkout);
            }

            return $order;
        });

        $this->cartService->clear();
        $this->checkoutService->clear();

        return redirect()
            ->route('checkout.success', $order)
            ->with('cart_success', 'Order created successfully.');
    }

    public function success(Order $order): View
    {
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items');

        return view('shop.checkout.success', [
            'order' => $order,
        ]);
    }

    private function storeUserAddressesFromCheckout(array $checkout): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $billing = UserAddress::firstOrCreate(
            [
                'user_id' => $user->id,
                'first_name' => $checkout['customer']['first_name'],
                'last_name' => $checkout['customer']['last_name'],
                'phone' => $checkout['customer']['phone'],
                'country' => $checkout['billing_address']['country'],
                'street' => $checkout['billing_address']['street'],
                'house_number' => $checkout['billing_address']['house_number'],
                'city' => $checkout['billing_address']['city'],
                'postal_code' => $checkout['billing_address']['postal_code'],
            ],
            [
                'label' => 'Billing address',
                'is_default_billing' => ! UserAddress::where('user_id', $user->id)->where('is_default_billing', true)->exists(),
                'is_default_delivery' => false,
            ]
        );

        if (! $billing->is_default_billing) {
            $billing->update(['is_default_billing' => false]);
        }

        $delivery = UserAddress::firstOrCreate(
            [
                'user_id' => $user->id,
                'first_name' => $checkout['customer']['first_name'],
                'last_name' => $checkout['customer']['last_name'],
                'phone' => $checkout['customer']['phone'],
                'country' => $checkout['delivery_address']['country'],
                'street' => $checkout['delivery_address']['street'],
                'house_number' => $checkout['delivery_address']['house_number'],
                'city' => $checkout['delivery_address']['city'],
                'postal_code' => $checkout['delivery_address']['postal_code'],
            ],
            [
                'label' => 'Delivery address',
                'is_default_billing' => false,
                'is_default_delivery' => ! UserAddress::where('user_id', $user->id)->where('is_default_delivery', true)->exists(),
            ]
        );

        if (! $delivery->is_default_delivery) {
            $delivery->update(['is_default_delivery' => false]);
        }
    }
}