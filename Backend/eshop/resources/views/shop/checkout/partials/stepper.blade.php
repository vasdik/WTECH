<div class="stepper">
    @php
        $steps = [
            1 => ['label' => 'My Information', 'route' => route('checkout.step1')],
            2 => ['label' => 'Delivery Address', 'route' => route('checkout.step2')],
            3 => ['label' => 'Payment Method', 'route' => route('checkout.step3')],
            4 => ['label' => 'Delivery Options', 'route' => route('checkout.step4')],
            5 => ['label' => 'Summary', 'route' => route('checkout.step5')],
        ];
    @endphp

    @foreach ($steps as $number => $step)
        <div class="stepper-step">
            @if ($number < $currentStep)
                <a href="{{ $step['route'] }}" class="stepper-circle">{{ $number }}</a>
            @elseif ($number === $currentStep)
                <div class="stepper-circle active">{{ $number }}</div>
            @else
                <div class="stepper-circle">{{ $number }}</div>
            @endif

            <span class="stepper-label">{{ $step['label'] }}</span>
        </div>
    @endforeach
</div>