{{-- Поле файлів Spatie MediaLibrary (Lte3::mediaFile / Lte3::mediaImage). Стилі — main.css, поведінка — main.js (initMediaFile). Формат полів для fomvasss/laravel-medialibrary-extension —
     атрибут 'format' або lte3.view.media.format:
       legacy (за замовчуванням, як у старому полі): name[] | name, name_deleted[], name_weight[id], name_custom[id][prop];
         властивості нового файлу — лише в одиночному полі (name_custom[new][prop]), порядок — лише збережених
       expand (MediaManager::saveExpand, потрібен пакет ≥ 6.4.1 для колекції `files`) — рядок на файл, наявний чи новий:
         multiple: name[N][id] | name[N][file], [weight], [delete], [is_main], [<властивість>]
         single:   name[id] | name[file], [delete], [<властивість>]
         властивості пишуться лише ті, що є в media-library-extension.expand.allowed_custom_properties --}}
@php
    $multiple = !empty($attrs['multiple']);
    $isImage = !empty($attrs['is_image']);
    $expand = (Arr::get($attrs, 'format') ?: config('lte3.view.media.format', 'legacy')) === 'expand';
    $withMain = $expand && $multiple && !empty($attrs['main']);
    $base = Str::replaceLast('[]', '', $name);
    $collection_name = !empty($attrs['collection']) ? $attrs['collection'] : $base;
    $accept = Arr::get($attrs, 'accept') ?: ($isImage ? 'image/*' : null);
    // підпис під зоною перетягування: "image/*,.pdf,application/vnd.ms-excel" → "Зображення, PDF, XLS"
    $acceptLabel = $accept === 'image/*' ? __('Images only') : ($accept ? __('Allowed: :types', ['types' => collect(explode(',', $accept))
        ->map(fn ($type) => trim($type))->filter()
        ->map(fn ($type) => match (true) {
            $type === 'image/*' => __('Images'),
            $type === 'video/*' => __('Video'),
            $type === 'audio/*' => __('Audio'),
            str_starts_with($type, '.') => strtoupper(substr($type, 1)),
            default => strtoupper(\Symfony\Component\Mime\MimeTypes::getDefault()->getExtensions($type)[0] ?? $type),
        })->unique()->implode(', ')]) : null);
    $disabled = (bool) Arr::get($attrs, 'disabled');
    $hasMediaModel = !empty($model) && $model instanceof \Spatie\MediaLibrary\HasMedia;
    $items = $hasMediaModel ? $model->getMedia($collection_name) : collect();
    $uid = 'f-media-' . Str::random(8);
    // розмір плитки картинки в px (сітка mediaImage) — атрибут thumb_size або lte3.view.media.thumb_size
    $thumbSize = (int) ($attrs['thumb_size'] ?? config('lte3.view.media.thumb_size', 110));

    // custom_properties у форматі Lte3::field: 'alt' | 'alt' => 'Підпис' | ['name' => 'alt', 'label' => ..., 'type' => ...]
    $fields = collect(Arr::wrap($attrs['custom_properties'] ?? []))->map(function ($field, $key) {
        $field = is_array($field) ? $field : (is_int($key) ? ['name' => $field] : ['name' => $key, 'label' => $field]);
        return $field + ['label' => __(Str::ucfirst($field['name'])), 'type' => 'text'];
    })->values();
    $notAllowed = $expand ? $fields->pluck('name')->diff(config('media-library-extension.expand.allowed_custom_properties', [])) : collect();

    // імена legacy-формату — як у lte3::components.mediaFile, з тими самими атрибутами name_deleted/name_weight/name_custom
    $deletedName = Str::replaceLast('[]', '', $attrs['name_deleted'] ?? "{$base}_deleted") . ($multiple ? '[]' : '');
    $weightName = $attrs['name_weight'] ?? "{$base}_weight";
    $customName = $attrs['name_custom'] ?? "{$base}_custom";
    // ім'я поля файлу: наявного ($mediaId, позиція $index) чи нового ($index = '__N__')
    $fieldName = function (string $key, $index, $mediaId = null) use ($expand, $multiple, $base, $deletedName, $weightName, $customName) {
        if ($expand) {
            return $multiple ? "{$base}[{$index}][{$key}]" : "{$base}[{$key}]";
        }

        return match ($key) {
            'file' => $multiple ? "{$base}[]" : $base,
            'delete' => $deletedName,
            'weight' => "{$weightName}[{$mediaId}]",
            default => "{$customName}[" . ($mediaId ?? 'new') . "][{$key}]",
        };
    };
    // властивості нового файлу legacy вміє лише в одиночному полі: бекенд віддає їх щойно збереженому медіа
    $newEditable = $fields->isNotEmpty() && ($expand || !$multiple);
    $fileIcon = function (?string $mime, ?string $fileName): string {
        $ext = strtolower(pathinfo((string) $fileName, PATHINFO_EXTENSION));
        return match (true) {
            str_starts_with((string) $mime, 'image/') => 'fa-file-image',
            str_starts_with((string) $mime, 'video/') => 'fa-file-video',
            str_starts_with((string) $mime, 'audio/') => 'fa-file-audio',
            $ext === 'pdf' => 'fa-file-pdf',
            in_array($ext, ['doc', 'docx', 'odt', 'rtf']) => 'fa-file-word',
            in_array($ext, ['xls', 'xlsx', 'ods', 'csv']) => 'fa-file-excel',
            in_array($ext, ['ppt', 'pptx', 'odp']) => 'fa-file-powerpoint',
            in_array($ext, ['zip', 'rar', '7z', 'tar', 'gz']) => 'fa-file-archive',
            in_array($ext, ['json', 'xml', 'html', 'js', 'css', 'php', 'sql']) => 'fa-file-code',
            in_array($ext, ['txt', 'md', 'log']) => 'fa-file-alt',
            default => 'fa-file',
        };
    };
@endphp

<div class="card card-default f-wrap f-media {{ $attrs['class_wrap'] ?? null }}" id="{{ $uid }}" style="--f-media-thumb: {{ $thumbSize }}px"
     data-multiple="{{ (int) $multiple }}" data-image="{{ (int) $isImage }}" data-expand="{{ (int) $expand }}" data-next="{{ $items->count() }}"
     data-rejected-text="{{ __('Not allowed: :files.', ['files' => ':files']) }}">
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <div class="card-header">
            <h3 class="card-title">{!! $label !!}</h3>
            @if($multiple && $items->count())
                <div class="card-tools"><span class="badge badge-light">{{ $items->count() }}</span></div>
            @endif
        </div>
    @endif
    <div class="card-body">
        @if(!empty($model) && !$hasMediaModel)
            <div><code>Model {{ get_class($model) }} must implements \Spatie\MediaLibrary\HasMedia</code></div>
        @endif
        @if($notAllowed->isNotEmpty())
            <div><code>{{ $notAllowed->implode(', ') }}: add to media-library-extension.expand.allowed_custom_properties, otherwise not saved</code></div>
        @endif

        <div class="f-media-items {{ $isImage ? 'f-media-grid' : 'f-media-list' }} {{ $multiple ? 'js-media-sortable' : '' }}">
            @foreach($items as $media)
                @php($thumb = str_starts_with((string) $media->mime_type, 'image/') ? \Fomvasss\Lte3\Support\MediaThumbUrlResolver::resolve($media, $thumbSize) : null)
                <div class="f-media-item {{ $withMain && $media->is_main ? 'is-main' : '' }}" data-name="{{ $media->name }}" data-thumb="{{ $thumb }}" data-icon="{{ $fileIcon($media->mime_type, $media->file_name) }}">
                    @include('lte3::components.mediaFileItem', ['url' => $media->getUrl(), 'fileName' => $media->name, 'meta' => strtoupper(pathinfo($media->file_name, PATHINFO_EXTENSION)) . ' · ' . human_filesize($media->size, 1), 'editable' => $fields->isNotEmpty()])

                    @if($expand)
                        <input type="hidden" name="{{ $fieldName('id', $loop->index) }}" value="{{ $media->id }}">
                    @endif
                    {{-- data-on/off — значення «видалити» / «лишити»: expand 1/0, legacy id/порожньо --}}
                    <input type="hidden" name="{{ $fieldName('delete', $loop->index, $media->id) }}" value="{{ $expand ? 0 : '' }}" class="js-media-delete-input" data-on="{{ $expand ? 1 : $media->id }}" data-off="{{ $expand ? 0 : '' }}">
                    @if($multiple)
                        <input type="hidden" name="{{ $fieldName('weight', $loop->index, $media->id) }}" value="{{ $loop->index }}" class="js-media-weight">
                    @endif
                    @if($withMain)
                        <input type="hidden" name="{{ $fieldName('is_main', $loop->index) }}" value="{{ (int) $media->is_main }}" class="js-media-main-input">
                    @endif
                    @foreach($fields as $field)
                        <input type="hidden" name="{{ $fieldName($field['name'], $loop->parent->index, $media->id) }}" value="{{ $media->getCustomProperty($field['name']) }}" data-prop="{{ $field['name'] }}">
                    @endforeach
                </div>
            @endforeach
        </div>

        <label class="f-media-drop @error($name) is-invalid @enderror {{ $disabled ? 'disabled' : '' }}">
            {{-- без name: кожен обраний файл переноситься у власний input рядка (див. скрипт) --}}
            <input type="file"
                   class="f-media-input {{ $attrs['class'] ?? '' }}"
                   @if($multiple) multiple @endif
                   @if($disabled) disabled @endif
                   @if($accept) accept="{{ $accept }}" @endif>
            <i class="fas {{ $isImage ? 'fa-image' : 'fa-cloud-upload-alt' }} f-media-drop-icon"></i>
            <span>
                <strong>{{ $multiple ? __('Choose files') : __('Choose file') }}</strong>
                {{ __('or drag them here') }}
            </span>
            @if($acceptLabel)
                <small class="text-muted">{{ $acceptLabel }}</small>
            @endif
            {{-- файли, що не підходять під accept, — скрипт пише сюди --}}
            <small class="text-danger f-media-rejected"></small>
        </label>

        @error($name)
            <div class="error invalid-feedback d-block">{{ $message }}</div>
        @enderror
        @isset($attrs['help'])
            <div><small class="text-muted">{!! $attrs['help'] !!}</small></div>
        @endisset
    </div>

    {{-- рядок нового файлу: скрипт клонує шаблон і підставляє індекс замість __N__ --}}
    <template class="f-media-template">
        <div class="f-media-item is-new">
            @include('lte3::components.mediaFileItem', ['url' => null, 'fileName' => '', 'meta' => '', 'editable' => $newEditable])
            <input type="file" name="{{ $fieldName('file', '__N__') }}" class="f-media-file-input" hidden>
            @if($expand && $multiple)
                <input type="hidden" name="{{ $fieldName('weight', '__N__') }}" value="0" class="js-media-weight">
            @endif
            @if($withMain)
                <input type="hidden" name="{{ $fieldName('is_main', '__N__') }}" value="0" class="js-media-main-input">
            @endif
            @if($newEditable)
                @foreach($fields as $field)
                    <input type="hidden" name="{{ $fieldName($field['name'], '__N__') }}" value="" data-prop="{{ $field['name'] }}">
                @endforeach
            @endif
        </div>
    </template>

    @if($fields->isNotEmpty())
        {{-- скрипт переносить вікно в body, поза форму: ці поля не відправляються, значення пишуться в рядок файлу --}}
        <div class="modal fade f-media-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-truncate f-media-modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="f-media-modal-preview"></div>
                        @foreach($fields as $field)
                            {!! Lte3::field(array_merge($field, ['name' => "f_media_prop[{$field['name']}]", 'value' => null, 'id' => "{$uid}-{$field['name']}"])) !!}
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="button" class="btn btn-primary js-media-props-save">{{ __('Done') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
