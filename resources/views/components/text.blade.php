@php
    if ($attrs['type'] === 'password') {
        $value = '';
        $attrs['placeholder'] = \Illuminate\Support\Arr::get($attrs, 'placeholder', '********');
    }
@endphp

<div class="@if(isset($attrs['prepend']) || isset($attrs['append']) || isset($attrs['checkbox'])  || $attrs['type'] === 'url') input-group @endif form-group position-relative {{ $attrs['class_wrap'] ?? null }}" @if(!empty($attrs['hidden_wrap'])) hidden @endif>
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <div style="width: 100%;"><label for="{{ $name }}">{!! $label !!}</label></div>
    @endif

    @isset($attrs['prepend'])
        <div class="input-group-prepend">
            @foreach(\Illuminate\Support\Arr::wrap($attrs['prepend']) as $val)
            <span class="input-group-text">{!!$val!!}</span>
            @endforeach
        </div>
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
    @if(Arr::get($attrs, 'disabled')) disabled @endif
    @if(Arr::get($attrs, 'readonly')) readonly @endif
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    @foreach(Arr::get($attrs, 'attrs') ?? [] as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    @if(isset($attrs['data']) && is_array($attrs['data']))
        @foreach($attrs['data'] as $dataKey => $dataVal)
            data-{{$dataKey}}="{{$dataVal}}"
        @endforeach
    @endif
    >
    @isset($attrs['append'])
        <div class="input-group-append">
            @foreach(\Illuminate\Support\Arr::wrap($attrs['append']) as $val)
            <span class="input-group-text">{!!$val!!}</span>
            @endforeach
        </div>
    @endisset

    @if(Arr::get($attrs, 'tokens'))
        <div class="btn-group btn-group-tokens dropleft">
            <button type="button" class="btn btn-default p-0 border-0 bg-transparent" data-toggle="dropdown" aria-expanded="false">
                <i class="far fa-caret-square-down"></i>
            </button>
            <div class="dropdown-menu" role="menu">
                @foreach(Arr::get($attrs, 'tokens', []) as $key => $name)
                    <a class="dropdown-item js-clipboard" href="#" data-text="{{ $key }}">{{ $name }} - {{ $key }}</a>
                @endforeach
            </div>
        </div>
    @endif

    @isset($attrs['checkbox'])
        <div class="input-group-append"
             @isset($attrs['checkbox']['title']) title="{{$attrs['checkbox']['title']}}" @endisset>
        <span class="input-group-text">
            <input name="{{ $attrs['checkbox']['name'] ?? '' }}" value="0" type="hidden" checked @if(Arr::get($attrs, 'checkbox.disabled')) disabled @endif>
            <input name="{{ $attrs['checkbox']['name'] ?? '' }}" value="1" type="checkbox"
                   @if(!empty($attrs['checkbox']['value'])) checked @endif
                   @if(Arr::get($attrs, 'checkbox.readonly')) onclick="return false;" @endif
                   @if(Arr::get($attrs, 'checkbox.disabled')) disabled @endif
            >
        </span>
        </div>
    @endisset
    @error($name)<div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>


