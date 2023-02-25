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

<div class="form-group field-links {{$attrs['class'] ?? null}}"
     data-field-name="{{ $name }}"
     data-key="{{ $key_key }}"
     data-value="{{ $key_value }}"
     data-placeholder-key="{{ $placeholder_key }}"
     data-placeholder-value="{{ $placeholder_value }}"
>

    @isset($attrs['label'])
        <label class="form-label" for="{{ $name }}">{!! $attrs['label'] !!}</label>
    @endisset

    <div class="table-responsive">
        <table class="table table-striped table-sm" style="position: relative">
            <tbody class="sortable-y">
                @forelse($items as $item)
                <tr class="item first">
                    <td>
                        <span><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></span>
                    </td>
                    <td>
                        <div class="input-group input-group-md">
                            <input type="{{$input_type_key}}" class="form-control" @isset ($item['safe']) readonly @endisset name="{{ $name }}[{{$loop->index}}][{{$key_key}}]" value="{{ $item[$key_key] ?? '' }}" placeholder="{{ $placeholder_key }}">
                            <span class="input-group-btn" style="width: 40%">
                                <input type="{{$input_type_value}}" name="{{ $name }}[{{ $loop->index }}][{{ $key_value }}]" value="{!! $item[$key_value] ?? '' !!}" class="form-control" placeholder="{{ $placeholder_value }}">
                                <input type="hidden" name="{{ $name }}[{{ $loop->index }}][safe]" value="1" @empty($item['safe']) disabled @endisset>
                            </span>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" @isset ($item['safe']) disabled @endisset class="btn btn-danger btn-flat">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="item first">
                    <td>
                        <td>
                            <span><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></span>
                        </td>
                    </td>
                    <td>
                        <div class="input-group input-group-md">
                            <input type="{{ $input_type_key }}" class="form-control" name="{{ $name}}[0][{{$key_key}}]" placeholder="{{ $placeholder_key }}">
                            <span class="input-group-btn" style="width: 40%">
                                <input type="{{ $input_type_value }}" name="{{ $name }}[0][{{ $key_value }}]" class="form-control" placeholder="{{ $placeholder_value }}">
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

