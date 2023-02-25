@php
    if (isset($value)) {

    } elseif (!empty($model)) {
        $value = $model->{$name};
    } else {
        $value = old($name, $value);
    }
@endphp

@isset($attrs['label'])
<div class="form-group {{ $attrs['class_wrap'] ?? null }}">
    <label for="{{ $name }}" data-toggle="tooltip">{!! $attrs['label'] ?: Str::studly($name) !!}</label>
</div>
@endisset
<input type="hidden" name="{{ $name }}" value="{{ $value }}" class="{{ $attrs['class'] ?? '' }}"
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
>
