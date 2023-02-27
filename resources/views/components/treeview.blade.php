<div
        class="card card-default f-treeview-wrap {{ $attrs['class_wrap'] ?? null }}"
        data-url="{{ $attrs['url_tree'] ?? '' }}"
        data-field-name="{{ $name }}"
        @if(!empty($attrs['data']))
        data-data='@json($attrs["data"], JSON_PRETTY_PRINT)'
        @endif
>

    @isset($attrs['label'])
        <div class="card-header">
            <h3 class="card-title">{!! $attrs['label'] ?: Str::studly($name) !!}</h3>
        </div>
    @endisset
    <div class="card-body">
        <div class="f-treeview-data"></div>
        <div class="f-treeview-inputs"></div>
    </div>
    <div class="overlay dark">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
</div>
