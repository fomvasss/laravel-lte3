@php
    $field_laravel_name = trim(preg_replace('/[\]\[]/', '.', $name), '.');
    $items = $items ?? [];
    //$items = array_merge(old($field_laravel_name, []), ['qq' => 'Qq', 'ww' => 'Ww']); // TODO
    $placeholder_value = $attrs['placeholder_value'] ?? 'Value';
    $input_type_value = $attrs['input_type_value'] ?? 'text';
@endphp

<div class="form-group f-wrap f-lists {{ $attrs['class'] ?? null }}"
     data-field-name="{{ $name }}"
     data-placeholder-value="{{ $placeholder_value }}"
     @if(!empty($attrs['hidden_wrap'])) hidden @endif
>
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <label class="form-label" for="{{ $name }}">{!! $label !!}</label>
    @endif

    <div class="table-responsive">
        <table class="table table-sm" style="position:relative;">
            <tbody class="sortable-y">
            @forelse($items as $item)
                <tr class="item first f-item">
                    <td class="align-middle text-center">
                        <i class="fa fa-arrows-alt-v"></i>
                    </td>
                    <td class="w-100">
                        <div class="input-group input-group-sm">
                            <input name="{{ $name }}[]" value="{!! $item ?? '' !!}" class="form-control"
                                   placeholder="{{ $placeholder_value .' '. $loop->iteration }}" type="{{ $input_type_value }}">
                            <span class="input-group-append">
                            <button type="button" class="btn btn-success btn-flat js-btn-add"><i
                                        class="fas fa-plus"></i></button>
                            <button type="button" class="btn btn-danger btn-flat js-btn-delete"><i
                                        class="fas fa-minus"></i></button></span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="item first f-item">
                    <td class="align-middle text-center">
                        <i class="fa fa-arrows-alt-v"></i>
                    </td>
                    <td class="w-100">
                        <div class="input-group input-group-sm">
                            <input name="{{ $name }}[]" value="" class="form-control"
                                   placeholder="{{ $placeholder_value }}" type="text">
                            <span class="input-group-append">
                            <button type="button" class="btn btn-success btn-flat js-btn-add"><i
                                        class="fas fa-plus"></i></button>
                            <button type="button" class="btn btn-danger btn-flat js-btn-delete"><i
                                        class="fas fa-minus"></i></button></span>
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

