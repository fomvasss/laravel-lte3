@php
    if (isset($selected)) {

    } elseif (!empty($model)) {
        $selected = $model->{$name};
    } else {
        $selected = old($name, $selected);
    }

    $selected = !is_null($selected) ? \Arr::wrap($selected) : [];
    $old = old($name) ? Arr::wrap(old($name)) : [];

    $field_name_input = !empty($attrs['multiple']) && (empty($attrs['max']) || $attrs['max'] > 1) ? (Str::replaceLast('[]', '', $name) . '[]') : Str::replaceLast('[]', '', $name);
    $selected = $old + $selected;
    $options = isset($options) ? \Arr::wrap($options) : []; // TODO: OR url_suggest
    $disableds = \Arr::wrap(\Arr::get($attrs, 'disableds')); // TODO: OR url_suggest
    $options = is_array_assoc($options) ? $options : array_combine(array_map(fn($o) => Str::lower($o), $options), $options);
@endphp

<div class="form-group f-select2-wrap {{ $attrs['class_wrap'] ?? null }}"
     @if(!empty($attrs['hidden_wrap'])) hidden @endif>
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <label for="{{ $field_name_input }}">{!! $label !!}</label>
    @endif

    <select
        name="{{ $field_name_input }}"
        data-name="{{ Str::replaceLast('[]', '', $name) }}"
        class="form-control f-select2 select2-hidden-accessible @isset($attrs['map']) js-map-blocks @endisset {{ $attrs['class'] ?? '' }} @error($name) is-invalid @enderror"
        style="width: 100%;"
        autocomplete="off"
        data-toggle="tooltip"
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    @if(isset($attrs['multiple']) && $attrs['multiple'] && (empty($attrs['max']) || $attrs['max'] > 0))
        multiple
    @endif
    @if(!empty($attrs['url_suggest']))
        data-url-suggest={{$attrs['url_suggest']}}
    @endif
    @if(!empty($attrs['url_save']))
        data-url-save={{$attrs['url_save']}}
    @endif
    @if(!empty($attrs['method_save']))
        data-method-save={{$attrs['method_save']}}
    @endif
    @isset($attrs['close_on_select'])
        data-close-on-select={{$attrs['close_on_select']}}
    @endisset
    @if(!empty($attrs['max']))
        data-max={{$attrs['max']}}
    @endif
    @if(!empty($attrs['url_tags']))
        data-url-tags={{$attrs['url_tags']}}
    @endif
    @if(!empty($attrs['new_tag_label']))
        data-new-tag-label={{$attrs['new_tag_label']}}
    @endif
    @if(!empty($attrs['separators']))
        data-separators={{$attrs['separators']}}
    @endif
    style="width: 100%;"
    @if(count($options) < 6 && empty($attrs['url_suggest']))
        data-minimum-results-for-search="-1"
    @endif {{-- TODO --}}
    @if(isset($attrs['map']) && is_array($attrs['map']))
        data-map='@json($attrs['map'])'
    @endif
    @if(isset($attrs['data']) && is_array($attrs['data']))
        @foreach($attrs['data'] as $dataKey => $dataVal)
            data-{{$dataKey}}="{{$dataVal}}"
        @endforeach
    @endif
    @empty($attrs['id'])
        id="{{ $field_name_input }}"
    @endempty
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    >
    @if(empty($attrs['multiple']) && empty($attrs['empty_value']) && count($options) > 6)
        <option value="" disabled selected> ---</option>
    @endif

    @if(isset($attrs['empty_value']) && empty($attrs['multiple']))
        <option value="" selected> {{ $attrs['empty_value'] }} </option>
    @endisset

    @if($options)
        @foreach($options as $value => $title)
            <option value="{{ $value }}"
                    @if(in_array($value, $selected)) selected @endif
                    @if(in_array($value, $disableds)) disabled="disabled" @endif
            >{{ $title }}</option>
        @endforeach
    @else
        @foreach($selected as $value => $title)
            <option value="{{ $value }}" selected>{{ $title }}</option>
        @endforeach
    @endif

    </select>

    @error($name)<div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])
        <span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>
    @endisset
</div>
