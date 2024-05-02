@extends('lte3::layouts.app')

@section('content')
    @include('lte3::parts.content-header', [
        'page_title' => 'Components',
        'url_back' => '#',
        'url_create' => '#'
    ])

    <!-- Main content -->
    <section class="content">

        <!-- TOP BUTTONS -->
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="#" class="btn btn-flat btn-success mt-1"><i class="fa fa-plus"></i> Create</a>
                <a href="#" class="btn btn-flat btn-warning mt-1" data-toggle="modal" data-target="#my-modal-lg"><i class="far fa-calendar-plus"></i> Modal</a>
                <a href="#" class="btn btn-flat btn-primary mt-1 js-modal-fill-html" data-target="#modal-lg" data-url="{{route('lte3.data.modal-content', ['modal' => 'lg'])}}" data-fn-inits="initSelect2"><i class="fas fa-sync-alt"></i></i> Ajax</a>
            </div>
            <div class="col-md-6 text-right">
                <a href="#" class="btn btn-flat btn-default mt-1"><i class="fa fa-search"></i></a>
                <a href="{{ \Illuminate\Support\Facades\Request::fullUrlWithQuery(['_export' => 'csv']) }}" class="btn btn-flat btn-default mt-1"><i class="fa fa-upload"></i></a>
            </div>
        </div>

        <!-- STATISTIC CARDS -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">CPU Traffic</span>
                        <span class="info-box-number">10<small>%</small></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Likes</span>
                        <span class="info-box-number">41,410</span>
                    </div>
                </div>
            </div>

            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sales</span>
                        <span class="info-box-number">760</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">New Members</span>
                        <span class="info-box-number">2,000</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER -->
        @include('lte3::examples.inc.filter')

        <!-- LIST/TABLE -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Total: 3 <a class="btn btn-success btn-xs"><i class="fas fa-plus"></i> Create</a></h3>

                <div class="card-tools">
                    <a href="{{ \Illuminate\Support\Facades\Request::fullUrlWithQuery(['_export' => 'csv']) }}" class="btn btn-default btn-xs"><i class="fas fa-upload"></i> Export</a>
                    {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'files' => true, 'method' => 'POST', 'class' => 'js-form-submit-file-changed', 'style' => 'display: inline-flex']) !!}
                        <label class="btn btn-default btn-xs"><input type="file" hidden><i class="fas fa-download"></i> Import</label>
                    {!! Lte3::formClose() !!}

                    <div class="btn-group">
                        <a href="#" class="btn btn-default btn-xs">UK</a>
                        <a href="#" class="btn btn-primary btn-xs">EN</a>
                        <a href="#" class="btn btn-default btn-xs">FR</a>
                    </div>

                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-source-selector="#card-refresh-content"
                            data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th style="width: 1%">#</th>
                        <th style="width: 10px;"></th>
                        <th></th>
                        <th style="width: 15%">Name</th>
                        <th style="width: 20%">Members</th>
                        <th>Sum</th>
                        <th>Progress</th>
                        <th style="width: 8%">Status</th>
                        <th></th>
                        <th style="width: 20%"></th>
                    </tr>
                    </thead>
                    <tbody class="sortable-y" data-url="{{ route('lte3.data.save') }}">
                    @foreach($progects as $progect)
                        <tr id="{{ $loop->index }}" class="va-center">
                            <td><i class="fa fa-arrows-alt-v"></i></td>
                            <td>
                                <div class="btn-actions dropdown">
                                    <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                    <div class="dropdown-menu  'is_image' => false," role="menu">
                                        <a href="#" class="dropdown-item">Edit</a>
                                        <a href="#" class="dropdown-item"
                                           data-confirm="Clone?">Clone</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#"
                                           class="dropdown-item js-click-submit" data-method="delete"
                                           data-confirm="Remove?">Remove</a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="/vendor/lte3/img/no-image.png" class="js-popup-image">
                                    <img src="/vendor/lte3/img/no-image.png" class="img-thumbnail" style="max-width: 100px">
                                </a>
                            </td>
                            <td>
                                <a class="hover-edit" href="#">{{ $progect['name'] }}</a>
                                <br/>
                                <small class="js-clipboard with-mark">Created {{ $progect['created_at'] }}</small>
                            </td>
                            <td>
                                <ul class="list-inline m-0">
                                    @foreach($progect['images'] as $img)
                                    <li class="list-inline-item js-popup-images">
                                        <a href="{{ url($img) }}"><img src="{{ url($img) }}" class="table-avatar" alt="Avatar"></a>
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>$<span class="js-num-format text-nowrap">{{ $progect['sum'] }}</span></td>
                            <td>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         aria-valuenow="{{$progect['progress']}}"
                                         aria-valuemin="0" aria-valuemax="100" style="width: {{$progect['progress']}}%">
                                    </div>
                                </div>
                                <small class="text-nowrap"> {{$progect['progress']}}% Complete </small>
                            </td>
                            <td>
                                <a href="#" class="hover-edit js-modal-fill-html"
                                    data-target="#modal-lg"
                                    data-url="{{route('lte3.data.modal-content', ['modal' => 'lg'])}}"
                                >
                                    <span class="btn btn-sm bg-gradient-warning btn-flat">{{ $progect['status'] }}</span>
                                </a>
                            </td>
                            <td class="text-right">
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
                            <td class="text-right">
                                <a href="#" class="btn btn-default btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                <a href="{{ route('lte3.data.save') }}" class="btn btn-danger btn-sm js-click-submit"
                                   data-confirm="Delete?"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {!! Lte3::pagination($terms ?? null) !!}
            </div>
        </div>

        <!-- COMPONENTS -->
        <div class="row">
            <div class="col-md-6">
                <!-- BASE -->
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Base</h3>
                    </div>

                    {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'model' => null, 'files' => true, 'method' => 'POST', 'id' => 'QQQ']) !!}

                    <div class="card-body">
                        {!! Lte3::text('firstname', 'Thomas') !!}

                        {!! Lte3::text('lastname', 'Mann', [
                            'readonly' => 1,
                        ]) !!}

                        {!! Lte3::text('city', 'Lutsk', ['hidden_wrap' => 1]) !!}

                        {!! Lte3::text('default', null, [
                            'default' => 'Default value',
                            'append' => ['<i class="fas fa-fingerprint"></i>', '<a href="#"><i class="fas fa-qrcode"></i></a>']
                        ]) !!}

                        {!! Lte3::password('Password') !!}

                        {!! Lte3::number('Age', null, ['default' => 18, 'max' => '100', 'min' => 1]) !!}

                        {!! Lte3::url('url', null, [
                            'default' => 'https://stackoverflow.com/',
                        ]) !!}

                        {!! Lte3::email('email', 'fom@app.com', [
                            'label' => 'Your Email',
                            'help' => '* Enter Email',
                            'prepend' => '<i class="fas fa-envelope"></i>',
                            'append' => '<i class="fas fa-check"></i>',
                            'checkbox' => ['name' => 'verify', 'title' => 'Verify', 'value' => 0, 'readonly' => 1,]
                        ]) !!}

                        {!! Lte3::hidden('__tmp', '666', ['label' => 'Hidden field']) !!}

                        {!! Lte3::slug('slug', 'qwerty', ['label' => 'Slug']) !!}

                        {!! Lte3::colorpicker('colorpicker', null, ['label' => 'Color', 'default' => '#FFFFFF']) !!}

                        {!! Lte3::colorpicker('colorpicker2', null, ['label' => 'Color2', 'transparent' => true]) !!}

                        {!! Lte3::range('age', 18, ['min' => 12, 'max' => 100, 'step' => 1,]) !!}

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
                                'class_control' => 'custom-switch'
                        ]) !!}
                        <div class="row">
                            <div class="col-md-12">
                                {!! Lte3::radiogroup('payment', null, ['paypal' => 'PayPal', 'fondy' => 'Fondy', 'liqpay' => 'LiqPay',], [
                                    'default' => 'liqpay',
                                    'class_wrap' => 'row',
                                ]) !!}
                            </div>
                            <div class="col-md-3">
                                {!! Lte3::radiogroup('size', 'm', ['s' => 'Small', 'm' => 'Medium', 'l' => 'Large',], ['label' => 'Size:']) !!}
                            </div>
                            <div class="col-md-3">
                                {!! Lte3::radiogroup('brand', 'samsung', [
                                        'apple' => ['label' => 'Apple', 'url' => route('lte3.data.save', ['brand' => 'apple'])],
                                        'samsung' => ['label' => 'Samsung', 'url' => route('lte3.data.save', ['brand' => 'samsung'])],
                                        'xiaomi' => ['label' => 'Xiaomi', 'url' => route('lte3.data.save', ['brand' => 'xiaomi']), 'disabled' => 1],
                                    ], ['label' => 'Submit to URL:', 'submit_methor' => 'POST'])
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!! Lte3::radiogroup('chanel', 'sms', ['push' => 'Push', 'email' => 'Email', 'sms' => 'SMS',], [
                                    'label' => 'Toggle Block:',
                                    'map' => [
                                        'push' => ['.js-block-push'],
                                        'email' => ['.js-block-email'],
                                        'sms' => ['.js-block-sms'],
                                    ],
                                ]) !!}
                                <h3 class="js-block-push">Push!</h3>
                                <h3 class="js-block-email">Email!</h3>
                                <h3 class="js-block-sms">SMS!</h3>
                            </div>
                        </div>

                        {!! Lte3::textarea('message', 'Hello World!', [
                                'label' => 'Message',
                                'rows' => 3,
                        ]) !!}

                        <a href="#" class="js-click-submit btn btn-outline-secondary btn-sm"
                           data-method="GET"
                           data-confirm="Submit?" data-url="#" data-toggle="tooltip" title="Submit">Reload</a>
                        <a href="#" class="js-clipboard btn btn-outline-success btn-sm"
                           data-text="Hello!"
                           data-toggle="tooltip" title="Copy">Copy text</a>
                        <a href="#" class="js-clipboard with-mark"
                           data-text="Hello!"
                           data-toggle="tooltip" title="Copy">Copy text</a>

                    </div>
                    <div class="card-footer text-right">
                        {!! Lte3::btnReset('Reset', ['url' => '']) !!}
                        {!! Lte3::btnSubmit('Submit', 'action', 'save', ['add' => 'fixed']) !!}
                        {!! Lte3::btnSubmit('Submit', null, null, ['before_title' => '<i class="fa fa-check"></i>', 'after_title' => '<i class="fa fa-cogs"></i>', 'class' => 'btn-success']) !!}
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
                            'close_on_select' => 0,
                        ]) !!}

                        {!! Lte3::select2('domain', 'canceled', ['canceled' => 'Canceled'], [
                            'label' => 'Domain',
                            'multiple' => true,
                            'max' => 1,
                            'url_tags' => route('lte3.data.tags'),
                            'help' => '* Select one or create ;'
                        ]) !!}

                        {!! Lte3::select2('tags', '4', ['4' => 'Auto'], [
                            'label' => 'Tags',
                            'multiple' => 1,
                            'url_tags' => route('lte3.data.tags'),
                            'separators' => "[';']",
                            'new_tag_label' => ' [NEW]',
                            'max' => 4,
                            'help' => '* Enter new end ;'
                        ]) !!}

                        {!! Lte3::select2('driver', 'smtp', ['log' => 'Log', 'smtp' => 'SMTP', 'sendmail' => 'Mail'], [
                            'label' => 'Mail Driver',
                            'empty_value' => '--',
                            'map' => [
                                'smtp' => ['.block-smtp'],
                                'log' => ['.block-log', '.block-log-sendmail'],
                                'sendmail' => ['.block-sendmail', '.block-log-sendmail'],
                            ],
                            'help' => '* Change for show block'
                        ]) !!}
                        <div class="block-smtp" style="display:none"><h2>SMTP Block</h2></div>
                        <div class="block-sendmail" style="display: none;"><h2>SENDMAIL Block</h2></div>
                        <div class="block-log" style="display: none;"><h2>LOG Block</h2></div>
                        <div class="block-log-sendmail" style="display: none;"><h2>Common LOG+SENDMAIL</h2></div>

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
                            'expand_all' => true,
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
                    {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'method' => 'post']) !!}
                    {!! Lte3::hidden('_modal', '#my-modal-lg') !!} {{-- Show this modal after submit form --}}
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
                                data-fn-inits="initSelect2"
                                data-url="{{route('lte3.data.modal-content')}}">
                            AJAX Xl Modal
                        </button>
                        <button type="button" class="btn btn-default" data-toggle="modal"
                                data-target="#my-modal-lg">
                            Small Modal
                        </button>

                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    {!! Lte3::formClose() !!}
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
                        <label class="badge bg-primary"><input type="file" hidden><strong>Select & Upload file</strong></label>
                        {!! Lte3::formClose() !!}
                        <hr>
                            <!-- Or simple: -->
                        {!! Lte3::fileForm('avatar', [
                                'html' => '<div><a href="/vendor/lte3/img/favicons/apple-touch-icon.png" class="js-popup-image"><img src="/vendor/lte3/img/favicons/apple-touch-icon.png" style="width: 100px;"></a></div>',
                                'url_save' => route('lte3.data.save'),
                        ]) !!}

                        {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'files' => true]) !!}

                        {!! Lte3::file('document', '/vendor/lte3/img/favicons/favicon-32x32.png', [
                            'label' => 'Document',
                            'help' => 'Single File',
                        ]) !!}

                        {!! Lte3::file('varfavicons', [
                            '/vendor/lte3/img/favicons/android-chrome-512x512.png',
                            '/vendor/lte3/img/favicons/android-chrome-192x192.png',
                        ], ['label' => '']) !!}

                        <button type="submit" class="btn btn-primary">Submit</button>
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

                        {!! Lte3::mediaImage('images', $model, [
                                'label' => 'Images',
                                'multiple' => true,
                        ]) !!}

                        {!! Lte3::mediaImage('image', $model, [
                                'custom_properties' => ['alt']
                        ]) !!}

                        {!! Lte3::mediaFile('documents', $model, [
                                'label' => 'Documents',
                                'multiple' => true,
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
        </div>

        <!-- EDITORS -->
        <div class="row">
                <div class="col-md-12">

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Laravel File Manager</h3>
                        </div>

                        <div class="card-body">

                            @if(is_dir(public_path('vendor/laravel-filemanager')))
                            {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'files' => true]) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Lte3::lfmImage('poster', '/vendor/lte3/img/favicons/favicon-32x32.png', [
                                        'lfm_category' => 'image',  // see configs/lfm.php folder_categories
                                    ]) !!}

                                </div>
                                <div class="col-md-6">
                                    {!! Lte3::lfmFile('instruction', null, [
                                          'label' => 'Instruction',
                                          'is_image' => false,
                                          'lfm_category' => 'file',
                                          'trim_host' => true,
                                          'multiple' => 1,
                                    ]) !!}
                                </div>
                            </div>
                            <br>
                            {!! Lte3::btnSubmit('Submit', 'action', 'save-dd') !!}
                            {!! Lte3::formClose() !!}

                            <br>
                            <iframe src="/filemanager?type=Images" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                            @else
                                LFM is not installed! See <a href="https://unisharp.github.io/laravel-filemanager/installation">docs</a>
                            @endif

                        </div>
                        <div class="card-footer">
                            Visit <a href="https://unisharp.github.io/laravel-filemanager/installation" target="_blank">LFM</a>
                            documentation.
                        </div>
                    </div>


                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">TinyMce</h3>
                        </div>

                        <div class="card-body">
                            {!! Lte3::textarea('message_tinymce', 'f-tinymce - add class to textarea', [
                                'label' => 'Message',
                                'class' => 'f-tinymce',
                            ]) !!}
                            {{--<textarea class="f-tinymce"></textarea>--}}
                        </div>
                        <div class="card-footer">
                            Visit <a href="https://www.tiny.cloud/docs/tinymce/6/php-projects/" target="_blank">TinyMce</a>
                            documentation for more examples and information about the plugin.
                        </div>
                    </div>


                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Summernote</h3>
                        </div>

                        <div class="card-body">
                            {!! Lte3::textarea('message_tinymce', 'f-summernote - add class to textarea', [
                                 'label' => 'Message',
                                 'class' => 'f-summernote',
                             ]) !!}
                            {{--<textarea class="f-summernote"></textarea>--}}
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
                            {!! Lte3::textarea('message_tinymce', '<p><strong>f-codeMirror</strong> - add class to textarea</p>', [
                                 'label' => 'HTML',
                                 'class' => 'f-codeMirror',
                             ]) !!}
                            {{--<textarea class="f-codeMirror" class="p-3"></textarea>--}}

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
                                <label for="">Message</label>
                                <textarea class="form-control f-cke-mini">f-cke-mini - add class to textarea</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Message</label>
                                <textarea class="form-control f-cke-small">f-cke-small - add class to textarea</textarea>
                            </div>
                            <div class="form-group">
                                {!! Lte3::textarea('message_cke_full', 'f-cke-full - add class to textarea', [
                                     'label' => 'Message',
                                     'class' => 'f-summernote',
                                 ]) !!}
                            </div>
                        </div>
                        <div class="card-footer">
                            <ul>
                                <li>Visit <a href="https://ckeditor.com/docs/ckeditor4/latest/index.html" target="_blank">CKEditor
                                        4</a> documentation for more examples and information about the plugin.</li>
                                <li><a href="https://ckeditor.com/ckeditor-4/download/" target="_blank">Download</a> actual version CKEditor (in <code>public/vendor/ckeditor</code> directory)</li>
                                <li>Install <a href="https://unisharp.github.io/laravel-filemanager/installation">Laravel Filemanager</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


        <!-- Dynamic blocks -->
        <div class="row">
            <div class="col-md-12">

                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Dynamic blocks</h3>
                    </div>
                    <div class="card-body">
                    {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'files' => true]) !!}

                        {{-- STATIC: --}}
                        {!! Lte3::text('block[title]', null, ['label' => 'Title']) !!}
                        {!! Lte3::textarea('block[desc]', null, ['label' => 'Desc']) !!}
                        {!! Lte3::lfmImage('block[photo]', isset($block) ? $block->getContent('photo') : null, ['label' => 'Photo']) !!}

                        {{-- MULTYPLE: --}}
                        <div class="card f-wrap f-multyblocks" data-fn-inits="initLfmBtn,initColorpicker">
                            <div class="card-body">
                                <div class="f-items sortable-y">

                                    {{-- TEMPLATE FIELDS --}}
                                    <template class="f-item-template">
                                        <div class="f-item">
                                            <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                                    class="fa fa-trash"></i></a>
                                            <i class="fa fa-arrows-alt-v cursor-move"></i>
                                            {!! Lte3::text('block[items][$i][question]', null, ['label' => 'Question',]) !!}
                                            {!! Lte3::textarea('block[items][$i][answer]', null, ['label' => 'Answer',]) !!}
                                            {!! Lte3::lfmImage('block[items][$i][img]', null, ['label' => 'Img',]) !!}
                                            {!! Lte3::hidden('block[items][$i][weight]', null, ['class' => 'js-input-weight']) !!}
                                        </div>
                                    </template>

                                    {{-- ALREADY SAVED MULTIPLE FIELDS --}}
                                    @if (isset($block) && ($items = $block->getContentSort('items', [])))
                                        @foreach ($items as $item)
                                            <div class="f-item">
                                                <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                                        class="fa fa-trash"></i></a>
                                                <i class="fa fa-arrows-alt-v cursor-move"></i>
                                                {!! Lte3::text("block[items][{$loop->index}][question]", Arr::get($item, 'question'), ['label' => 'Question']) !!}
                                                {!! Lte3::textarea("block[items][{$loop->index}][answer]", Arr::get($item, 'answer'), ['label' => 'Answer',]) !!}
                                                {!! Lte3::lfmImage("block[items][{$loop->index}][img]", Arr::get($item, 'img'), ['label' => 'Img'] ) !!}
                                                {!! Lte3::hidden("block[items][{$loop->index}][weight]", Arr::get($item, 'weight'), ['class' => 'js-input-weight']) !!}
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="js-msg-empty">Elements not created 😢</p>
                                    @endisset
                                </div>
                                <a href="" class="btn btn-info btn-xs float-right js-btn-add"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>

                    {!! Lte3::btnSubmit('Submit', 'action', 'save-dd') !!}
                    {!! Lte3::formClose() !!}
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
                <div class="modal-header">
                    <h4 class="modal-title">Large Modal</h4>
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"><p>One fine body&hellip;</p></div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endpush
