@isset($attrs['class_wrap']) <div class="{{$attrs['class_wrap']}}">@endisset
    @if($label = Arr::get($attrs, 'label'))
        <div class="form-group">
            <label data-toggle="tooltip">{!! $label !!}</label>
        </div>
    @endif

    <input type="hidden" name="{{ $name }}" value="{{ $value }}" class="{{ $attrs['class'] ?? '' }}"
    @if(Arr::get($attrs, 'disabled')) disabled @endif
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    @foreach(Arr::get($attrs, 'attrs') ?? [] as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    >
    @error($name)<div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
@isset($attrs['class_wrap'])</div>@endisset