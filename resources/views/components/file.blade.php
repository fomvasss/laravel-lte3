@php
    if ($path && is_array($path)) $attrs['multiple'] = true;
    $multimpe = $attrs['multiple'] ?? false;
    $input_name = !empty($attrs['multiple']) ? (Str::replaceLast('[]', '', $name) . '[]') : Str::replaceLast('[]', '', $name);
    $input_deleted = !empty($attrs['name_deleted']) ? $attrs['name_deleted'] : (Str::replaceLast('[]', '', $name) . '_deleted');
    $input_deleted = !empty($attrs['multiple']) ? (Str::replaceLast('[]', '', $input_deleted) . '[]') : Str::replaceLast('[]', '', $input_deleted);
    $input_weight_name = !empty($attrs['name_weight']) ? $attrs['name_weight'] : (Str::replaceLast('[]', '', $name) . '_weight');
    $paths = $path ? Arr::wrap($path) : [];
@endphp

<div class="form-group f-wrap f-file {{ $attrs['class_wrap'] ?? null }}" @if(!empty($attrs['hidden_wrap'])) hidden @endif>
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <label for="{{ $input_name }}">{!! $label !!}</label>
    @endif
    <div class="custom-file">
        <input
            class="custom-file-input js-files-input @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
            name="{{ $input_name }}"
            type="file"
            @if($multimpe) multiple @endif
            data-toggle="tooltip"
            @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
                {{$key}}="{{$val}}"
            @endforeach
        >
        <label class="custom-file-label">Choose file</label>
    </div>
    <div class="text-muted"><small class="js-files-info"></small></div>
    @if(count($paths))
        <table class="table table-sm">
            <thead>
                <tr>
                <th>Path</th>
                <th style="width: 40px">Action</th>
                </tr>
            </thead>
            <tbody @if($multimpe)class="sortable-y" data-input-weight-class="js-input-weight"@endif>
                @foreach($paths as $path)
                <tr class="f-file-item">

                    <td>{{ '...' . Str::substr($path, -50) }}</td>
                    <td>
                        <a href="{{$path}}" class="btn btn-info btn-xs" target="_blank"><i class="fas fa-download"></i></a>
                        <a href="#" class="btn btn-danger btn-xs js-btn-delete" data-id="{{ $path }}"><i class="fas fa-times"></i></a>

                        <input name="{{ $input_deleted }}" class="js-input-delete" value="" type="hidden">
                        <input name="{{ $input_weight_name }}[{{ $path }}]" class="js-input-weight"
                               value="{{ $loop->index }}" type="hidden">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div><small>Files not loaded.</small></div>
    @endif
    @error($name) <div class="error invalid-feedback" style="display: inline;"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<div ><small>{!! $attrs['help'] !!}</small></div>@endisset
</div>


