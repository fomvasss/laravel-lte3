@extends('lte3::layouts.app')

@section('content')
    @include('lte3::parts.content-header', [
        'page_title' => 'Multy Blocks',
        'small_page_title' => 'Example',
    ])

    <!-- Main content -->
    <section class="content">

        {{--
            Block template example — demonstrates all available mb-* patterns and Lte3 fields.
            Does not depend on project-specific backend (no custom models or queries).
            Include assets at the bottom: @include('lte3::partials.mb-assets')
        --}}


        {{-- =====================================================================
             STATIC FIELDS (no multiblock)
             ===================================================================== --}}

        <div class="card">
            <div class="card-header"><h3 class="card-title">Text fields</h3></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">{!! Lte3::text('content[title]', isset($block) ? $block->getContent('title') : null, ['label' => 'Title', 'placeholder' => 'Enter title']) !!}</div>
                    <div class="col-md-4">{!! Lte3::email('content[email]', isset($block) ? $block->getContent('email') : null, ['label' => 'Email', 'placeholder' => 'info@example.com']) !!}</div>
                    <div class="col-md-4">{!! Lte3::url('content[link]', isset($block) ? $block->getContent('link') : null, ['label' => 'URL', 'placeholder' => 'https://']) !!}</div>
                </div>
                <div class="row">
                    <div class="col-md-4">{!! Lte3::number('content[count]', isset($block) ? $block->getContent('count') : null, ['label' => 'Count', 'default' => 10]) !!}</div>
                    <div class="col-md-4">{!! Lte3::text('content[phone]', isset($block) ? $block->getContent('phone') : null, ['label' => 'Phone', 'placeholder' => '+1234567890']) !!}</div>
                    <div class="col-md-4">{!! Lte3::slug('content[slug]', isset($block) ? $block->getContent('slug') : null, ['label' => 'Slug']) !!}</div>
                </div>
                <div class="row">
                    <div class="col-md-6">{!! Lte3::textarea('content[description]', isset($block) ? $block->getContent('description') : null, ['label' => 'Short description', 'rows' => 3]) !!}</div>
                    <div class="col-md-6">{!! Lte3::textarea('content[text]', isset($block) ? $block->getContent('text') : null, ['label' => 'Text', 'rows' => 3]) !!}</div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-header"><h3 class="card-title">Select & toggles</h3></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        {!! Lte3::select2('content[type]', isset($block) ? $block->getContent('type') : null, [
                            'slider' => 'Slider',
                            'grid'   => 'Grid',
                            'list'   => 'List',
                            'banner' => 'Banner',
                        ], ['label' => 'Display type']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Lte3::select2('content[columns]', isset($block) ? $block->getContent('columns') : null, [
                            '2' => '2 columns',
                            '3' => '3 columns',
                            '4' => '4 columns',
                        ], ['label' => 'Columns']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Lte3::colorpicker('content[color]', isset($block) ? $block->getContent('color') : null, ['label' => 'Accent color']) !!}
                    </div>
                </div>

                {!! Lte3::checkbox('content[is_active]', isset($block) ? $block->getContent('is_active') : null, [
                    'label'         => 'Active',
                    'class_control' => 'custom-switch',
                ]) !!}

                {!! Lte3::checkbox('content[show_title]', isset($block) ? $block->getContent('show_title') : null, [
                    'label'         => 'Show title',
                    'class_control' => 'custom-switch',
                ]) !!}
            </div>
        </div>


        <div class="card">
            <div class="card-header"><h3 class="card-title">Media & files</h3></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! Lte3::lfmImage('content[image]', isset($block) ? $block->getContent('image') : null, ['label' => 'Image']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Lte3::lfmFile('content[file]', isset($block) ? $block->getContent('file') : null, ['label' => 'File (PDF, doc...)']) !!}
                    </div>
                </div>
            </div>
        </div>


        {{-- =====================================================================
             SIMPLE MULTIBLOCK — text + url
             ===================================================================== --}}

        <div class="card mb-wrap">
            <div class="card-header">
                <h3 class="card-title">Items (text + url)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-outline-primary mb-btn-add">
                        <i class="fa fa-plus"></i> Add
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-items sortable-y">

                    <template class="mb-template">
                        <div class="mb-item" data-mb-idx="$i">
                            <div class="mb-item-controls">
                                <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                                <span class="mb-item-num"></span>
                                <div class="mb-item-actions">
                                    <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">{!! Lte3::text('content[items][$i][title]', null, ['label' => 'Title']) !!}</div>
                                <div class="col-md-6">{!! Lte3::url('content[items][$i][url]', null, ['label' => 'URL']) !!}</div>
                            </div>
                            {!! Lte3::textarea('content[items][$i][text]', null, ['label' => 'Text', 'rows' => 2]) !!}
                        </div>
                    </template>

                    @if (isset($block) && ($items = $block->getContentSort('items', [])))
                        @foreach ($items as $item)
                            <div class="mb-item" data-mb-idx="{{ $loop->index }}">
                                <div class="mb-item-controls">
                                    <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                                    <span class="mb-item-num"></span>
                                    <div class="mb-item-actions">
                                        <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                                        <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">{!! Lte3::text("content[items][{$loop->index}][title]", $item['title'] ?? '', ['label' => 'Title']) !!}</div>
                                    <div class="col-md-6">{!! Lte3::url("content[items][{$loop->index}][url]", $item['url'] ?? '', ['label' => 'URL']) !!}</div>
                                </div>
                                {!! Lte3::textarea("content[items][{$loop->index}][text]", $item['text'] ?? '', ['label' => 'Text', 'rows' => 2]) !!}
                            </div>
                        @endforeach
                    @else
                        <p class="mb-empty">No items added</p>
                    @endisset

                </div>
            </div>
        </div>


        {{-- =====================================================================
             MULTIBLOCK WITH LFM IMAGE — data-fn-inits="initLfmBtn"
             ===================================================================== --}}

        <div class="card mb-wrap" data-fn-inits="initLfmBtn">
            <div class="card-header">
                <h3 class="card-title">Slides (with image)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-outline-primary mb-btn-add">
                        <i class="fa fa-plus"></i> Add
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-items sortable-y">

                    <template class="mb-template">
                        <div class="mb-item" data-mb-idx="$i">
                            <div class="mb-item-controls">
                                <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                                <span class="mb-item-num"></span>
                                <div class="mb-item-actions">
                                    <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Lte3::text('content[slides][$i][title]', null, ['label' => 'Title']) !!}
                                    {!! Lte3::textarea('content[slides][$i][text]', null, ['label' => 'Text', 'rows' => 2]) !!}
                                    {!! Lte3::url('content[slides][$i][url]', null, ['label' => 'URL']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Lte3::lfmImage('content[slides][$i][img]', null, ['label' => 'Image']) !!}
                                </div>
                            </div>
                        </div>
                    </template>

                    @if (isset($block) && ($items = $block->getContentSort('slides', [])))
                        @foreach ($items as $item)
                            <div class="mb-item" data-mb-idx="{{ $loop->index }}">
                                <div class="mb-item-controls">
                                    <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                                    <span class="mb-item-num"></span>
                                    <div class="mb-item-actions">
                                        <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                                        <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        {!! Lte3::text("content[slides][{$loop->index}][title]", $item['title'] ?? '', ['label' => 'Title']) !!}
                                        {!! Lte3::textarea("content[slides][{$loop->index}][text]", $item['text'] ?? '', ['label' => 'Text', 'rows' => 2]) !!}
                                        {!! Lte3::url("content[slides][{$loop->index}][url]", $item['url'] ?? '', ['label' => 'URL']) !!}
                                    </div>
                                    <div class="col-md-4">
                                        {!! Lte3::lfmImage("content[slides][{$loop->index}][img]", $item['img'] ?? '', ['label' => 'Image']) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="mb-empty">No items added</p>
                    @endisset

                </div>
            </div>
        </div>


        {{-- =====================================================================
             MULTIBLOCK WITH LIMITS (data-mb-min / data-mb-max)
             AND CONFIRM ON DELETE (data-confirm)
             ===================================================================== --}}

        <div class="card mb-wrap" data-mb-min="1" data-mb-max="5">
            <div class="card-header">
                <h3 class="card-title">Features (min 1, max 5, confirm on delete)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-outline-primary mb-btn-add">
                        <i class="fa fa-plus"></i> Add
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-items sortable-y">

                    <template class="mb-template">
                        <div class="mb-item" data-mb-idx="$i">
                            <div class="mb-item-controls">
                                <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                                <span class="mb-item-num"></span>
                                <div class="mb-item-actions">
                                    <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"
                                            data-confirm="Delete this item?"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">{!! Lte3::text('content[features][$i][icon]', null, ['label' => 'Icon', 'placeholder' => 'fa fa-star']) !!}</div>
                                <div class="col-md-4">{!! Lte3::text('content[features][$i][title]', null, ['label' => 'Title']) !!}</div>
                                <div class="col-md-6">{!! Lte3::textarea('content[features][$i][text]', null, ['label' => 'Description', 'rows' => 2]) !!}</div>
                            </div>
                        </div>
                    </template>

                    @if (isset($block) && ($items = $block->getContentSort('features', [])))
                        @foreach ($items as $item)
                            <div class="mb-item" data-mb-idx="{{ $loop->index }}">
                                <div class="mb-item-controls">
                                    <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                                    <span class="mb-item-num"></span>
                                    <div class="mb-item-actions">
                                        <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                                        <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"
                                                data-confirm="Delete this item?"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">{!! Lte3::text("content[features][{$loop->index}][icon]", $item['icon'] ?? '', ['label' => 'Icon', 'placeholder' => 'fa fa-star']) !!}</div>
                                    <div class="col-md-4">{!! Lte3::text("content[features][{$loop->index}][title]", $item['title'] ?? '', ['label' => 'Title']) !!}</div>
                                    <div class="col-md-6">{!! Lte3::textarea("content[features][{$loop->index}][text]", $item['text'] ?? '', ['label' => 'Description', 'rows' => 2]) !!}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="mb-empty">No items added</p>
                    @endisset

                </div>
            </div>
        </div>


        {{-- =====================================================================
             NESTED MULTIBLOCK (2 levels)
             Outer level: $i  — sections
             Inner level: $j  — points inside section (data-mb-placeholder="$j")
             ===================================================================== --}}

        <div class="card mb-wrap">
            <div class="card-header">
                <h3 class="card-title">Sections with points (nested multiblock)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-outline-primary mb-btn-add">
                        <i class="fa fa-plus"></i> Add section
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-items sortable-y">

                    <template class="mb-template">
                        <div class="mb-item" data-mb-idx="$i">
                            <div class="mb-item-controls">
                                <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                                <span class="mb-item-num"></span>
                                <div class="mb-item-actions">
                                    <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                            {!! Lte3::text('content[sections][$i][title]', null, ['label' => 'Section title']) !!}

                            <div class="mb-wrap">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted font-weight-bold">Points:</small>
                                    <button type="button" class="btn btn-xs btn-outline-primary mb-btn-add">
                                        <i class="fa fa-plus"></i> Add point
                                    </button>
                                </div>
                                <div class="mb-items sortable-y">
                                    <template class="mb-template" data-mb-placeholder="$j">
                                        <div class="mb-item" data-mb-idx="$j">
                                            <div class="mb-item-controls">
                                                <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                                                <span class="mb-item-num"></span>
                                                <div class="mb-item-actions">
                                                    <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                                                    <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                                                </div>
                                            </div>
                                            {!! Lte3::text('content[sections][$i][points][$j][text]', null, ['label' => 'Point text']) !!}
                                        </div>
                                    </template>
                                    <p class="mb-empty">No points added</p>
                                </div>
                            </div>

                        </div>
                    </template>

                    @if (isset($block) && ($sections = $block->getContentSort('sections', [])))
                        @foreach ($sections as $section)
                            @php $outerIndex = $loop->index; @endphp
                            <div class="mb-item" data-mb-idx="{{ $loop->index }}">
                                <div class="mb-item-controls">
                                    <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                                    <span class="mb-item-num"></span>
                                    <div class="mb-item-actions">
                                        <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                                        <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                                {!! Lte3::text("content[sections][{$loop->index}][title]", $section['title'] ?? '', ['label' => 'Section title']) !!}

                                <div class="mb-wrap">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted font-weight-bold">Points:</small>
                                        <button type="button" class="btn btn-xs btn-outline-primary mb-btn-add">
                                            <i class="fa fa-plus"></i> Add point
                                        </button>
                                    </div>
                                    <div class="mb-items sortable-y">
                                        <template class="mb-template" data-mb-placeholder="$j">
                                            <div class="mb-item" data-mb-idx="$j">
                                                <div class="mb-item-controls">
                                                    <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                                                    <span class="mb-item-num"></span>
                                                    <div class="mb-item-actions">
                                                        <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                                                        <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </div>
                                                {!! Lte3::text('content[sections][' . $outerIndex . '][points][$j][text]', null, ['label' => 'Point text']) !!}
                                            </div>
                                        </template>
                                        @foreach ($section['points'] ?? [] as $point)
                                            <div class="mb-item" data-mb-idx="{{ $loop->index }}">
                                                <div class="mb-item-controls">
                                                    <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                                                    <span class="mb-item-num"></span>
                                                    <div class="mb-item-actions">
                                                        <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                                                        <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </div>
                                                {!! Lte3::text("content[sections][{$outerIndex}][points][{$loop->index}][text]", $point['text'] ?? '', ['label' => 'Point text']) !!}
                                            </div>
                                        @endforeach
                                        @if (empty($section['points']))
                                            <p class="mb-empty">No points added</p>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    @else
                        <p class="mb-empty">No sections added</p>
                    @endisset

                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
@endsection
