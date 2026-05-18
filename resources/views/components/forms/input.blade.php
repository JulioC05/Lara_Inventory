@props([
    'label',
    'name',
    'id',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'validacion' => '',
    'bag' => 'default',
])


<div class="mb-6">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
    </label>
    <input type="{{ $type ?? 'text' }}" id="{{ $id }}" name="{{ $name }}"
        value="{{ old($name, $value ?? '') }}" placeholder="{{ $placeholder ?? '' }}"
        {{ $attributes->merge([
            'class' => 'form-control ' . ($errors->$bag->has($name) ? 'is-invalid' : ''),
        ]) }}>
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
