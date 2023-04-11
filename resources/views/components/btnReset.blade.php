<a href="{{ $attrs['url'] ?? (Request::url() . '?' . http_build_query(request()->only('type'))) }}"
    class="btn btn-default {{ $attrs['class'] ?? '' }}"
    data-toggle="tooltip"
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
>{!! $title !!}</a>