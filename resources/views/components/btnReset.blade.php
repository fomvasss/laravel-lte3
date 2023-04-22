@php
    $url = $attrs['url'] ?? Request::url();
    if (!empty($attrs['ignores'])) {
        $url = $url . '?' . http_build_query(request()->only($attrs['ignores']));
    }
@endphp

<a href="{{ $url }}"
   class="btn btn-default {{ $attrs['class'] ?? '' }}"
   data-toggle="tooltip"
@foreach(Arr::only($attrs, $field_attrs) as $key => $val)
    {{$key}}="{{$val}}"
@endforeach
>{!! $title !!}</a>