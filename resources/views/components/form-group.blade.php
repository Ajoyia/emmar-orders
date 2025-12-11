@props(['label', 'name', 'type' => 'text', 'value' => null, 'placeholder' => '', 'required' => false, 'options' => null, 'selected' => null])

<div class="form-group">
    <strong>{{ $label }}@if($required)<span class="text-danger">*</span>@endif:</strong>
    @if($type === 'select' && $options)
    <select name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" id="{{ $name }}">
        <option value="">--Select--</option>
        @foreach($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" {{ ($selected !== null && $selected == $optionValue) || old($name) == $optionValue ? 'selected' : '' }}>
            {{ $optionLabel }}
        </option>
        @endforeach
    </select>
    @else
    <input type="{{ $type }}" 
           name="{{ $name }}" 
           class="form-control @error($name) is-invalid @enderror" 
           placeholder="{{ $placeholder }}" 
           value="{{ $value ?? old($name) }}"
           @if($required) required @endif>
    @endif
    @error($name)
    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
    @enderror
</div>

