<div class="mb-item" data-mb-idx="{{ $index }}">
    <div class="mb-item-controls">
        <span class="mb-item-num"></span>
        <div class="mb-item-actions">
            <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
            <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
        </div>
    </div>
    {!! Lte3::fields($field['fields'] ?? [], $row, ['prefix' => "{$name}[{$index}]", 'depth' => $depth + 1]) !!}
</div>
