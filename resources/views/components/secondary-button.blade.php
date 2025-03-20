<button {{ $attributes->merge(['type' => 'button', 'class' => 'secondary-button d-flex align-items-center justify-content-center gap-2 mb-4']) }}>
    {{ $slot }}
</button>
