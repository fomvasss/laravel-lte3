@php
    $unchecked_value = isset($attrs['unchecked_value']) ? $attrs['unchecked_value'] : 0;
    $checked_value = isset($attrs['checked_value']) ? $attrs['checked_value'] : 1;
    $raw_name = $attrs['raw_name'] ?? Str::replaceLast('[]', '', $name);
    $is_simple = \Illuminate\Support\Arr::get($attrs, 'is_simple') ? true : false;
@endphp

<div class="form-group {{ $attrs['class_wrap'] ?? null }}" @if(!empty($attrs['hidden_wrap'])) hidden @endif>
    <div class="@if($is_simple) form-check @else custom-control @endif {{ $attrs['class_control'] ?? null }}">
        @if($unchecked_value !== '')
            <input type="hidden" name="{{ $name }}" value="{{ $unchecked_value }}">
        @endif
        <input class="@if($is_simple) form-check-input @else custom-control-input @endif @if(!empty($attrs['url_save'])) f-checkbox-ajax @endif @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
               name="{{ $name }}"
               value="{{ $checked_value }}"
               @if($value && $value !== $unchecked_value) checked @endif
               data-raw-name="{{$raw_name}}"
               @if(!empty($attrs['method_save'])) data-method-save="{{$attrs['method_save']}}" @endif
               data-format="{{ isset($attrs['format']) && $attrs['format'] === 'name,value' ? 'name,value' : 'name=value' }}"
               @if(!empty($attrs['url_save'])) data-url-save={{$attrs['url_save']}} @endif

               data-toggle="tooltip"
               @empty($attrs['id'])id="{{ $name }}"@endempty
        @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
            {{$key}}="{{$val}}"
        @endforeach
        type="checkbox"
        >
        @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
            <label for="{{ $attrs['id'] ?? $name }}" class="@if($is_simple) form-check-label @else custom-control-label @endif">{!! $label !!}</label>
        @endif
    </div>
    @error($name) <span class="error invalid-feedback" style="display: inline;">{{ $message }}</span> @enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>
