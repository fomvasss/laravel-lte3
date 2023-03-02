@isset($attrs['class_wrap']) <div class="{{$attrs['class_wrap']}}">@endisset
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <div class="form-group">
            <label for="{{ $name }}" data-toggle="tooltip">{!! $label !!}</label>
        </div>
    @endif

    <input type="hidden" name="{{ $name }}" value="{{ $value }}" class="{{ $attrs['class'] ?? '' }}"
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    >
@isset($attrs['class_wrap'])</div>@endisset