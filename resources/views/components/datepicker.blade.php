@php
    if ($value instanceof \DateTime) {
        if (!empty($attrs['timezone'])) {
            $value = $value->setTimezone($attrs['timezone']);
        }
        if (!empty($attrs['format'])) {
            $value = $value->format($attrs['format']);
        }
    }
@endphp

<div class="form-group {{ $attrs['class_wrap'] ?? null }}" @if(!empty($attrs['hidden_wrap'])) hidden @endif>
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <label>{!! $label !!}</label>
    @endif
    <input
            name="{{ $name }}"
            value="{{ $value }}"
            class="form-control f-datepicker @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
            type="text" autocomplete="off"
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    >
    @error($name)
    <div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>
