@php
    $name = str_ends_with($name, '[]') ? $name : $name . '[]';
@endphp

<div class="input-group form-group {{ $attrs['class_wrap'] ?? null }}" @if(!empty($attrs['hidden_wrap'])) hidden @endif>
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <div style="width: 100%;"><label for="{{ $name }}">{!! $label !!}</label></div>
    @endif
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
    </div>
    <input
            name="_{{ $name }}"
            data-name="{{ $name }}"
            data-min="{{ Arr::get($attrs, 'min') }}"
            data-max="{{ Arr::get($attrs, 'max') }}"
            data-format="{{ Arr::get($attrs, 'format') }}"
            value="{{ implode(', ', Arr::wrap($value)) }}"
            class="form-control f-multiDatesPicker @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
            type="text" autocomplete="off"
            readonly
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    >
    @error($name)
    <div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset

    @foreach(Arr::wrap($value) as $date)
        <input type="hidden" name="{{ $name }}" value="{{ $date }}">
    @endforeach
</div>
