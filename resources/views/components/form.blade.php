<form
        class="{{ $attrs['class'] ?? null}}"
        action="{{ $attrs['action'] ?? '#' }}"
        method="{{ (strtoupper($attrs['method'] ?? 'POST')) === 'GET' ? 'GET' : 'POST' }}"
        @if(!empty($attrs['files'])) enctype="multipart/form-data" @endif
        accept-charset="UTF-8"
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
