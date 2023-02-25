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
        <table class="table table-striped table-sm" style="position:relative;">
            <tbody class="sortable-y">
                @forelse($items as $item)
                <tr class="item first">
                    <td>
                        <span><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></span>
                    </td>
                    <td>
                        <div class="input-group input-group-md">
                            <div class="input-group-btn" style="width: 88%">
                                <input type="text" name="{{ $name }}[]" value="{!! $item ?? '' !!}" class="form-control" placeholder="{{ $placeholder_value .' '. $loop->iteration }}">
                            </div>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-flat">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="item first">
                    <td>
                        <span><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></span>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-btn" style="width: 88%">
                                 <input type="text" class="form-control" name="{{ $name}}[]" placeholder="{{ $placeholder_value }}">
                            </span>
                            </span>
                                <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" disabled class="btn btn-danger btn-flat">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </span>
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

