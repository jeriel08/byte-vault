@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'form-control rounded py-2']) }}>
