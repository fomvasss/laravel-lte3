<div class="input-group form-group js-verification-slug-field {{ $attrs['class_wrap'] ?? null }}" @if(!empty($attrs['hidden_wrap'])) hidden @endif>
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <div style="width: 100%;"><label for="{{ $name }}">{!! $label !!}</label></div>
    @endif

    @if($model || $value)
        <input type="text"
               class="form-control js-slug-field-input @error($name) is-invalid @enderror @isset($attrs['class']) {{ $attrs['class'] }} @endisset"
               name="{{ $name }}"
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
                    <input name="{{ $name.'_change' }}" value="1" class="js-slug-field-change"
                           @if(old($name . '_change')) checked @endif type="checkbox">
            </span>
        </div>

    @else

        <input type="text"
               class="form-control js-slug-field-input @error($name) is-invalid @enderror"
               name="{{ $name }}"
               value="{{ $value }}"
               data-toggle="tooltip"
        @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
            {{$key}}="{{$val}}"
        @endforeach
        >
        <div class="input-group-append">
            <span class="input-group-text">
                <!-- <input name="{{ $attrs['checkbox']['name'] ?? '' }}" value="0" checked type="hidden" > -->
                    <input name="{{ $name.'_change' }}" value="1" class="js-slug-field-change"
                           @if(old($name . '_change')) checked @endif type="checkbox">
            </span>
        </div>

    @endif
    @error($name)
    <div class="invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>
