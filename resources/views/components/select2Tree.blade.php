@php
    $input_name = !empty($attrs['multiple']) ? (Str::replaceLast('[]', '', $name) . '[]') : Str::replaceLast('[]', '', $name);
@endphp

<div class="form-group f-select2-tree-wrap {{ $attrs['class_wrap'] ?? null }}">
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <label for="{{ $input_name}}">{!! $label !!}</label>
    @endif
    <select name="{{ $input_name }}"
            class="form-control f-select2-tree-input is-invalid {{ $attrs['class'] ?? null }} @error($name) is-invalid @enderror"
            @if(!empty($attrs['multiple'])) multiple @endif
            data-url="{{ $attrs['url_tree'] ?? null }}"
            data-method-get="{{ $attrs['method_get'] ?? null }}"
            data-valFld="id"
            data-labelFld="name"
            data-incFld="children"
            autocomplete="off"
            style="width: 100%;"
            data-toggle="tooltip"
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    >
    </select>
    @error($name)
    <div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>
