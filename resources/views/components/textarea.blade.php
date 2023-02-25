@php
    if (isset($value)) {

    } elseif (!empty($model)) {
        $value = $model->{$name};
    } else {
        $value = old($name, $value);
    }
@endphp

<div class="form-group {{ $attrs['class_wrap'] ?? null }}">
    @isset($attrs['label'])
    <label for="{{ $name }}">{!! $attrs['label'] ?: Str::studly($name) !!}</label>
    @endisset

    <textarea class="form-control @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
        id="{{ $name }}"
        name="{{ $name }}"
        data-toggle="tooltip"
        @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
            {{$key}}="{{$val}}"
        @endforeach
    >{!! $value !!}</textarea>

    @error($name) <div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>
