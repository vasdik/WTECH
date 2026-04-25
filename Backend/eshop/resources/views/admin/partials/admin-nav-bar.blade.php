@php
    $steps = $steps ?? [];
    $currentStep = $currentStep ?? null;

    $currentIndex = collect($steps)->search(fn ($step) => $step['key'] === $currentStep);
    $prevStep = $currentIndex !== false && $currentIndex > 0 ? $steps[$currentIndex - 1] : null;
    $nextStep = $currentIndex !== false && $currentIndex < count($steps) - 1 ? $steps[$currentIndex + 1] : null;
@endphp

<nav class="border-bottom py-3 nav-custom mb-4">
    <div class="container px-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap gap-2">
                @foreach ($steps as $step)
                    <a
                        href="{{ $step['route'] }}"
                        class="btn btn-sm {{ $step['key'] === $currentStep ? 'btn-custom' : 'btn-outline-custom' }}"
                    >
                        {{ $step['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="d-flex gap-2">
                @if ($prevStep)
                    <a href="{{ $prevStep['route'] }}" class="btn btn-outline-custom btn-sm">
                        ← Back
                    </a>
                @endif

                @if ($nextStep)
                    <a href="{{ $nextStep['route'] }}" class="btn btn-custom btn-sm">
                        Next →
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>