@props([
    'label',
    'name',
    'id' => '',
    'value' => '',
    'validacion' => '',
    'bag' => 'default',
    'class' => ''
])

<div class="{{ $class ? $class : 'mb-6' }}">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
    </label>
    <select name="{{ $name }}" id="{{ $id }}"
        {{ $attributes->merge([
            'class' => 'form-control ' . ($errors->$bag->has($name) ? 'is-invalid' : ''),
        ]) }}>{{ $slot }}</select>
    @if ($errors->$bag->has($name))
        <div class="invalid-feedback">
            {{ $errors->$bag->first($name) }}
        </div>  
    @else
        <div class="invalid-feedback">
            {{ $validacion }}
        </div>
    @endif
</div>
