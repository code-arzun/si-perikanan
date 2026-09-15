@props(['name', 'label', 'type' => 'text', 'value' => '', 'required' => false, 'placeholder' => '', 'hint' => null])

<div style="margin-bottom: 1rem;">
    <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.85rem; color: #374151;">
        {{ $label }} @if($required)<span style="color: #ef4444;">*</span>@endif
    </label>
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 0.9rem;"
    >
    @if($hint)
        <small style="color: #6b7280; font-size: 0.75rem; display: block; margin-top: 2px;">{{ $hint }}</small>
    @endif
    @error($name)
        <small style="color: #ef4444; font-size: 0.75rem; display: block; margin-top: 2px;">{{ $message }}</small>
    @enderror
</div>