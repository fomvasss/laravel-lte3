@php
    $title = $attrs['value_title'] ?? $value ?? $attrs['label'] ?? '[------]';
    $limitEnd = \Illuminate\Support\Arr::get($attrs, 'limit_end', '...');
    $title = ($limit = \Illuminate\Support\Arr::get($attrs, 'limit_title')) ? \Illuminate\Support\Str::limit($title, $limit, $limitEnd) : $title;
@endphp

<a @if(Arr::get($attrs, 'disabled') || Arr::get($attrs, 'readonly')) href="#" @endif
class="f-x-editable {{ $attrs['class'] ?? null }} {{ $attrs['class_wrap'] ?? null }}"
   data-value="{{ $value }}"
@foreach(Arr::only($attrs, $field_attrs) as $key => $val)
    {{$key}}="{{$val}}"
@endforeach
@foreach(Arr::get($attrs, 'attrs') ?? [] as $key => $val)
    {{$key}}="{{$val}}"
@endforeach
data-type="{{ $attrs['data_type'] ?? $attrs['type'] ?? 'text' }}"
data-name="{{ $name }}"
@if(Arr::get($attrs, 'disabled') || Arr::get($attrs, 'readonly')) data-disabled="1" @endif
@isset($attrs['mode']) data-mode="{{$attrs['type']}}" @endisset
@isset($attrs['inputclass'])data-mode="{{$attrs['inputclass']}}" @endisset
@isset($attrs['viewformat'])data-viewformat="{{$attrs['viewformat']}}" @endisset
@isset($attrs['source'])data-source="{{ json_encode($attrs['source']) }}" @endisset
@isset($attrs['url_save'])data-url="{{ $attrs['url_save'] }}" @endisset
data-pk="@isset($attrs['pk']){{$attrs['pk']}}@else{{\Illuminate\Support\Str::random()}}@endisset"
> {{ $title }}
</a>