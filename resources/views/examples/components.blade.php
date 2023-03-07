@extends('lte3::layouts.app')

@section('content')
    @include('lte3::parts.content-header', [
        'page_title' => 'Components',
        'url_back' => '#' ,
        'url_create' => '#'
    ])

    <!-- Main content -->
    <section class="content">


        <!-- FILTER -->
        {!! Lte3::formOpen(['action' => Request::fullUrl(), 'method' => 'GET']) !!}
        <div class="card card-outline card-primary collapsed-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i>Filter</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        {!! Lte3::text('name', null, ['label' => 'Name']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Lte3::select2('status', 'new', ['Success', 'Paused', 'Canceled', 'New', 'Old'], [
                            'label' => 'Status',
                            'multiple' => 1,
                            'id' => 'status2'
                        ]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Lte3::datetimepicker('datetime', now(), [
                           'label' => 'Datetime',
                       ]) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {!! Lte3::btnSubmit('Submit') !!}
                {!! Lte3::btnReset('Reset') !!}
            </div>
        </div>
        {!! Lte3::hidden('type', 'projects') !!}
        {!! Lte3::formClose() !!}

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><a class="btn btn-success btn-xs"><i class="fas fa-plus"></i> Create</a></h3>

                <div class="card-tools">
                    <a href="#" class="btn btn-default btn-xs"><i class="fas fa-upload"></i> Export</a>
                    <a href="#" class="btn btn-default btn-xs"><i class="fas fa-download"></i> Import</a>

                    <div class="btn-group">
                        <a href="#" class="btn btn-default btn-xs">UK</a>
                        <a href="#" class="btn btn-primary btn-xs">EN</a>
                        <a href="#" class="btn btn-default btn-xs">FR</a>
                    </div>

                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                    <tr>
                        <th style="width: 1%">
                            #
                        </th>
                        <th style="width: 20%">
                            Project Name
                        </th>
                        <th style="width: 30%">
                            Team Members
                        </th>
                        <th>
                            Project Progress
                        </th>
                        <th style="width: 8%" class="text-center">
                            Status
                        </th>
                        <th></th>
                        <th style="width: 20%">
                        </th>
                    </tr>
                    </thead>
                    <tbody class="sortable-y" data-url="{{ route('lte3.data.save') }}">
                    @foreach($progects as $progect)
                        <tr id="{{ $loop->index }}">
                            <td>#</td>
                            <td>
                                <a class="hover-edit" href="#">{{ $progect['name'] }}</a>
                                <br/>
                                <small class="js-clipboard with-mark">Created {{ $progect['created_at'] }}</small>
                            </td>
                            <td>
                                <ul class="list-inline">
                                    @foreach($progect['images'] as $img)
                                    <li class="list-inline-item">
                                        <img src="{{ url($img) }}" class="table-avatar" alt="Avatar">
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="project_progress">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         aria-valuenow="{{$progect['progress']}}"
                                         aria-valuemin="0" aria-valuemax="100" style="width: {{$progect['progress']}}%">
                                    </div>
                                </div>
                                <small> {{$progect['progress']}}% Complete </small>
                            </td>
                            <td class="project-state">
                                <span class="badge badge-success">{{ $progect['status'] }}</span>
                            </td>
                            <td class="project-actions text-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-default">Action</button>
                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle dropdown-icon"
                                            data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a href="#" class="dropdown-item">Clone</a>
                                        <a href="#" class="dropdown-item">Notify</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td class="project-actions text-right">
                                <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-folder"></i>View</a>
                                <a href="#" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i>Edit</a>
                                <a href="{{ route('lte3.data.save') }}" class="btn btn-danger btn-sm js-click-submit"
                                   data-confirm="Delete?"><i class="fas fa-trash"></i>Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {!! Lte3::pagination($terms ?? null) !!}
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <!-- BASE -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Base</h3>
                        </div>

                        {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'model' => null, 'files' => true, 'method' => 'POST']) !!}

                        <div class="card-body">

                            {!! Lte3::text('firstname', 'Thomas') !!}

                            {!! Lte3::text('lastname', 'Mann', [
                                'readonly' => 1,
                            ]) !!}

                            {!! Lte3::text('email', 'fom@app.com', [
                                'type' => 'email',
                                'max' => '30',
                                'label' => 'Your Email',
                                'help' => '* Enter Email',
                                'prepend' => '<i class="fas fa-envelope"></i>',
                                'append' => '<i class="fas fa-check"></i>',
                                'checkbox' => ['name' => 'verify', 'title' => 'Verify', 'value' => 0, 'readonly' => 1,]
                            ]) !!}
                            {!! Lte3::text('Password', null, ['type' => 'password']) !!}

                            {!! Lte3::text('url', null, [
                                    'type' => 'url',
                            ]) !!}

                            {!! Lte3::hidden('__tmp', '666', ['label' => 'Hidden field']) !!}

                            {!! Lte3::slug('slug', 'qwerty', ['label' => 'Slug']) !!}

                            {!! Lte3::colorpicker('colorpicker', null, ['label' => 'Color']) !!}

                            {!! Lte3::checkbox('publish', null, ['label' => 'Publish']) !!}

                            {!! Lte3::checkbox('allowed', 1, [
                                'label' => 'Allowed',
                                'url_save' => route('lte3.data.save'),
                                'method_save' => 'POST',
                            ]) !!}

                            {!! Lte3::checkbox('accept', 0, [
                                    'label' => 'Accept <a href="#">Terms</a>',
                                    'checked_value' => 2,
                                    'unchecked_value' => 0,
                                    'wrap_class' => 'custom-switch'
                            ]) !!}

                            {!! Lte3::radiogroup('size', 'm', ['s' => 'Small', 'm' => 'Medium', 'l' => 'Large',], ['label' => 'Size:']) !!}

                            {!! Lte3::radiogroup('chanel', 'tg', ['tg' => 'Telegram', 'email' => 'Email', 'sms' => 'SMS',], [
                                'label' => 'Toggle Block:',
                                'map' => [
                                    'tg' => ['.js-block-tg'],
                                    'email' => ['.js-block-email'],
                                    'sms' => ['.js-block-sms'],
                                ],
                            ]) !!}
                            <h3 class="js-block-tg">Telegram!</h3>
                            <h3 class="js-block-email">Email!</h3>
                            <h3 class="js-block-sms">SMS!</h3>

                            {!! Lte3::radiogroup('brand', 'samsung', [
                                    'apple' => ['label' => 'Apple', 'url' => route('lte3.data.save', ['brand' => 'apple'])],
                                    'samsung' => ['label' => 'Samsung', 'url' => route('lte3.data.save', ['brand' => 'samsung'])],
                                    'xiaomi' => ['label' => 'Xiaomi', 'url' => route('lte3.data.save', ['brand' => 'xiaomi'])],
                                ], ['label' => 'Submit to URL:', 'submit_methor' => 'POST'])
                            !!}

                            {!! Lte3::textarea('message', 'Hello World!', [
                                    'label' => 'Message',
                                    'rows' => 3,
                            ]) !!}

                            <a href="#" class="js-clipboard btn btn-outline-success btn-sm"
                               data-text="Hello!"
                               data-toggle="tooltip" title="Copy">Copy text</a>
                            <a href="#" class="js-click-submit btn btn-outline-secondary btn-sm"
                               data-method="GET"
                               data-confirm="Submit?" data-url="#" data-toggle="tooltip" title="Submit">Reload</a>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        {!! Lte3::formClose() !!}
                    </div>

                    <!-- SELECT2 -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">SELECT2</h3>
                        </div>
                        {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'method' => 'post']) !!}
                        <div class="card-body">

                            {!! Lte3::select2('status', 'canceled', ['new' => 'New', 'canceled' => 'Canceled', 'delivered' => 'Delivered'], [
                                'label' => 'Status',
                            ]) !!}

                            {!! Lte3::select2('color', 'green', ['Green', 'Red', 'White'], [
                                'label' => 'Color',
                                'empty_value' => '--',
                            ]) !!}

                            {!! Lte3::select2('tag', 'auto', ['auto' => 'Auto', 'news' => 'News'], [
                                'label' => 'Tag',
                                'url_save' => route('lte3.data.save'),
                                'help' => '* AJAX save'
                            ]) !!}

                            {!! Lte3::select2('categories', 3, [3 => 'Sport'], [
                                'label' => 'Categories',
                                'multiple' => 1,
                                'url_save' => route('lte3.data.save'),
                                'url_suggest' => route('lte3.data.tags'),
                            ]) !!}

                            {!! Lte3::select2('domain', 'canceled', ['canceled' => 'Canceled'], [
                                'label' => 'Domain',
                                'multiple' => true,
                                'max' => 1,
                                'url_tags' => route('lte.data.tags'),
                                'help' => '* Select one or create ;'
                            ]) !!}

                            {!! Lte3::select2('tags', '4', ['4' => 'Auto'], [
                                'label' => 'Tags',
                                'multiple' => 1,
                                'url_tags' => route('lte.data.tags'),
                                'separators' => "[';']",
                                'new_tag_label' => ' [NEW]',
                                'max' => 4,
                                'help' => '* Enter new end ;'
                            ]) !!}

                            {!! Lte3::select2('status', 'smtp', ['log' => 'Log', 'smtp' => 'SMTP', 'sendmail' => 'Mail'], [
                                'label' => 'Mail Driver',
                                'empty_value' => '--',
                                'map' => [
                                    'smtp' => ['.block-smtp'],
                                    'log' => ['.block-log'],
                                    'sendmail' => ['.block-sendmail'],
                                ],
                                'help' => '* Change for show block'
                            ]) !!}
                            <div class="block-smtp" style="display:none"><h2>SMTP Block</h2></div>
                            <div class="block-sendmail" style="display: none;"><h2>SENDMAIL Block</h2></div>
                            <div class="block-log" style="display: none;"><h2>LOG Block</h2></div>

                        </div>
                        {!! Lte3::formClose() !!}
                    </div>

                    <!-- TREE & NESTEDSET -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Tree & Nestedsets</h3>
                        </div>
                        <div class="card-body">

                            {!! Lte3::select2Tree('category_id', [
                                'label' => 'Tree Categories',
                                'multiple' => 1,
                                'required' => 1,
                                'help' => '* Some text',
                                'method_get' => 'POST',
                                'url_tree' => route('lte3.data.treeselect', ['vocabulary' => 'products', 'selected' => [1,3]]),
                            ]) !!}

                            {!! Lte3::treeview('models', [
                                'label' => 'Ajax data',
                                'method_get' => 'GET',
                                'url_tree' => route('lte3.data.treeview', ['selected' => [2,4]]),
                            ]) !!}

                            {!! Lte3::treeview('models', [
                                'label' => 'Static data',
                                'data' => $treeviewArray,
                            ]) !!}

                        </div>
                        <div class="card-footer">
                            Visit <a href="https://github.com/fomvasss/laravel-simple-taxonomy" target="_blank">documentation</a>
                            for more examples and information about the plugin.
                        </div>
                    </div>

                    <!-- SORTABLE NESTED -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Nestedset</h3>
                        </div>
                        <div class="card-body">
                            @isset($terms)
                                {!! Lte3::nestedset($terms, [
                                        'label' => 'Models',
                                        'has_nested' => true,
                                        'routes' => [
                                            'edit' => 'lte3.data.save',
                                            'create' => 'lte3.data.save',
                                            'delete' => 'lte3.data.save',
                                            'order' => 'lte3.data.save',
                                            'show' => 'lte3.data.save',
                                            'params' => [],
                                        ],
                                ]) !!}

                                {!! Lte3::pagination($terms) !!}

                            @endisset
                        </div>
                    </div>

                </div>

                <div class="col-md-6">
                    <!-- X-EDITABLE -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">X-Editable</h3>
                        </div>
                        <div class="card-body">
                            <div>
                                Text:
                                {!! Lte3::xEditable('name', 'Space Odyssey 2001', [
                                    'type' => 'text',
                                    'pk' => 1,
                                    'url_save' => route('lte3.data.save'),
                                ]) !!}
                            </div>
                            <div>
                                Textarea:
                                {!! Lte3::xEditable('comment', 'Interstellar', [
                                    'type' => 'textarea',
                                    'field_name' => 'data[comment]',
                                    'pk' => 2,
                                    'url_save' => route('lte3.data.save'),
                                ]) !!}
                            </div>
                            <div>
                                Showed:
                                {!! Lte3::xEditable('visible', 0, [
                                    'value_title' => 'Hide',
                                    'type' => 'select',
                                    'field_name' => 'data[is_show]',
                                    'source' => [["value" => "1", "text" => "Show"], ["value" => "0", "text" => "Hide"]],
                                    'pk' => 3,
                                    'url_save' => route('lte3.data.save'),
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <!-- LISTS -->
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Inline</h3>
                        </div>
                        {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'method' => 'post']) !!}
                        <div class="card-body">

                            {!! Lte3::lists('countries', ['Ukraine', 'Poland', 'France', 'England', 'USA', 'Spanish'], [
                                'label' => 'Countries:',
                                'field_name' => 'countries',
                                'placeholder_value' => 'Title',
                            ]) !!}

                            {!! Lte3::links('links', [['key' => 'liqpay', 'value' => 'LiqPay', 'safe' => 1], ['key' => 'paypal', 'value' => 'PayPal']], [
                                'label' => 'Payment methods:',
                                'field_name' => 'pay_methods',
                                'key_key' => 'key',
                                'key_value' => 'value',
                                'placeholder_key' => 'Key',
                                'placeholder_value' => 'Title',
                            ]) !!}

                        </div>
                        <div class="card-footer">
                            {!! Lte3::btnSubmit('Submit') !!}

                            <div class="mt-2">
                                Visit <a href="https://github.com/fomvasss/laravel-variables" target="_blank">documentation</a>
                                for more examples and information about the plugin.
                            </div>
                        </div>
                        {!! Lte3::formClose() !!}
                    </div>

                    <!-- MODALS -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Modals</h3>
                            <div class="card-tools">
                                <button data-source="{{route('lte3.data.modal-content')}}" type="button"
                                        class="btn btn-tool" data-card-widget="card-refresh" data-load-on-init="false">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-source-selector="#card-refresh-content"
                                        data-card-widget="maximize">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <span class="text-danger"></span>

                            <button type="button" class="btn btn-default js-modal-fill-html" data-target="#modal-sm"
                                    data-url="{{route('lte3.data.modal-content', ['modal' => 'sm'])}}"
                                    data-fn-inits="initJsVerificationSlugField,initSortableY">
                                AJAX Small Modal with Init functions
                            </button>
                            <button type="button" class="btn btn-default js-modal-fill-html" data-target="#modal-lg"
                                    data-url="{{route('lte3.data.modal-content', ['modal' => 'lg'])}}">
                                AJAX Large Modal
                            </button>
                            <button type="button" class="btn btn-default js-modal-fill-html" data-target="#modal-xl"
                                    data-url="{{route('lte3.data.modal-content')}}">
                                AJAX Xl Modal
                            </button>
                            <button type="button" class="btn btn-default" data-toggle="modal"
                                    data-target="#my-modal-lg">
                                Small Modal
                            </button>


                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

                    </div>

                    <!-- DATE & TIME -->
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Dete & Time</h3>
                        </div>
                        <div class="card-body">

                            {!! Lte3::timepicker('time', now(), [
                                'label' => 'Time',
                                'format' => 'H:i:s',
                            ]) !!}

                            {!! Lte3::datepicker('date', now(), [
                                'label' => 'Date',
                                'format' => 'Y-m-d',
                            ]) !!}

                            {!! Lte3::datetimepicker('datetime', now(), [
                                'label' => 'Datetime',
                                'format' => 'Y-m-d H:i:s',
                                'help' => 'Now datetime',
                            ]) !!}

                        </div>
                    </div>

                    <!-- FILES -->
                    <div class="card card-success"
                         style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
                        <div class="card-header">
                            <h3 class="card-title">Files</h3>
                        </div>
                        <div class="card-body">
                            <span class="text-danger"></span>

                            {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'files' => true, 'method' => 'POST', 'class' => 'js-form-submit-file-changed']) !!}
                            <label><input type="file" hidden><strong>Select & Upload file</strong></label>
                            {!! Lte3::formClose() !!}
                        <!-- Or simple: -->
                            {!! Lte3::fileForm('avatar', [
                                    'html' => '<div><img src="/vendor/lte3/img/favicons/apple-touch-icon.png" style="width: 100px;"></div>',
                                    'url_save' => route('lte3.data.save'),
                            ]) !!}

                            {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'files' => true]) !!}

                            {!! Lte3::file('document', '/vendor/lte3/img/favicons/favicon-32x32.png', [
                                'label' => 'Document',
                                'help' => 'Single File',
                            ]) !!}

                            {!! Lte3::file('favicons', [
                                '/vendor/lte3/img/favicons/android-chrome-512x512.png',
                                '/vendor/lte3/img/favicons/android-chrome-192x192.png',
                            ], ['label' => '']) !!}

                            {!! Lte3::btnSubmit('Submit', 'action', 'save') !!}

                            {!! Lte3::formClose() !!}

                        </div>
                    </div>

                    <!-- SPATIE MEDIALIBRARY -->
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Spatie Medialibrary & Extension</h3>
                        </div>
                        <div class="card-body">

                            {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'files' => true]) !!}

                            {!! Lte3::mediaFile('images', $model, [
                                    'label' => 'Images',
                                    'multiple' => true,
                                    'is_image' => true,
                            ]) !!}

                            {!! Lte3::mediaFile('image', $model, [
                                    'label' => 'Image',
                                    'is_image' => true,
                            ]) !!}

                            {!! Lte3::btnSubmit('Submit', 'action', 'save') !!}

                            {!! Lte3::formClose() !!}

                        </div>
                        <div class="card-footer">
                            Visit <a href="https://github.com/fomvasss/laravel-medialibrary-extension"
                                     target="_blank"> documentation</a> for more examples and information about the
                            plugin.
                        </div>
                    </div>

                </div>


                <!-- Text Editors -->


            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Summernote</h3>
                        </div>

                        <div class="card-body">
                            <textarea class="f-summernote"></textarea>
                        </div>
                        <div class="card-footer">
                            Visit <a href="https://github.com/summernote/summernote/" target="_blank">Summernote</a>
                            documentation for more examples and information about the plugin.
                        </div>
                    </div>


                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Codemirror</h3>
                        </div>

                        <div class="card-body">
                            <textarea class="f-codeMirror" class="p-3"></textarea>

                        </div>
                        <div class="card-footer">
                            Visit <a href="https://codemirror.net/" target="_blank">CodeMirror</a> documentation for
                            more examples and information about the plugin.
                        </div>
                    </div>

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">CKEditor 4</h3>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="">CKE Mini</label>
                                <textarea class="form-control f-cke-mini"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">CKE Small</label>
                                <textarea class="form-control f-cke-small"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">CKE Full</label>
                                <textarea class="form-control f-cke-full"></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            Visit <a href="https://ckeditor.com/docs/ckeditor4/latest/index.html" target="_blank">CKEditor
                                4</a> documentation for more examples and information about the plugin.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@push('styles')
    <!-- summernote -->
    <link rel="stylesheet" href="/vendor/adminlte/plugins/summernote/summernote-bs4.min.css">
    <!-- CodeMirror -->
    <link rel="stylesheet" href="/vendor/adminlte/plugins/codemirror/codemirror.css">
    <link rel="stylesheet" href="/vendor/adminlte/plugins/codemirror/theme/monokai.css">
@endpush

@push('scripts')
    <!-- Summernote -->
    <script src="/vendor/adminlte/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- CodeMirror -->
    <script src="/vendor/adminlte/plugins/codemirror/codemirror.js"></script>
    <script src="/vendor/adminlte/plugins/codemirror/mode/css/css.js"></script>
    <script src="/vendor/adminlte/plugins/codemirror/mode/xml/xml.js"></script>
    <script src="/vendor/adminlte/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>
@endpush

@push('modals')
    <div class="modal fade" id="my-modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"><h4 class="modal-title">Large Modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body"><p>One fine body&hellip;</p></div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content --></div><!-- /.modal-dialog --></div>
@endpush
