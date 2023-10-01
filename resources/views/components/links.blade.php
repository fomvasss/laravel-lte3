@php
    $field_laravel_name = trim(preg_replace('/[\]\[]/', '.', $name), '.');
    $items = $items ?? [];
    $key_key = $key_key ?? 'key';
    $key_value = $key_value ?? 'value';
    $placeholder_key = $placeholder_key ?? 'Key';
    $placeholder_value = isset($placeholder_value) ? $placeholder_value : 'Value';
    $input_type_key = $input_type_key ?? 'text';
    $input_type_value = $input_type_value ?? 'text';
@endphp

<div class="form-group f-wrap f-links {{$attrs['class'] ?? null}}"
     data-field-name="{{ $name }}"
     data-key="{{ $key_key }}"
     data-value="{{ $key_value }}"
     data-placeholder-key="{{ $placeholder_key }}"
     data-placeholder-value="{{ $placeholder_value }}"
     @if(!empty($attrs['hidden_wrap'])) hidden @endif
>

    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <label class="form-label" for="{{ $name }}">{!! $label !!}</label>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-sm" style="position: relative">
            <tbody class="sortable-y">
            @forelse($items as $item)
                <tr class="item first">
                    <td class="align-middle text-center">
                        <i class="fa fa-arrows-alt-v"></i>
                    </td>
                    <td class="w-100">
                        <div class="input-group input-group-sm">
                            <input name="{{ $name }}[{{$loop->index}}][{{$key_key}}]"
                                   value="{{ $item[$key_key] ?? '' }}" placeholder="{{ $placeholder_key }}"
                                   type="{{$input_type_key}}" @isset ($item['safe']) readonly
                                   @endisset class="form-control">
                            <input name="{{ $name }}[{{ $loop->index }}][{{ $key_value }}]"
                                   value="{!! $item[$key_value] ?? '' !!}" type="{{$input_type_value}}"
                                   class="form-control" placeholder="{{ $placeholder_value }}">
                            <input type="hidden" name="{{ $name }}[{{ $loop->index }}][safe]" value="1"
                                   @empty($item['safe']) disabled @endisset>
                            <span class="input-group-append">
                            <button type="button" class="btn btn-success btn-flat js-btn-add"><i
                                        class="fas fa-plus"></i></button>
                            <button type="button" @isset ($item['safe']) disabled
                                    @endisset class="btn btn-danger btn-flat js-btn-delete"><i class="fas fa-minus"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="item first">
                    <td class="align-middle text-center">
                        <i class="fa fa-arrows-alt-v"></i>
                    </td>
                    <td class="w-100">
                        <div class="input-group input-group-sm">
                            <input name="{{ $name}}[0][{{$key_key}}]" placeholder="{{ $placeholder_key }}"
                                   type="{{ $input_type_key }}" class="form-control">
                            <input name="{{ $name }}[0][{{ $key_value }}]" class="form-control"
                                   placeholder="{{ $placeholder_value }}" type="{{ $input_type_value }}">
                            <input type="hidden" name="{{ $name }}[{{ $loop->index }}][safe]" value="0">
                            <span class="input-group-append">
                            <button type="button" class="btn btn-success btn-flat js-btn-add"><i
                                        class="fas fa-plus"></i></button>
                            <button type="button" @isset ($item['safe']) disabled
                                    @endisset class="btn btn-danger btn-flat js-btn-delete"><i class="fas fa-minus"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @error($field_laravel_name)
    <div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>

