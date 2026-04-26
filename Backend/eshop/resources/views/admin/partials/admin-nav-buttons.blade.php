@php
    $mode = $mode ?? 'dashboard';

    $adminSteps = [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'route' => route('admin.dashboard'),
        ],
        [
            'key' => 'products',
            'label' => 'Products',
            'route' => route('admin.products.index'),
        ],
        [
            'key' => 'create',
            'label' => 'Add Product',
            'route' => route('admin.products.create'),
        ],
    ];

    if ($mode === 'edit' && isset($product)) {
        $adminSteps[] = [
            'key' => 'edit',
            'label' => 'Edit Product',
            'route' => route('admin.products.edit', $product),
        ];
    }
@endphp

@include('admin.partials.admin-nav-bar', [
    'steps' => $adminSteps,
    'currentStep' => $mode,
])