{{-- Картка обраного файлу для lte3::components.lfmFile — і для збереженого значення, і шаблоном для скрипта ($url порожній) --}}
<div class="f-media-item">
    <a href="{{ $url }}" target="_blank" class="f-media-thumb {{ $thumb ? 'js-popup-image' : '' }}" title="{{ $fileName }}">
        @if($thumb)
            <img src="{{ $thumb }}" alt="" loading="lazy">
        @else
            <i class="far {{ $icon }}"></i>
        @endif
    </a>
    <div class="f-media-meta">
        <a href="{{ $url }}" target="_blank" class="f-media-name" title="{{ $fileName }}">{{ $fileName }}</a>
        <small class="text-muted f-media-path" title="{{ $url }}">{{ $url }}</small>
    </div>
    <div class="f-media-actions">
        <button type="button" class="btn btn-xs btn-light js-lfm-pick" title="{{ __('Replace') }}"><i class="fas fa-sync-alt"></i></button>
        <a href="{{ $url }}" target="_blank" class="btn btn-xs btn-light js-lfm-open" title="{{ __('Open') }}"><i class="fas fa-external-link-alt"></i></a>
        <button type="button" class="btn btn-xs btn-light js-lfm-clear" title="{{ __('Clear') }}"><i class="fas fa-times"></i></button>
    </div>
</div>
