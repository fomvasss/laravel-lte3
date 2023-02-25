@php
    if (isset($selected)) {
        //
    } elseif (!empty($model)) {
        $selected = $model->{$name};
    } else {
        $selected = old($name, $selected);
    }
@endphp

<div class="form-group {{ $attrs['class_wrap'] ?? null }}">

    @isset($attrs['label'])
    <label>{!! $attrs['label'] ?: Str::studly($name) !!}</label>
    @endisset

    @foreach($options ?: [] as $value => $label)
    <div class="custom-control custom-radio">
        <input class="custom-control-input @error($name) is-invalid @enderror"
            name="{{ $name }}"
            value="{{$value}}"
            id="{{ $name.$loop->index }}"
            @if($selected == $value) checked="" @endif
            type="radio"
         >
        <label class="custom-control-label"for="{{ $name.$loop->index }}">{{ $label }}</label>
    </div>
    @endforeach
    @error($name) <span class="error invalid-feedback" style="display: inline;">{{ $message }}</span> @enderror
    @isset($attrs['help'])<div style="width: 100%;"><small>{!! $attrs['help'] !!}</small></div>@endisset
</div>
