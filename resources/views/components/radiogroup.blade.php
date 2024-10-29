@php
    if (isset($selected)) {
        //
    } elseif (!empty($model)) {
        $selected = $model->{$name};
    } elseif (old($name, $selected)) {
        $selected = old($name, $selected);
    } elseif (isset($attrs['default'])) {
        $selected = $attrs['default'];
    } else {
        $selected = array_key_first($options);
    }
@endphp

<div class="form-group f-radiogroup {{ $attrs['class_wrap'] ?? null }}" @if(!empty($attrs['hidden_wrap'])) hidden @endif>

    @if($label = Arr::get($attrs, 'label'))
        <label>{!! $label !!}</label>&nbsp;
    @endif

    @foreach($options ?: [] as $value => $val)
        @php
            $label = is_array($val) ? $val['label'] ?? $val : $val;
            $url = is_array($val) ? $val['url'] ?? '' : '';
            $disabled = is_array($val) ? Arr::get($val, 'disabled') : false;
        @endphp
        <div class="custom-control custom-radio">
            <input class="custom-control-input @isset($attrs['map']) js-map-blocks @endisset @if(isset($attrs['submit_method']) && $url) js-radio-submit @endif @error($name) is-invalid @enderror"
                   name="{{ $name }}"
                   value="{{$value}}"
                   id="{{ $name.$loop->index }}"
                   type="radio"
                   @isset($attrs['submit_method'])data-method={{$attrs['submit_method']}}@endisset
                   @if(Arr::get($attrs, 'disabled') || $disabled) disabled @endif
                   @if($url)data-url={{$url}}@endif
                   @if($selected == $value) checked @endif
                   @if(isset($attrs['map']) && is_array($attrs['map']))
                       data-map='@json($attrs['map'])'
                   @endif
            @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
                {{$key}}="{{$val}}"
            @endforeach
            @foreach(Arr::get($attrs, 'attrs') ?? [] as $key => $val)
                {{$key}}="{{$val}}"
            @endforeach
            @if(isset($attrs['data']) && is_array($attrs['data']))
                @foreach($attrs['data'] as $dataKey => $dataVal)
                    data-{{$dataKey}}="{{$dataVal}}"
                @endforeach
            @endif
            >
            <label class="custom-control-label" for="{{ $name.$loop->index }}">{{ $label }}</label>
        </div>
    @endforeach
    @error($name) <span class="error invalid-feedback" style="display: inline;">{{ $message }}</span> @enderror
    @isset($attrs['help'])
        <div style="width: 100%;"><small>{!! $attrs['help'] !!}</small></div>@endisset
</div>
