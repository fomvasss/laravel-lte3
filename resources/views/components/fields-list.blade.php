@php
    // Повторювана група полів (type=list) на mb-blocks: два рівні вкладеності, як і в самого mb-blocks
    $placeholder = ['$i', '$j'][$depth] ?? throw new \InvalidArgumentException("Lte3::fields: list '{$field['name']}' is nested deeper than two levels");
    $rows = old($dot, $value);
    $rows = is_array($rows) ? array_values(array_filter($rows, 'is_array')) : [];
    $label = $field['label'] ?? \Illuminate\Support\Str::headline($field['name']);
@endphp
<div class="card mb-wrap {{ $field['class_wrap'] ?? '' }}" data-mb-weight="false"
     @isset($field['min']) data-mb-min="{{ $field['min'] }}" @endisset
     @isset($field['max']) data-mb-max="{{ $field['max'] }}" @endisset>
    <div class="card-header">
        <h3 class="card-title">{!! $label !!}@if(!empty($field['required'])) <span class="text-danger">*</span>@endif</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-sm btn-outline-primary mb-btn-add"><i class="fa fa-plus"></i> {{ $field['add_label'] ?? 'Add' }}</button>
        </div>
    </div>
    <div class="card-body">
        @isset($field['help'])
            <p class="text-muted small">{!! $field['help'] !!}</p>
        @endisset
        <div class="mb-items">
            <template class="mb-template" data-mb-placeholder="{{ $placeholder }}">
                @include('lte3::components.fields-list-item', ['index' => $placeholder, 'row' => []])
            </template>
            @forelse($rows as $index => $row)
                @include('lte3::components.fields-list-item', compact('index', 'row'))
            @empty
                <p class="mb-empty">{{ $field['empty_label'] ?? 'No items added' }}</p>
            @endforelse
        </div>
        @error($dot)<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
