<div class="form-group {{ $attrs['class_wrap'] ?? null }}">
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <label>{!! $label !!}</label>
    @endif
    <div class="input-group f-colorpicker colorpicker-element">
        <input type="text" class="form-control {{ $attrs['class'] ?? '' }}"
               name="{{ $name }}"
               @if(Arr::get($attrs, 'transparent'))
               data-color="transparent"
               @endif
               value="{{ $value }}"
               data-original-title=""
        @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
            {{$key}}="{{$val}}"
        @endforeach
        >
        <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-square" style="color: {{ $value }};"></i></span>
        </div>
    </div>
    @error($name)
    <div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>



