{{-- Поле файлу Laravel File Manager (Lte3::lfmFile / Lte3::lfmImage). Значення — рядок з URL файлу. Один файл.
     Стилі — main.css (спільні з полем медіа .f-media-*), поведінка — main.js (initLfmFile): File Manager у модалці
     (LFM callback), перетягування заливає файл у File Manager (/upload) --}}
@php
    $isImage = !empty($attrs['is_image']);
    $category = $attrs['lfm_category'] ?? ($isImage ? 'image' : 'file');
    $editable = !empty($attrs['editable']);
    $path = is_array($path) ? Arr::first($path) : $path;
    // як value в інших полях: не передали — береться з old() чи моделі форми (Lte3::formOpen(['model' => ...]))
    $path ??= Lte3::getValueAttribute($name);
    // розмір плитки картинки (як у mediaImage) і її мініатюра — lte3.view.lfm.thumb через резолвер пакета
    $thumbSize = (int) ($attrs['thumb_size'] ?? config('lte3.view.media.thumb_size', 110));
    $thumb = $isImage && $path ? \Fomvasss\Lte3\Support\MediaThumbUrlResolver::resolveUrl($path, $thumbSize) : null;
    $inputName = Str::replaceLast('[]', '', $name);
    // тексти для main.js (статичний) — через data-texts
    $texts = ['manager' => __('File manager'), 'imagesOnly' => __('Images only'), 'uploading' => __('Uploading…'), 'failed' => __('Upload failed')];
    $disabled = (bool) Arr::get($attrs, 'disabled');
    $fileName = $path ? urldecode(basename((string) parse_url($path, PHP_URL_PATH))) : '';
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $icon = match (true) {
        in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'avif']) => 'fa-file-image',
        in_array($ext, ['mp4', 'mov', 'webm', 'avi']) => 'fa-file-video',
        in_array($ext, ['mp3', 'wav', 'ogg']) => 'fa-file-audio',
        $ext === 'pdf' => 'fa-file-pdf',
        in_array($ext, ['doc', 'docx', 'odt', 'rtf']) => 'fa-file-word',
        in_array($ext, ['xls', 'xlsx', 'ods', 'csv']) => 'fa-file-excel',
        in_array($ext, ['zip', 'rar', '7z']) => 'fa-file-archive',
        default => 'fa-file',
    };
@endphp

<div class="form-group f-wrap f-lfm {{ $attrs['class_wrap'] ?? null }}" style="--f-media-thumb: {{ $thumbSize }}px"
     @if(!empty($attrs['hidden_wrap'])) hidden @endif
     data-lfm-category="{{ $category }}"
     data-is-image="{{ (int) $isImage }}"
     data-trim-host="{{ $attrs['trim_host'] ?? 0 }}"
     data-url-save="{{ $attrs['url_save'] ?? '' }}"
     data-lfm-prefix="{{ $attrs['lfm_prefix'] ?? '/filemanager' }}"
     data-lfm-folder="{{ $attrs['lfm_folder'] ?? '' }}"
     data-texts="{{ json_encode($texts) }}">
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <label>{!! $label !!}</label>
    @endif

    <div class="f-lfm-body {{ $path ? 'has-file' : '' }} {{ $disabled ? 'is-disabled' : '' }}">
        <div class="f-media-items {{ $isImage ? 'f-media-grid' : 'f-media-list' }}">
            @if($path)
                @include('lte3::components.lfmFileItem', ['url' => $path, 'fileName' => $fileName, 'icon' => $icon, 'thumb' => $thumb])
            @endif
        </div>

        {{-- без класу f-media-drop: на нього підписане перетягування поля медіа (main.js) --}}
        <div class="f-lfm-pick js-lfm-pick" role="button" tabindex="0">
            <i class="fas {{ $isImage ? 'fa-image' : 'fa-folder-open' }} f-media-drop-icon"></i>
            <span>
                <strong>{{ $isImage ? __('Choose image') : __('Choose file') }}</strong>
                {{ __('or drag it here') }}
            </span>
            <small class="text-muted f-lfm-status"></small>
        </div>
    </div>

    <input type="{{ $editable ? 'text' : 'hidden' }}"
           class="js-lfm-input {{ $editable ? 'form-control form-control-sm mt-2' : '' }} @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
           name="{{ $inputName }}"
           value="{{ $path }}"
           @if($disabled) disabled @endif
           @if($editable)
               @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
                   {{ $key }}="{{ $val }}"
               @endforeach
           @endif>

    <template class="f-lfm-template">
        @include('lte3::components.lfmFileItem', ['url' => '', 'fileName' => '', 'icon' => 'fa-file', 'thumb' => null])
    </template>

    @error($name)
        <div class="error invalid-feedback d-block">{{ $message }}</div>
    @enderror
    @isset($attrs['help'])
        <div><small class="text-muted">{!! $attrs['help'] !!}</small></div>
    @endisset
</div>
