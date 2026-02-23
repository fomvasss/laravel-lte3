@extends('lte3::layouts.app')
{{--

@section('btn-content-header')
    <a href="#" class="btn btn-flat btn-default mb-1" data-toggle="tooltip" title="Export"><i class="fa fa-upload"></i> </a>
    <a href="#" class="btn btn-flat btn-success mb-1"><i class="fa fa-plus"></i></a>
@endsection
--}}

@section('content')
    @include('lte3::parts.content-header', [
        'page_title' => 'Components: 42',
        'url_back' => '#',
        'url_create' => Lte3::backUrl('admin.components.index'), // add _back to GET params or set route name admin.components.index
        'btn_search' => true,
        //'btn_filter' => true,
        'btn_filter' => [['title' => 'Tomorow', 'url' => \Illuminate\Support\Facades\URL::current() . '?_f=1&datetime_from='.now()->subDay()->toDateString() .'&'. 'datetime_to='. now()->subDay()->toDateString()]],
    ])

    <!-- Main content -->
    <section class="content">

        <!-- TOP BUTTONS -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="btn-group margin-bottom mt-1">
                    <button type="button" class="btn btn-flat btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-plus"></i> Add
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu" style="max-height: 500px; overflow-y: scroll">
                        <a href="#" data-target="#modal-xl" class="dropdown-item js-modal-fill-html">Article</a>
                        <a href="#" data-target="#modal-xl" class="dropdown-item js-modal-fill-html">Product</a>
                    </div>
                </div>

                <a href="#" class="btn btn-flat btn-success mt-1"><i class="fa fa-plus"></i> Create</a>
                <a href="#" class="btn btn-flat btn-warning mt-1" data-toggle="modal" data-target="#my-modal-lg"><i class="far fa-calendar-plus"></i> Modal static</a>
                <a href="#" class="btn btn-flat btn-primary mt-1 js-modal-fill-html" data-target="#modal-lg" data-url="{{route('lte3.data.modal-content', ['modal' => 'lg'])}}" data-fn-inits="initSelect2"><i class="fas fa-sync-alt"></i></i> Modal Ajax</a>
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

        <!-- STATIC OPEN FILTER -->
        {{--@include('lte3::examples.inc.filter')--}}

        <!-- FILTER -->
        @include('lte3::examples.inc.filter2')

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
                @php
                    $tableOptions = session()->get('table-options', [])['orders'] ?? [];
                    $columns = [
                        [
                            'key' => 'sort',
                            'name' => 'Sort',
                        ],
                        [
                            'key' => 'actions',
                            'name' => 'Actions Btn',
                        ],
                        [
                            'key' => 'actions2',
                            'name' => 'Actions 2',
                            'default' => false
                        ],
                        [
                            'key' => 'secret',
                            'name' => 'Secret',
                            'hidden' => true
                        ],
                        [
                            'key' => 'img',
                            'name' => 'Photo',
                        ],
                        [
                            'key' => 'name',
                            'name' => 'Name',
                        ],
                        [
                            'key' => 'members',
                            'name' => 'Members',
                        ],
                        [
                            'key' => 'sum',
                            'name' => 'Sum',
                        ],
                        [
                            'key' => 'progress',
                            'name' => 'Progress',
                        ],
                        [
                            'key' => 'status',
                            'name' => 'Status',
                        ],
                        [
                            'key' => 'buttons',
                            'name' => 'Buttons',
                        ],
                    ];
                @endphp
                <table class="table table-hover js-table-options-columns" data-options='@json($tableOptions)' data-columns='@json($columns)'>
                    <thead>
                    <tr>
                        <th class="js-table-options-sort" style="width: 1%">#</th>
                        <th class="js-table-options-actions" style="width: 10px;"></th>
                        <th class="js-table-options-actions2"></th>
                        <th class="js-table-options-secret">Secret</th>
                        <th class="js-table-options-img"></th>
                        <th class="js-table-options-name" style="width: 15%">Name</th>
                        <th class="js-table-options-members" style="width: 20%">Members</th>
                        <th class="js-table-options-sum">Sum</th>
                        <th class="js-table-options-progress">Progress</th>
                        <th class="js-table-options-status" style="width: 8%">Status</th>
                        <th class="js-table-options-buttons" style="width: 20%"></th>
                    </tr>
                    </thead>
                    <tbody class="sortable-y" data-url="{{ route('lte3.data.save') }}">
                    @foreach($progects as $progect)
                        <tr id="{{ $loop->index }}" class="va-center">
                            <td class="js-table-options-sort"><i class="fas fa-sort"></i></td>
                            <td class="js-table-options-actions">
                                <div class="btn-actions dropdown">
                                    <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                    <div class="dropdown-menu" role="menu" style="top: 93%;">
                                        <a href="#" class="dropdown-item">Edit</a>
                                        <a href="#" class="dropdown-item"
                                           data-confirm="Clone?">Clone</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="{{ route('lte3.data.save') }}"
                                           class="dropdown-item js-ajax-send" data-method="delete"
                                           data-confirm="Remove?">Remove</a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right js-table-options-actions2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-default">Action</button>
                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle dropdown-icon"
                                            data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="top: 93%;">
                                        <a href="#" class="dropdown-item">Clone</a>
                                        <a href="#" class="dropdown-item">Notify</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td class="js-table-options-secret">Secret</td>
                            <td class="js-table-options-img">
                                <a href="/vendor/lte3/img/no-image.png" class="js-popup-image">
                                    <img src="/vendor/lte3/img/no-image.png" class="img-thumbnail" style="max-width: 100px">
                                </a>
                            </td>
                            <td class="js-table-options-name">
                                <a class="hover-edit" href="#">{{ $progect['name'] }}</a>
                                <br/>
                                <small class="js-clipboard with-mark">Created {{ $progect['created_at'] }}</small>
                            </td>
                            <td class="js-table-options-members">
                                <ul class="list-inline m-0">
                                    @foreach($progect['images'] as $img)
                                    <li class="list-inline-item js-popup-images">
                                        <a href="{{ url($img) }}"><img src="{{ url($img) }}" class="table-avatar" alt="Avatar"></a>
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="js-table-options-sum">$<span class="js-num-format text-nowrap">{{ $progect['sum'] }}</span></td>
                            <td class="js-table-options-progress">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         aria-valuenow="{{$progect['progress']}}"
                                         aria-valuemin="0" aria-valuemax="100" style="width: {{$progect['progress']}}%">
                                    </div>
                                </div>
                                <small class="text-nowrap"> {{$progect['progress']}}% Complete </small>
                            </td>
                            <td class="js-table-options-status">
                                <a href="#" class="hover-edit js-modal-fill-html"
                                    data-target="#modal-lg"
                                    data-url="{{route('lte3.data.modal-content', ['modal' => 'lg'])}}"
                                >
                                    <span class="btn btn-sm bg-gradient-warning btn-flat">{{ $progect['status'] }}</span>
                                </a>
                            </td>
                            <td class="text-right js-table-options-buttons">
                                <a href="#" class="btn btn-default btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                <a href="{{ route('lte3.data.save') }}" class="btn btn-danger btn-sm js-ajax-send"
                                   data-confirm="Delete?"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {!! Lte3::tableOptions($columns, $tableOptions, [
                'action' => route('lte3.data.save', ['key' => 'table-options']),
                'method' => 'post',
                'table' => 'orders',
                'name' => 'value',
                'preloader' => true,
            ]) !!}

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
                            'attrs' => ['custom-attrs' => 'custom-val'],
                        ]) !!}

                        {!! Lte3::text('tokens', 'Example tokens ', ['tokens' => ['[user:name]' => 'Name', '[user:phone]' => 'Phone']]) !!}

                        {!! Lte3::text('city', 'Lutsk', ['hidden_wrap' => 1]) !!}

                        {!! Lte3::text('default', null, [
                            'default' => 'Default value',
                            'append' => ['<i class="fas fa-fingerprint"></i>', '<a href="https://www.google.com/" target="_blank"><i class="fab fa-google"></i></a>']
                        ]) !!}

                        {!! Lte3::search('search', null, ['placeholder' => 'Enter text...']) !!}

                        {!! Lte3::text('secret', 'Some secret string :)', ['secret' => true]) !!}
                        {{-- OR --}}
                        {!! Lte3::secret('token', 'some-secret-token') !!}

                        {!! Lte3::password('Password') !!}

                        {!! Lte3::password('password_new', null, [
                            'label' => 'Password Generator',
                            'append' => '<i class="fas fa-sync js-passgen" data-complexity="4" data-length-from="8" data-length-to="16"></i>',
                            'help' => '* Complexity from 1 to 5, length from 4 to 128',
                        ]) !!}

                        {!! Lte3::number('Age', null, ['default' => 18, 'max' => '100', 'min' => 1, 'secret' => true]) !!}

                        {!! Lte3::text('formatter', null, [
                            'default' => 'some example formatter',
                            'formatter' => fn($v) => \Illuminate\Support\Str::upper($v),
                            //'formatter' => 'some_upper_helper',
                            //'formatter' => \App\Formatters\UpperFormatter::class,
                        ]) !!}

                        {!! Lte3::text('Calculator', '3+4*2', ['class' => 'js-input-calc', 'help' => '* Press Enter for calc']) !!}

                        {!! Lte3::url('url', null, [
                            'default' => 'https://stackoverflow.com/',
                        ]) !!}

                        {!! Lte3::email('email', 'fom@app.com', [
                            'label' => 'Your Email',
                            'help' => '* Enter Email',
                            'prepend' => '<i class="fas fa-envelope"></i>',
                            'append' => '<i class="fas fa-check"></i>',
                            'disabled' => 2,
                            'checkbox' => ['name' => 'verify', 'title' => 'Verify', 'value' => 0, 'readonly' => 1,]
                        ]) !!}

                        {!! Lte3::hidden('__tmp', '666', ['label' => 'Hidden field']) !!}

                        {!! Lte3::slug('slug', 'qwerty', ['label' => 'Slug']) !!}

                        {!! Lte3::colorpicker('colorpicker', null, ['label' => 'Color', 'default' => '#FFFFFF']) !!}

                        {!! Lte3::colorpicker('colorpicker2', null, ['label' => 'Color2', 'transparent' => true]) !!}

                        {!! Lte3::colorpicker('colorpicker3', null, ['label' => 'Color3', 'url_save' => route('lte3.data.save')]) !!}

                        {!! Lte3::range('age', 18, ['min' => 12, 'max' => 100, 'step' => 1]) !!}

                        {!! Lte3::checkbox('archived', null, ['label' => 'Archived', 'is_simple' => true, 'readonly' => 1]) !!}

                        {!! Lte3::checkbox('publish', null, ['label' => 'Publish', 'readonly' => 0]) !!}

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
                            <div class="col-md">
                                {!! Lte3::checkboxes('rd', ['morning', 'day'], [
                                    ['id' => 'night', 'name' => 'Night', 'disabled' => true],
                                    ['id' => 'morning', 'name' => 'Morning'],
                                    'day' => 'Day',
                                    'evening',
                                    'dd',
                                ], [
                                    'help' => '* Some text',
                                    'label' => 'Time of day',
                                    'field_id_prefix' => 'qq',
                                ]) !!}
                            </div>
                            <div class="col-md">
                                {!! Lte3::checkboxes('car', ['audi', 'tesla'], [
                                    ['id' => 'audi', 'name' => 'Audi',],
                                    ['id' => 'tesla', 'name' => 'Tesla',],
                                    ['id' => 'bmw', 'name' => 'BMW',],
                                    ['id' => 'mercedes', 'name' => 'Mercedes',],
                                    ['id' => 'ford', 'name' => 'Ford', 'disabled' => true,],
                                ], [
                                    'label' => 'Cars',
                                    'url_save' => route('lte3.data.save'),
                                    'method_save' => 'POST',
                                ]) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                {!! Lte3::radiogroup('payment', null, ['paypal' => 'PayPal', 'fondy' => 'Fondy', 'liqpay' => 'LiqPay',], [
                                    'default' => 'liqpay',
                                    'class_wrap' => 'row',
                                    'x-model' => 'testPayment'
                                ]) !!}
                            </div>
                            <div class="col-md-3">
                                {!! Lte3::radiogroup('size', 'm', ['s' => 'Small', 'm' => 'Medium', 'l' => 'Large',], ['label' => 'Size:']) !!}
                            </div>
                            <div class="col-md-3">
                                {!! Lte3::radiogroup('brand', 'samsung', [
                                        'apple' => ['label' => 'Apple', 'url' => route('lte3.data.save', ['brand' => 'apple'])],
                                        'samsung' => ['label' => 'Samsung', 'url' => route('lte3.data.save', ['brand' => 'samsung'])],
                                        'xiaomi' => ['label' => 'Xiaomi', 'url' => route('lte3.data.save', ['brand' => 'xiaomi']), 'disabled' => true],
                                    ], ['label' => 'Submit to URL:', 'submit_methor' => 'POST'])
                                !!}
                            </div>
                            <div class="col-md-3">
                                {!! Lte3::radiogroup('chanel', 'sms', ['push' => 'Push', 'email' => 'Email', 'sms' => 'SMS',], [
                                    'label' => 'Toggle Block:',
                                    'field_id_prefix' => 'ww',
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
                        <p>Bluer text: <span class="js-blur-text">Hello World!</span></p>

                    </div>
                    <div class="card-footer text-right">
                        {!! Lte3::btnReset('Reset', ['url' => '', 'disabled' => true]) !!}
                        {!! Lte3::btnSubmit('Cancel', 'action', 'cancel', ['disabled' => true]) !!}
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

                        {!! Lte3::select2('status', 'canceled', ['new' => 'New', 'pending' => 'Pending', 'canceled' => 'Canceled', 'delivered' => 'Delivered',], [
                            'label' => 'Status',
                            'disableds' => ['pending'],
                            'data' => ['sum' => 1234],
                        ]) !!}

                        {!! Lte3::select2('city', 'City', ['London', 'Kyiv', 'Warszawa'], [
                            'label' => 'City',
                            'disabled' => '--',
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
                                    'label' => 'Categories',
                                    'has_nested' => true,
                                    'root_btn_create' => 'Create',
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
                <!-- FIELD -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Lte3 Field</h3>
                    </div>
                    <div class="card-body">
                        {!! Lte3::field([
                            'type' => 'text',
                            'name' => 'nickname',
                            'value' => 'Nik',
                            'label' => 'Nickname',
                            'class' => 'some-class',
                            'data' => ['rr' => 'qq'],
                        ]) !!}

                        {!! Lte3::field([
                            'type' => 'select2',
                            'name' => 'rd2',
                            'multiple' => true,
                            'selected' => 'male',
                            'label' => 'Time of day',
                            'data' => ['tt' => 'yy'],
                            'options' => [
                                ['id' => 'night', 'name' => 'Night'],
                                ['id' => 'morning', 'name' => 'Morning'],
                                'day' => 'Day',
                                'evening',
                            ],
                        ]) !!}

                        {!! Lte3::field([
                            'type' => 'checkboxes',
                            'name' => 'rd',
                            'label' => 'Time of day',
                            'selected' => ['morning', 'day'],
                            'help' => '* Some help text',
                            'options' => [
                                ['id' => 'night', 'name' => 'Night'],
                                ['id' => 'morning', 'name' => 'Morning'],
                                'day' => 'Day',
                                'evening',
                            ],
                        ]) !!}
                    </div>
                </div>

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
                            Disabled Text:
                            {!! Lte3::xEditable('name', 'Space Odyssey 2001', [
                                'type' => 'text',
                                'pk' => 1,
                                'url_save' => route('lte3.data.save'),
                                'disabled' => 1,
                            ]) !!}
                        </div>
                        <div>
                            Textarea:
                            {!! Lte3::xEditable('comment', 'A film that explores the psychological and emotional state of a man whose life revolves around his family, Interstellar is a thrilling and thought-provoking', [
                                //'limit_title' => 10,
                                'type' => 'textarea',
                                'field_name' => 'data[comment]',
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
                        ], ['label' => '',]) !!}

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

                        {!! Lte3::formOpen(['action' => route('lte3.data.save'), 'files' => true,]) !!}

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
                        <h3 class="card-title">highlight.js</h3>
                    </div>

                    <div class="card-body">
                        <div class="f-highlight language-html">
                            {{ "<h3>Hello highlight</h3><p>This example text</p>" }}
                        </div>
                        <hr>
                        <div class="f-highlight language-php">
                            $a = 5;
                            phpInfo();
                            $request->input('name');
                        </div>
                        <hr>
                        <div class="f-highlight language-javascript">
                            hljs = require('highlight.js');
                            html = hljs.highlightAuto('<h1>Hello World!</h1>').value
                        </div>


                    </div>
                    <div class="card-footer">
                        Visit <a href="https://highlightjs.org/" target="_blank">highlight.js</a> documentation for
                        more examples and information about the plugin.
                        Github <a href="https://github.com/highlightjs/highlight.js">repo</a>
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
                        <h3 class="card-title">EasyMDE</h3>
                    </div>

                    <div class="card-body">
                        <!-- Easy MD Editor -->
                        {!! Lte3::textarea('body_md', null, ['class' => 'f-md-editor']) !!}
                    </div>
                    <div class="card-footer">
                        Visit <a href="https://github.com/Ionaru/easy-markdown-editor" target="_blank">EasyMDE</a> documentation for
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

        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Editor.js</h3>
            </div>

            <div class="card-body">
                {!! Lte3::formOpen(['action' => route('lte3.data.save', ['action' => 'save-dd', 'key' => 'editorjs-data', 'value_key' => 'editorjs_data']), null, 'method' => 'POST']) !!}
                <div id="editorjs" data-lfm-image-folder="images" data-lfm-file-folder="files" style="height:450px; border: 1px solid #ddd; border-radius: .25rem" class="mb-3"></div>
                {!! Lte3::hidden('editorjs_data', session()->get('editorjs-data') ?: '{"time":1729844966856,"blocks":[{"id":"tCGreiYH5q","type":"header","data":{"text":"&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Editor.js","level":2},"tunes":{"textVariant":""}},{"id":"qOmb7csMW2","type":"list","data":{"style":"ordered","items":["add div with id -&nbsp;editorjs","add hidden input with any name (ex. editorjs_data) and id -&nbsp;editorjs_data"]},"tunes":{"textVariant":""}}],"version":"2.30.6"}', [
                    'id' => 'editorjs_data',
                ]) !!}

                {!! Lte3::btnSubmit('Submit') !!}
                {!! Lte3::formClose() !!}
            </div>
            <div class="card-footer">
                Visit <a href="https://editorjs.io/" target="_blank">Editor.js</a>
                documentation for more examples and information about the plugin.
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
                                            <i class="fas fa-sort cursor-move"></i>
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
                                                <i class="fas fa-sort cursor-move"></i>
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


        <!-- ChartJS 4 -->
        <div class="row">
            <div class="col-md-12">
                <canvas id="myChart"></canvas>
            </div>
        </div>

    </section>
@endsection

@push('styles')
    <!-- EasyMDE -->
    <link rel="stylesheet" href="/vendor/lte3/plugins/easy-markdown-editor/easymde.min.css">

    <!-- summernote -->
    <link rel="stylesheet" href="/vendor/adminlte/plugins/summernote/summernote-bs4.min.css">

    <!-- CodeMirror -->
    <link rel="stylesheet" href="/vendor/adminlte/plugins/codemirror/codemirror.css">
    <link rel="stylesheet" href="/vendor/adminlte/plugins/codemirror/theme/monokai.css">

    <!-- highlight.js -->
    <link rel="stylesheet" href="/vendor/lte3/plugins/highlightjs/styles/default.min.css">
@endpush

@push('scripts')
    <!-- ChartJS 4 -->
    <script src="/vendor/lte3/plugins/chartjs4/chart.js"></script>

    <!-- EasyMDE -->
    <script src="/vendor/lte3/plugins/easy-markdown-editor/easymde.min.js"></script>

    <!-- Summernote -->
    <script src="/vendor/adminlte/plugins/summernote/summernote-bs4.min.js"></script>

    <!-- CodeMirror -->
    <script src="/vendor/adminlte/plugins/codemirror/codemirror.js"></script>
    <script src="/vendor/adminlte/plugins/codemirror/mode/css/css.js"></script>
    <script src="/vendor/adminlte/plugins/codemirror/mode/xml/xml.js"></script>
    <script src="/vendor/adminlte/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>

    <!-- highlight.js -->
    <script src="/vendor/lte3/plugins/highlightjs/highlight.min.js"></script>
    <script src="/vendor/lte3/plugins/highlightjs/languages/php.min.js"></script>
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

@push('scripts')
    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush