@php
    if ($value instanceof \DateTime && !empty($attrs['format'])) {
        $value = $value->format($attrs['format']);
    }
@endphp

<div class="form-group {{ $attrs['class_wrap'] ?? null }}">
    @isset($attrs['label'])
        <label>{!! $attrs['label'] ?: Str::studly($name) !!}</label>
    @endisset
    <input
            name="{{ $name }}"
            value="{{ $value }}"
            class="f-timepicker form-control @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
            type="text" autocomplete="off"
            data-toggle="tooltip"
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    >
    @error($name)
    <div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>

