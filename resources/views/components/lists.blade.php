@php
    $field_laravel_name = trim(preg_replace('/[\]\[]/', '.', $name), '.');
    $items = $items ?? [];
    //$items = array_merge(old($field_laravel_name, []), ['qq' => 'Qq', 'ww' => 'Ww']); // TODO
    $placeholder_value = isset($placeholder_value) ? $placeholder_value : 'Value';
@endphp

<div class="form-group field-linear-list {{ $attrs['class'] ?? null }}"
     data-field-name="{{ $name }}"
     data-placeholder-value="{{ $placeholder_value }}"
>
    @isset($attrs['label'])
        <label class="form-label" for="{{ $name }}">{!! $attrs['label'] !!}</label>
    @endisset

    <div class="table-responsive">
        <table class="table table-sm" style="position:relative;">
            <tbody class="sortable-y" >
                @forelse($items as $item)
                <tr class="item first">
                    <td class="align-middle text-center">
                        <i class="fa fa-arrows-alt-v"></i>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input name="{{ $name }}[]" value="{!! $item ?? '' !!}" class="form-control" placeholder="{{ $placeholder_value .' '. $loop->iteration }}" type="text">
                            <span class="input-group-append">
                            <button type="button" class="btn btn-success btn-flat js-btn-add"><i class="fas fa-plus"></i></button>
                            <button type="button" class="btn btn-danger btn-flat js-btn-remove"><i class="fas fa-minus"></i></button></span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="item first">
                    <td class="align-middle text-center">
                        <i class="fa fa-arrows-alt-v"></i>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input name="{{ $name }}[]" value="" class="form-control" placeholder="{{ $placeholder_value }}" type="text">
                            <span class="input-group-append">
                            <button type="button" class="btn btn-success btn-flat js-btn-add"><i class="fas fa-plus"></i></button>
                            <button type="button" class="btn btn-danger btn-flat js-btn-remove"><i class="fas fa-minus"></i></button></span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
    @error($field_laravel_name) <div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>

