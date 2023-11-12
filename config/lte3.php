<?php

return [
    /*
     * HTML Logo
     */
    'logo' => '<b>Admin</b>LTE',

    /*
     * Dashboard Home
     */
    'dashboard_slug' => 'lte3',

    'routes' => true,

    'middleware' => [
        'web',
        \Fomvasss\Lte3\Http\Middleware\LteRequestOptions::class,
    ],

    'view' => [

        'dark_mode' => false,

        'preloader' => false,

        /**
         * Show next type alerts in dashboard
         * Example alert: \Session::flash('success', 'Welcome to LTE!');
         * Available types: success, info, warning, error
         *
         */
        'alerts' => [
            'toastr',
            //'sweetalert',
            //'bootstrap',
        ],

        'sidebar' => [
            'search' => true,
        ],

        'components' => [
            'form' => ['blade' => 'lte3::components.form', 'default' => ['files' => true]],
            'btnSubmit' => ['blade' => 'lte3::components.btnSubmit', 'vars' => ['title', 'name', 'value', 'attrs']],
            'btnReset' => ['blade' => 'lte3::components.btnReset', 'vars' => ['title', 'attrs']],
            'btnModalClose' => ['blade' => 'lte3::components.btnModalClose', 'vars' => ['title', 'attrs']],
            'hidden' => ['blade' => 'lte3::components.hidden', 'vars' => ['name', 'value', 'attrs']],
            'text' => ['blade' => 'lte3::components.text', 'vars' => ['name', 'value', 'attrs'], 'default' => ['type' => 'text']],
            'number' => ['blade' => 'lte3::components.text', 'vars' => ['name', 'value', 'attrs'], 'default' => ['type' => 'number', 'default' => 0,]],
            'email' => ['blade' => 'lte3::components.text', 'vars' => ['name', 'value', 'attrs'], 'default' => ['type' => 'email']],
            'url' => ['blade' => 'lte3::components.text', 'vars' => ['name', 'value', 'attrs'], 'default' => ['type' => 'url']],
            'password' => ['blade' => 'lte3::components.text', 'vars' => ['name', 'value', 'attrs'], 'default' => ['type' => 'password']],
            'slug' => ['blade' => 'lte3::components.slug', 'vars' => ['name', 'value', 'attrs']],
            'textarea' => ['blade' => 'lte3::components.textarea', 'vars' => ['name', 'value', 'attrs']],
            'checkbox' => ['blade' => 'lte3::components.checkbox', 'vars' => ['name', 'value', 'attrs']],
            'radiogroup' => ['blade' => 'lte3::components.radiogroup', 'vars' => ['name', 'selected', 'options', 'attrs']],
            'links' => ['blade' => 'lte3::components.links', 'vars' => ['name', 'items', 'attrs']],
            'lists' => ['blade' => 'lte3::components.lists', 'vars' => ['name', 'items', 'attrs']],
            'colorpicker' => ['blade' => 'lte3::components.colorpicker', 'vars' => ['name', 'value', 'attrs']],
            'select2' => ['blade' => 'lte3::components.select2', 'vars' => ['name', 'selected', 'options', 'attrs']],
            'range' => ['blade' => 'lte3::components.range', 'vars' => ['name', 'value', 'attrs']],

            'select2Tree' => ['blade' => 'lte3::components.select2Tree', 'vars' => ['name', 'attrs']],
            'treeview' => ['blade' => 'lte3::components.treeview', 'vars' => ['name', 'attrs']],
            'nestedset' => ['blade' => 'lte3::components.nestedset.tree', 'vars' => ['terms', 'attrs'], 'default' => ['item' => 'lte3::components.nestedset.item']],

            'xEditable' => ['blade' => 'lte3::components.xEditable', 'vars' => ['name', 'value', 'attrs']],
            'datepicker' => ['blade' => 'lte3::components.datepicker', 'vars' => ['name', 'value', 'attrs'], 'default' => ['timezone' => env('APP_TIMEZONE_CLIENT', 'Europe/Kiev'), 'default' => now()->startOfDay()]],
            'timepicker' => ['blade' => 'lte3::components.timepicker', 'vars' => ['name', 'value', 'attrs'], 'default' => ['timezone' => env('APP_TIMEZONE_CLIENT', 'Europe/Kiev'), 'default' => now()]],
            'datetimepicker' => ['blade' => 'lte3::components.datetimepicker', 'vars' => ['name', 'value', 'attrs'], 'default' => ['timezone' => env('APP_TIMEZONE_CLIENT', 'Europe/Kiev'), 'default' => now()]],

            'file' => ['blade' => 'lte3::components.file', 'vars' => ['name', 'path', 'attrs']],
            'fileForm' => ['blade' => 'lte3::components.fileForm', 'vars' => ['name', 'attrs']],
            'lfmFile' => ['blade' => 'lte3::components.lfmFile', 'vars' => ['name', 'path', 'attrs']],
            'lfmImage' => ['blade' => 'lte3::components.lfmFile', 'vars' => ['name', 'path', 'attrs'], 'default' => ['lfm_category' => 'image', 'is_image' => 1]],
            'mediaFile' => ['blade' => 'lte3::components.mediaFile', 'vars' => ['name', 'model', 'attrs']],
            'mediaImage' => ['blade' => 'lte3::components.mediaFile', 'vars' => ['name', 'model', 'attrs'], 'default' => ['is_image' => true, 'accept' => 'image/*']],
        ],

        'field_attrs' => [
            'autocomplete',
            'autofocus',
            'accept',
            'placeholder',
            'required',
            'disabled',
            'readonly',
            'max',
            'min',
            'step',
            'rows',
            'title',
            'alt',
            'style',
            'id',
            'data-name',
        ],

        'next_destination_key' => '_destination',

        'modal_key' => '_modal',

        'pagination' => [
            'simple_view' => 'pagination::simple-bootstrap-5',
            'view' => 'pagination::bootstrap-5',
        ],
    ],
];
