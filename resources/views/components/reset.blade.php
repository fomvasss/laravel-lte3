<a href="{{ Request::url() . '?' . http_build_query(request()->only('type')) }}"
   class="{{ $attrs['class'] ?? 'btn btn-default float-right' }}"
   @isset($name)name="{{$name}}" @endisset
   @isset($value)value="{{$value}}" @endisset
   type="submit"
   data-toggle="tooltip"
>{!! $title !!}</a>