@php
    $url = $attrs['url'] ?? Request::url();
    if (!empty($attrs['ignores'])) {
        $url = $url . '?' . http_build_query(request()->only($attrs['ignores']));
    }
@endphp

<a @empty(Arr::get($attrs, 'disabled')) href="{{ $url }}" @endempty
   class="btn btn-default {{ $attrs['class'] ?? '' }}"
   data-toggle="tooltip"
@isset($attrs['confirm'])
   onclick="return confirm('{{ $attrs['confirm'] }}')"
@endisset
@foreach(Arr::only($attrs, $field_attrs) as $key => $val)
    {{$key}}="{{$val}}"
@endforeach
>{!! $title !!}</a>
