<button class="btn btn-primary {{ $attrs['class'] ?? '' }}"
        @isset($name)name="{{$name}}" @endisset
        @isset($value)value="{{$value}}" @endisset
        type="submit"
        data-toggle="tooltip"
        @if(Arr::get($attrs, 'disabled')) disabled @endif
        @isset($attrs['confirm'])
            onclick="return confirm('{{ $attrs['confirm'] }}')"
        @endisset
        @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
            {{$key}}="{{$val}}"
        @endforeach
>{!!$attrs['before_title'] ?? ''!!} {!! $title !!} {!!$attrs['after_title'] ?? ''!!}</button>

@if(\Illuminate\Support\Arr::get($attrs, 'add') === 'fixed')
    <button type="submit" class="btn btn-primary btn-lte-fixed" @if(Arr::get($attrs, 'disabled')) disabled @endif>
        <i class="fa fa-save"></i>
        <span class="table__options-text">{!! $title !!}</span>
    </button>
@endif
