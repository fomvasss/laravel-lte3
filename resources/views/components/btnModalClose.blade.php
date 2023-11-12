<button
   class="btn btn-default {{ $attrs['class'] ?? '' }}"
   data-toggle="tooltip"
   data-dismiss="modal"
@foreach(Arr::only($attrs, $field_attrs) as $key => $val)
    {{$key}}="{{$val}}"
@endforeach
>{!! $title !!}</button>
