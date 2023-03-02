@php
    $unchecked_value = isset($attrs['unchecked_value']) ? $attrs['unchecked_value'] : 0;
    $checked_value = isset($attrs['checked_value']) ? $attrs['checked_value'] : 1;
    $raw_name = $raw_name ?? Str::replaceLast('[]', '', $name);
@endphp
<div class="form-group {{ $attrs['class_wrap'] ?? null }}">
    <div class="custom-control {{ $attrs['wrap_class'] ?? '' }}">
        <input type="hidden" name="{{ $name }}" value="{{ $unchecked_value }}">
        <input class="custom-control-input @if(!empty($attrs['url_save'])) f-checkbox-ajax @endif @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
               name="{{ $name }}"
               id="{{ $name }}"
               value="{{ $checked_value }}"
               data-raw-name="{{$raw_name}}"
               @if(!empty($attrs['method_save'])) data-method-save="{{$attrs['method_save']}}" @endif
               data-format="{{ isset($format) && $format === 'name,value' ? 'name,value' : 'name=value' }}"
               @if(!empty($attrs['url_save'])) data-url-save={{$attrs['url_save']}} @endif
               @if($value) checked @endif
               data-toggle="tooltip"
        @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
            {{$key}}="{{$val}}"
        @endforeach
        type="checkbox"
        >
        @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
            <label for="{{ $name }}" class="custom-control-label">{!! $label !!}</label>
        @endif
    </div>
    @error($name) <span class="error invalid-feedback" style="display: inline;">{{ $message }}</span> @enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>
