@php($method = (strtoupper($attrs['method'] ?? 'POST')) === 'GET' ? 'GET' : 'POST')
<form
        @if(!empty($attrs['class'])) class="{{ $attrs['class'] }}" @endif
        action="{{ $attrs['action'] ?? '#' }}"
        method="{{ $method }}"
        @if(!empty($attrs['files'])) enctype="multipart/form-data" @endif
        @if(!empty($attrs['style'])) style="{{$attrs['style']}}" @endif
        accept-charset="UTF-8"
        @if(Arr::get($attrs, 'disabled')) disabled @endif
        @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
            {{$key}}="{{$val}}"
        @endforeach

        @if(isset($attrs['data']) && is_array($attrs['data']))
            @foreach($attrs['data'] as $dataKey => $dataVal)
                data-{{$dataKey}}="{{$dataVal}}"
          @endforeach
        @endif
>

    @if($method !== 'GET')
    <input name="_method" value="{{ $attrs['method'] ?? 'POST' }}" type="hidden">
    <input name="_token" value="{{ csrf_token() }}" type="hidden">
    @endif

    @isset($attrs['label'])
        <div class="form-group">
            <label>{!! $attrs['label'] !!}</label>
        </div>
    @endisset


@if(!empty($attrs['close']))
</form>
@endif
