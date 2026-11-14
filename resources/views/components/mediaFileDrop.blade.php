{{-- Зона вибору файлів для lte3::components.mediaFile: під списком або остання плитка сітки зображень --}}
<label class="f-media-drop @error($name) is-invalid @enderror {{ $disabled ? 'disabled' : '' }}">
    {{-- без name: кожен обраний файл переноситься у власний input рядка (див. скрипт) --}}
    <input type="file"
           class="f-media-input {{ $attrs['class'] ?? '' }}"
           @if($multiple) multiple @endif
           @if($disabled) disabled @endif
           @if($accept) accept="{{ $accept }}" @endif>
    <i class="fas {{ $isImage ? 'fa-image' : 'fa-cloud-upload-alt' }} f-media-drop-icon"></i>
    <span class="f-media-drop-text">
        <strong>{{ $multiple ? __('Choose files') : __('Choose file') }}</strong>
        {{ __('or drag them here') }}
    </span>
    <span class="f-media-drop-add">{{ __('Add') }}</span>
    @if($acceptLabel)
        <small class="text-muted f-media-drop-text">{{ $acceptLabel }}</small>
    @endif
</label>
