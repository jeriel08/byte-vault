@if ($attributes->has('href'))
    <a {{ $attributes->merge(['class' => 'primary-button d-flex align-items-center justify-content-center gap-2 mb-4']) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => 'primary-button d-flex align-items-center justify-content-center gap-2 mb-4']) }}>
        {{ $slot }}
    </button>
@endif