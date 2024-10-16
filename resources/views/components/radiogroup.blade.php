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
        @endphp
        <div class="custom-control custom-radio">
            <input class="custom-control-input @isset($attrs['map']) js-map-blocks @endisset @if(isset($attrs['submit_method']) && $url) js-radio-submit @endif @error($name) is-invalid @enderror"
                   name="{{ $name }}"
                   value="{{$value}}"
                   id="{{ $name.$loop->index }}"
                   @isset($attrs['submit_method'])data-method={{$attrs['submit_method']}}@endisset
                   
                   @if($url)data-url={{$url}}@endif
                   @if($selected == $value) checked @endif
                   @if(isset($attrs['map']) && is_array($attrs['map']))
                       data-map='@json($attrs['map'])'
                   @endif
                   type="radio"
            @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
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
