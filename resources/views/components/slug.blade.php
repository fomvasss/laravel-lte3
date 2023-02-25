@php
    if (isset($value) && $value instanceof \Illuminate\Database\Eloquent\Model) {
        $model = $value;
        $value = $value->{$name};
    } elseif (isset($model)) {
        // ...
    } elseif (!empty($model)) {
        $value = $model->{$name};
    } else {
        $value = old($name, $value);
    }
@endphp

<div class="input-group form-group js-verification-slug-field {{ $attrs['class_wrap'] ?? null }}">
    @isset($attrs['label'])
        <div style="width: 100%;"><label for="{{ $name }}">{!! $attrs['label'] ?: Str::studly($name) !!}</label></div>
    @endisset

    @if(isset($model) || $value)

        <input type="text"
            class="form-control js-slug-field-input @error($name) is-invalid @enderror @isset($attrs['class']) {{ $attrs['class'] }} @endisset"
            @if($name) name="{{ $name }}" @endif
            value="{{ $value }}"
            data-toggle="tooltip"
            @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
                {{$key}}="{{$val}}"
            @endforeach
            readonly
        >

        <div class="input-group-append">
            <span class="input-group-text">
                <!-- <input name="{{ $attrs['checkbox']['name'] ?? '' }}" value="0" checked type="hidden" > -->
                <input name="{{ $name.'_change' }}" value="1" class="js-slug-field-change" @if(old($name . '_change')) checked @endif type="checkbox">
            </span>
        </div>

    @else

        <input type="text"
                class="form-control js-slug-field-input @error($name) is-invalid @enderror"
                @if($name) name="{{ $name }}" @endif
                value="{{ old($name, $value) }}"
                @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
                    {{$key}}="{{$val}}"
                @endforeach
        >
        <div class="input-group-append">
            <span class="input-group-text">
                <!-- <input name="{{ $attrs['checkbox']['name'] ?? '' }}" value="0" checked type="hidden" > -->
                <input name="{{ $name.'_change' }}" value="1" class="js-slug-field-change" @if(old($name . '_change')) checked @endif type="checkbox">
            </span>
        </div>

    @endif
    @error($name) <div class="invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>
