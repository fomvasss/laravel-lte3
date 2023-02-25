@php
    $type = $attrs['type'] ?? 'text';
    if ($type === 'password') {
        $value = '';
    } elseif (isset($value)) {

    } elseif (!empty($model)) {
        $value = $model->{$name};
    } else {
        $value = old($name, $value) ?? 'FFF';
    }

@endphp

<div class="form-group {{ $attrs['class_wrap'] ?? null }}">
    @isset($attrs['label'])
        <label>{!! $attrs['label'] ?: Str::studly($name) !!}</label>
    @endisset
    <div class="input-group f-colorpicker colorpicker-element">
        <input type="text" class="form-control {{ $attrs['class'] ?? '' }}"
            data-original-title=""
            @if($name) name="{{ $name }}" @endif
            value="{{ $value }}"
            @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
                {{$key}}="{{$val}}"
            @endforeach
        >
        <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-square" style="color: {{ $value }};"></i></span>
        </div>
    </div>
    @error($name) <div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>



