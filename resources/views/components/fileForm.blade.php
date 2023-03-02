@php
    $input_name = !empty($attrs['multiple']) ? (Str::replaceLast('[]', '', $name) . '[]') : Str::replaceLast('[]', '', $name);
@endphp

{!! Lte3::formOpen([
    'files' => true,
    'action' => $attrs['url_save'] ?? '',
    'method' => $attrs['method'] ?? 'POST'])
!!}

<label class="js-form-submit-file-changed">
    @isset($attrs['html']){!! $attrs['html'] !!} @endisset
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <strong>{!! $label !!}</strong>
    @endif
    <input type="file" class="{{ $attrs['class'] ?? '' }}"
           style="display: none;"
           name="{{ $input_name }}"
           @if(!empty($attrs['multiple'])) multiple @endif
           @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
           {{$key}}="{{$val}}"
            @endforeach
    >
</label>

{!! Lte3::formClose() !!}
