@php
    if (isset($selected)) {

    } elseif (!empty($model)) {
        $selected = $model->{$name};
    } elseif (old($name)) {
        $selected = old($name);
    } else {
        $selected = \Arr::get($attrs, 'default');
    }
    $selected = !is_null($selected) ? \Arr::wrap($selected) : [];
    $old = old($name) ? Arr::wrap(old($name)) : [];
    $selected = array_merge($old, $selected);

    $disableds = \Arr::wrap(\Arr::get($attrs, 'disableds'));
    $readonlys = \Arr::wrap(\Arr::get($attrs, 'readonlys'));

    $unchecked_value = isset($attrs['unchecked_value']) ? $attrs['unchecked_value'] : 0;
    $raw_name = $attrs['raw_name'] ?? Str::replaceLast('[]', '', $name);
    $is_simple = \Illuminate\Support\Arr::get($attrs, 'is_simple') ? true : false;

    $field_id_prefix = $attrs['field_id_prefix'] ?? '';
@endphp

<div class="form-group {{ $attrs['class_wrap'] ?? null }}" @if(!empty($attrs['hidden_wrap'])) hidden @endif @if($title = Arr::get($attrs, 'title')) data-toggle="tooltip" title="{{$title}}" @endif>
    @if($unchecked_value !== '')
        <input type="hidden" name="{{ $name }}" value="{{ $unchecked_value }}">
    @endif

    @if($label = Arr::get($attrs, 'label'))
        <label>{!! $label !!}</label>&nbsp;
    @endif

    @foreach($options ?? [] as $key => $val)
        @php
            $label = is_array($val) ? (Arr::get($val, 'label') ?? Arr::get($val, 'name') ?? Arr::get($val, 'title') ?? $val) : $val;
            $url = is_array($val) ? Arr::get($val, 'url') ?? '' : '';
            $disabled = is_array($val) ? Arr::get($val, 'disabled') : false;
            $value = is_array($val) ? (Arr::get($val, 'id') ?? Arr::get($val, 'slug') ?? Arr::get($val, 'key') ?? Arr::get($val, 'value') ?: $val) : $val;
            $id = "_{$field_id_prefix}_{$name}_{$loop->index}";
        @endphp
    <div class="@if($is_simple) form-check @else custom-control @endif {{ $attrs['class_control'] ?? null }}">

        <input class="@if($is_simple) form-check-input @else custom-control-input @endif @if(!empty($attrs['url_save'])) f-checkbox-ajax @endif @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
               data-toggle="tooltip"
               type="checkbox"
               name="{{ $name }}[]"
               value="{{ $value }}"
               @if(Arr::get($val, 'disabled') || in_array($value, $disableds)) disabled @endif
               @if(Arr::get($val, 'readonly') || in_array($value, $readonlys)) onclick="return false;" @endif

               @if($value !== $unchecked_value && in_array($value, $selected)) checked @endif
               data-raw-name="{{$raw_name}}"
{{--TODO--}}
               @if(!empty($attrs['method_save'])) data-method-save="{{$attrs['method_save']}}" @endif
               data-format="{{ isset($attrs['format']) && $attrs['format'] === 'name,value' ? 'name,value' : 'name=value' }}"
               @if(!empty($attrs['url_save'])) data-url-save={{$attrs['url_save']}} @endif
               id="{{$id}}"
                @foreach(Arr::get($val, 'attrs') ?? [] as $key => $val)
                    {{$key}}="{{$val}}"
                @endforeach
        >
        @if($label !== '')
            <label for="{{ $id }}" class="@if($is_simple) form-check-label @else custom-control-label @endif">{!! $label !!}</label>
        @endif
    </div>
    @endforeach

    @error($name) <p class="error invalid-feedback" style="display: inline;">{{ $message }}</p> @enderror
    @isset($attrs['help'])<p style="width: 100%;"><small>{!! $attrs['help'] !!}</small></p>@endisset
</div>
