<a href="{{ Request::url() . '?' . http_build_query(request()->only('type')) }}"
    class="btn btn-default {{ $attrs['class'] ?? '' }}"
    @isset($name)name="{{$name}}" @endisset
    @isset($value)value="{{$value}}" @endisset
    type="submit"
    data-toggle="tooltip"
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
>{!! $title !!}</a>
