<button class="btn btn-primary {{ $attrs['class'] ?? '' }}"
        @isset($name)name="{{$name}}" @endisset
        @isset($value)value="{{$value}}" @endisset
        type="submit"
        data-toggle="tooltip"
        @isset($attrs['confirm'])
            onclick="return confirm('{{ $attrs['confirm'] }}')"
        @endisset
        @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
            {{$key}}="{{$val}}"
        @endforeach
>{!! $title !!}</button>

@if(\Illuminate\Support\Arr::get($attrs, 'add') === 'fixed')
    <button type="submit" class="btn btn-primary btn-lte-fixed" data-toggle="tooltip" title="{!! $title !!}"><i class="fa fa-save"></i></button>
@endif