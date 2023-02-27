@isset($attrs['class_wrap']) <div class="{{$attrs['class_wrap']}}">@endisset
    @isset($attrs['label'])
        <div class="form-group">
            <label for="{{ $name }}" data-toggle="tooltip">{!! $attrs['label'] ?: Str::studly($name) !!}</label>
        </div>
    @endisset

    <input type="hidden" name="{{ $name }}" value="{{ $value }}" class="{{ $attrs['class'] ?? '' }}"
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    >
@isset($attrs['class_wrap'])</div>@endisset