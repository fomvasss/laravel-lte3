@php
    $label = isset($attrs['label']) ? $attrs['label'] : '[------]';
@endphp

<a href="#"
   class="f-x-editable {{ $attrs['class'] ?? null }} {{ $attrs['class_wrap'] ?? null }}"
   data-value="{{ $value }}"
   data-type="{{ isset($attrs['type']) ? $attrs['type'] : 'text' }}"
   data-name="{{ $name }}"
   @isset($attrs['mode'])data-mode="{{$attrs['type']}}"@endisset
   @isset($attrs['inputclass'])data-mode="{{$attrs['inputclass']}}"@endisset
   @isset($attrs['viewformat'])data-viewformat="{{$attrs['viewformat']}}"@endisset
   @isset($attrs['source'])data-source="{{ json_encode($attrs['source']) }}"@endisset
   @isset($attrs['url_save'])data-url="{{ $attrs['url_save'] }}"@endisset
   data-pk="@isset($attrs['pk']){{$attrs['pk']}}@else{{rand(0,10000)}}@endisset"
> {{ $attrs['value_title'] ?? $value ?? $label }}
</a>

{{--
{!! Form::iXEditable('name', $post->name, [
    'type' => 'text',
    'pk' => $post->id,
    'url_save' => '#',
]) !!}
--}}


{{--
 @include('lte::fields.field-x-editable', [
    'value' => $form->data['message'] ?? '[Вопрос]',
    'type' => 'textarea',
    'field_name' => 'data[message]',
    'pk' => $form->id,
    'url' => route('lte.forms.editable', $form),
])
--}}

{{--
@include('lte::fields.field-x-editable', [
    'value' => 1,
    'valueTitle' => 'Отображать',
    'type' => 'select',
    'field_name' => 'data[show_if_empty_filter]',
    'source' => [["value" => "1", "text" => "Отображать"], ["value" => "0", "text" => "Скрывать"]],
    'pk' => $attribute->id,
    'url' => route('lte.shop.attributes.editable', $attribute),
])
--}}

{{-- See more: https://vitalets.github.io/x-editable/docs.html --}}
