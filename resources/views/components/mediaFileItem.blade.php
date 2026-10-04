{{-- Видима частина рядка файлу для lte3::components.mediaFile — спільна для наявного ($url) і нового файлу (шаблон для скрипта).
     Змінні батьківського шаблону: $multiple, $isImage, $withMain, $fields, $fileIcon; для наявного — $media, $thumb; $editable — чи є кнопка властивостей --}}
<span class="f-media-handle" title="{{ __('Drag to reorder') }}"><i class="fas fa-grip-vertical"></i></span>
@if($withMain)
    <button type="button" class="f-media-star js-media-main" title="{{ __('Main') }}"><i class="far fa-star"></i></button>
@endif

@if($url)
    <a href="{{ $url }}" target="_blank" class="f-media-thumb {{ $thumb ? 'js-popup-image' : '' }}" title="{{ $fileName }}">
        @if($thumb)
            <img src="{{ $thumb }}" alt="{{ $media->getCustomProperty('alt') ?: $fileName }}" loading="lazy">
        @else
            <i class="far {{ $fileIcon($media->mime_type, $media->file_name) }}"></i>
        @endif
    </a>
@else
    <span class="f-media-thumb"><i class="far fa-file"></i></span>
@endif

<div class="f-media-meta">
    @if($url)
        <a href="{{ $url }}" target="_blank" class="f-media-name" title="{{ $fileName }}">{{ $fileName }}</a>
    @else
        <span class="f-media-name"></span>
    @endif
    <small class="text-muted"><span class="f-media-meta-text">{{ $meta }}</span> <span class="f-media-new-label text-success font-weight-bold">· {{ __('New') }}</span></small>
    @if($fields->isNotEmpty())
        <small class="text-muted f-media-caption"></small>
    @endif
</div>

<div class="f-media-badges">
    @if($isImage)
        <span class="badge badge-success f-media-new-label">{{ __('New') }}</span>
    @endif
    <span class="badge badge-warning f-media-replaced">{{ __('Will be replaced') }}</span>
    @if($fields->contains('name', 'alt'))
        <span class="badge badge-danger f-media-noalt">{{ __('No alt') }}</span>
    @endif
</div>

<div class="f-media-actions">
    @if(!$multiple)
        <button type="button" class="btn btn-xs {{ $isImage ? 'btn-light' : 'btn-default' }} js-media-replace" title="{{ __('Replace') }}"><i class="fas fa-sync-alt"></i></button>
    @endif
    @if($editable)
        <button type="button" class="btn btn-xs {{ $isImage ? 'btn-light' : 'btn-default' }} js-media-edit" title="{{ __('Edit') }}"><i class="fas fa-pen"></i></button>
    @endif
    @if($url)
        <a href="{{ $url }}" class="btn btn-xs {{ $isImage ? 'btn-light' : 'btn-default' }}" target="_blank" download title="{{ __('Download') }}"><i class="fas fa-download"></i></a>
    @endif
    <button type="button" class="btn btn-xs {{ $isImage ? 'btn-light' : 'btn-default' }} js-media-delete" title="{{ $url ? __('Delete') : __('Remove') }}"><i class="fas {{ $url ? 'fa-trash-alt' : 'fa-times' }}"></i></button>
</div>

@if($url)
    <div class="f-media-deleted">
        <span>{{ __('Will be deleted') }}</span>
        <button type="button" class="btn btn-xs btn-light js-media-restore">{{ __('Restore') }}</button>
    </div>
@endif
