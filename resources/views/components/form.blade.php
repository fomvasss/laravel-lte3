<form
        @if(!empty($attrs['class'])) class="{{ $attrs['class'] }}" @endif
        action="{{ $attrs['action'] ?? '#' }}"
        method="{{ (strtoupper($attrs['method'] ?? 'POST')) === 'GET' ? 'GET' : 'POST' }}"
        @if(!empty($attrs['files'])) enctype="multipart/form-data" @endif
        @if(!empty($attrs['style'])) style="{{$attrs['style']}}" @endif
        accept-charset="UTF-8"
        @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
            {{$key}}="{{$val}}"
        @endforeach
>

    <input name="_method" value="{{ $attrs['method'] ?? 'POST' }}" type="hidden">
    <input name="_token" value="{{ csrf_token() }}" type="hidden">

    @isset($attrs['label'])
        <div class="form-group">
            <label>{!! $attrs['label'] !!}</label>
        </div>
    @endisset


@if(!empty($attrs['close']))
</form>
@endif
