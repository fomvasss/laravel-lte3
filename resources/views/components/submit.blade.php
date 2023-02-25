<button class="btn btn-primary {{ $attrs['class'] ?? null }}"
    @isset($name)name="{{$name}}"@endisset
    @isset($value)value="{{$value}}"@endisset
    type="submit"
    data-toggle="tooltip"
>{{ $title }}</button>
