@php
    if ($attrs['type'] === 'password') {
        $value = '';
    }
@endphp


<div class="@if(isset($attrs['prepend']) || isset($attrs['append']) || $attrs['type'] === 'url') input-group @endif form-group {{ $attrs['class_wrap'] ?? null }}">
   {{-- @isset($attrs['label'])--}}
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <div style="width: 100%;"><label for="{{ $name }}">{!! $label !!}</label></div>
    @endif

    @isset($attrs['prepend'])
        <div class="input-group-prepend"><span class="input-group-text">{!!$attrs['prepend']!!}</span></div>
    @endisset

    @if($attrs['type'] === 'url')
        <div class="input-group-prepend">
            <span class="input-group-text">
                @if($value)<a href="{{$value}}" target="_blank"><i class="fas fa-link "></i></a>
                @else<i class="fas fa-link "></i>@endif
            </span>
        </div>
    @endif

    <input class="form-control @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
           name="{{ $name }}"
           type="{{ $attrs['type'] }}"
           value="{{ $value }}"
           data-toggle="tooltip"
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    >
    @isset($attrs['append'])
        <div class="input-group-append"><span class="input-group-text">{!!$attrs['append']!!}</span></div>
    @endisset

    @isset($attrs['checkbox'])
        <div class="input-group-prepend"
             @isset($attrs['checkbox']['title']) title="{{$attrs['checkbox']['title']}}" @endisset>
        <span class="input-group-text">
            <input name="{{ $attrs['checkbox']['name'] ?? '' }}" value="0" checked type="hidden">
            <input name="{{ $attrs['checkbox']['name'] ?? '' }}" value="1"
                   @if(!empty($attrs['checkbox']['value'])) checked
                   @endif @if(!empty($attrs['checkbox']['readonly'])) readonly @endif type="checkbox">
        </span>
        </div>
    @endisset
    @error($name)<div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>


