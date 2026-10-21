@props(['padding' => true, 'accent' => false, 'hover' => false])

@php
    $classes = 'card';
    if ($accent) $classes .= ' card-accent';
    if ($hover) $classes .= ' card-hover';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($header))
        <div class="card-header">
            <div class="font-heading text-base font-semibold text-slate-900">{{ $header }}</div>
            @if(isset($headerAction))
                <div>{{ $headerAction }}</div>
            @endif
        </div>
    @endif

    @if($padding)
        <div class="{{ isset($header) ? 'card-body' : '' }}">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif

    @if(isset($footer))
        <div class="border-t border-slate-100 pt-4 mt-4">
            {{ $footer }}
        </div>
    @endif
</div>
