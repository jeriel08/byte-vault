<button {{ $attributes->merge(['type' => 'submit', 'class' => 'primary-button d-flex align-items-center justify-content-center gap-2 mb-4']) }}>
    {{ $slot }}
</button>
