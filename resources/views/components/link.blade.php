<div class="form-group">
    @if($attrs['label'] ?? false)
        <label class="d-block">&nbsp;</label>
    @endif
    <a href="{{ $url }}"
       class="btn btn-{{ $attrs['btn_class'] ?? 'primary' }} {{ $attrs['class'] ?? '' }}"
       @isset($attrs['target']) target="{{ $attrs['target'] }}" @endisset
    >{{ $attrs['label'] ?? '' }}</a>
</div>
