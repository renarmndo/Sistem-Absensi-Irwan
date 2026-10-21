@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-lg bg-green-50 border border-green-200 p-3 text-sm text-status-hadir']) }}>
        {{ $status }}
    </div>
@endif
