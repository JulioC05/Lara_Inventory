@props([
    'label',
    'name',
    'id' => '',
    'rows',
    'value' => '',
    'placeholder' => '',
    'validacion' => '',
    'bag' => 'default',
    'class' => ''
])

<div class="{{ $class ? $class : 'mb-6' }}">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
    </label>
    <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}"
        placeholder="{{ $placeholder ?? '' }}"
        {{ $attributes->merge([
            'class' => 'form-control ' . ($errors->$bag->has($name) ? 'is-invalid' : ''),
        ]) }}>{{ old($name, $value ?? '') }}</textarea>
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
