@php
    $multimpe = $attrs['multiple'] ?? false;
    $input_name = !empty($attrs['multiple']) ? (Str::replaceLast('[]', '', $name) . '[]') : Str::replaceLast('[]', '', $name);
    $input_deleted_name = !empty($attrs['name_deleted']) ? $attrs['name_deleted'] : (Str::replaceLast('[]', '', $name) . '_deleted');
    $input_deleted_name = !empty($attrs['multiple']) ? (Str::replaceLast('[]', '', $input_deleted_name) . '[]') : Str::replaceLast('[]', '', $input_deleted_name);
    $input_weight_name = !empty($attrs['name_weight']) ? $attrs['name_weight'] : (Str::replaceLast('[]', '', $name) . '_weight');
    //$input_weight_name = !empty($attrs['multiple']) ? (Str::replaceLast('[]', '', $input_weight_name) . '[]') : Str::replaceLast('[]', '', $input_weight_name);
    $collection_name = !empty($attrs['collection']) ? $attrs['collection'] : $name;
@endphp

<div class="card card-default f-wrap f-media-file {{ $attrs['class_wrap'] ?? null }}">
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <div class="card-header">
            <h3 class="card-title">{!! $label !!}</h3>
        </div>
    @endif
    <div class="card-body">
        <div class="btn-group w-100">
            <label class="btn btn-success col">
                <i class="fas fa-plus"></i>
                <span>Choose file</span>
                <input
                        type="file"
                        name="{{$input_name}}"
                        class="js-files-input @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
                        style="display: none;"
                        @if($multimpe) multiple @endif
                        @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
                        {{$key}}="{{$val}}"
                        @endforeach
                >
            </label>
        </div>
        <div class="text-muted"><small class="js-files-info"></small></div>
        @if(!empty($model) && !($model instanceof \Spatie\MediaLibrary\HasMedia))
            <div><code>Model {{ get_class($model) }} must implements \Spatie\MediaLibrary\HasMedia</code></div>
        @elseif(!empty($model) && $model->getMedia($collection_name)->count())
            <table class="table table-sm" style="position: relative;">
                <tbody @if($multimpe)class="sortable-y" data-input-weight-class="js-input-weight"@endif>
                @foreach($model->getMedia($collection_name) as $media)
                    <tr class="f-file-item" id="{{ $media->id }}">
                        <td style="width: 20%;">
                            @empty($attrs['is_image'])
                            <a href="{{ $media->getUrl() }}" target="_blank">
                                {{ $media->mime_type }}
                            </a>
                            @else
                            <a href="{{ $media->getUrl() }}" target="_blank" class="js-popup-image">
                                <img src="{{ $media->getUrl('thumb') }}" alt="{{ $media->name }}">
                            </a>
                            @endempty
                        </td>

                        <td class="align-middle">
                            {{ Str::substr($media->name, -50) }}
                            [{{ human_filesize($media->size, 1) }}]
                        </td>
                        <td class="align-middle" style="width: 10%;">
                            <a href="{{ $media->getUrl() }}" class="btn btn-info btn-xs" target="_blank"><i
                                        class="fas fa-download"></i></a>
                            <a href="#" class="btn btn-danger btn-xs js-btn-delete" data-id="{{ $media->id }}"><i
                                        class="fas fa-times"></i></a>

                            <input name="{{ $input_deleted_name }}" class="js-input-delete" value="" type="hidden">
                            <input name="{{ $input_weight_name }}[{{ $media->id }}]" class="js-input-weight"
                                   value="{{ $loop->index }}" type="hidden">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div><small>Files not loaded.</small></div>
        @endif
        <div>
            @error($name)
            <div class="error invalid-feedback" style="display: inline;"> {{ $message }} </div>@enderror
            @isset($attrs['help'])
                <div><small>{!! $attrs['help'] !!}</small></div>@endisset
        </div>
    </div>
</div>
