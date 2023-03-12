<div class="form-group f-wrap f-range {{ $attrs['class_wrap'] ?? null }}">
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <div><label for="{{ $name }}">{!! $label !!}</label></div>
    @endif
    <input type="range"
        name="{{ $name }}"
           class="custom-range {{ $attrs['class'] ?? null }}"
           value="{{ $value }}"
           data-toggle="tooltip"
           oninput="this.nextElementSibling.value = this.value"
        @isset($attrs['min']) min="{{$attrs['min']}}" @endisset
        @isset($attrs['max']) max="{{$attrs['max']}}" @endisset
        @isset($attrs['step']) step="{{$attrs['step']}}" @endisset
        id="{{ $name }}"
        @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
        @endforeach
        style="width: 96%"
    ><output style="float: right; display: inline; font-size: 80%">{{ $value }}</output>
        @error($name)<div class="error invalid-feedback"> {{ $message }} </div>@enderror
        @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>

