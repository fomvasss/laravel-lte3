@php
    if ($path && is_array($path)) $attrs['multiple'] = true;
    $multimpe = $attrs['multiple'] ?? false;
    $input_name = !empty($attrs['multiple']) ? (Str::replaceLast('[]', '', $name) . '[]') : Str::replaceLast('[]', '', $name);
    $input_deleted_name = !empty($attrs['name_deleted']) ? $attrs['name_deleted'] : (Str::replaceLast('[]', '', $name) . '_deleted');
    $input_deleted_name = !empty($attrs['multiple']) ? (Str::replaceLast('[]', '', $input_deleted_name) . '[]') : Str::replaceLast('[]', '', $input_deleted_name);
    $input_weight_name = !empty($attrs['name_weight']) ? $attrs['name_weight'] : (Str::replaceLast('[]', '', $name) . '_weight');
    $paths = $path ? Arr::wrap($path) : [];
    $readonly = !isset($attrs['readonly']) || !empty($attrs['readonly']);
@endphp

<div class="form-group f-wrap f-lfm {{ $attrs['class_wrap'] ?? null }}"
     @if(!empty($attrs['hidden_wrap'])) hidden @endif
     data-lfm-category="{{ $attrs['lfm_category'] ?? 'file' }}"
     data-is-image="{{ $attrs['is_image'] ?? 0 }}"
     data-field-name="{{$name}}"
     data-trim-host="{{ $attrs['trim_host'] ?? 0 }}"
>
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <label for="{{ $input_name }}">{!! $label !!}</label>
    @endif
    @if($multimpe) <a href="#" class="btn btn-success btn-xs js-btn-add"><i class="fas fa-plus"></i></a> @endif

    <table class="table table-sm" style="position: relative;">
        <tbody class="f-wrap-items @if($multimpe) sortable-y @endif">
        @forelse($paths as $path)
            <tr class="f-wrap-item">
                <td class="align-middle">
                    <div class="input-group">
                        <input class="form-control js-lfm-input"
                               name="{{$input_name}}"
                               type="text"
                               value="{{$path}}"
                               @if($readonly) readonly @endif
                                @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
                                {{$key}}="{{$val}}"
                                @endforeach
                        >
                        <div class="input-group-append">
                            <span class="btn btn-info btn-flat f-lfm-btn">Browse</span>
                        </div>
                    </div>
                </td>
                <td style="width: 15%;" class="preview-block">
                    @empty($attrs['is_image'])
                        <a href="{{ $path }}" target="_blank">
                            {{ Str::substr($path, -4) }}
                        </a>
                    @else
                        <a href="{{ $path }}" target="_blank" class="js-popup-image">
                            <img src="{{ $path }}" style="height: 60px">
                        </a>
                    @endempty
                </td>

                <td class="align-middle" style="width: 5%;">
                    @if($multimpe)
                        <a href="#" class="btn btn-danger btn-xs js-btn-delete" data-id="{{ $path }}"><i class="fas fa-times"></i></a>
                        {{--<input name="{{ $input_deleted_name }}" class="js-input-delete" value="" type="hidden">--}} {{--TODO--}}
                    @else
                        <a href="#" class="btn btn-warning btn-xs js-btn-clear"><i class="fas fa-broom"></i></a>
                    @endif
                </td>
            </tr>
        @empty
            @if($multimpe)
                <tr class="f-wrap-item"></tr>
            @else
                <tr class="f-wrap-item">
                    <td class="align-middle">
                        <div class="input-group">
                            <input class="form-control js-lfm-input"
                                name="{{$input_name}}"
                                type="text"
                                @if($readonly) readonly @endif
                                @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
                                {{$key}}="{{$val}}"
                                @endforeach
                            >
                            <div class="input-group-append">
                                <span class="btn btn-info btn-flat f-lfm-btn">Browse</span>
                            </div>
                        </div>
                    </td>
                    <td style="width: 15%;" class="preview-block"></td>
                    <td class="align-middle" style="width: 5%;">
                        @if($multimpe)
                            <a href="#" class="btn btn-danger btn-xs js-btn-delete"><i class="fas fa-times"></i></a>
                        @else
                            <a href="#" class="btn btn-warning btn-xs js-btn-clear"><i class="fas fa-broom"></i></a>
                        @endif
                    </td>
                </tr>
            @endif
        @endforelse
        </tbody>
    </table>


    @error($name) <div class="error invalid-feedback" style="display: inline;"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<div ><small>{!! $attrs['help'] !!}</small></div>@endisset
</div>


