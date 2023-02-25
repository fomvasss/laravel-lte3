<?php

return [
    'logo' => '<b>Admin</b>LTE',

    /*
     * Dashboard Home, example: /admin
     */
    'dashboard_url' => '/lte3',

    'middleware' => ['web'],

    'view' => [
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
            'submit' => ['blade' => 'lte3::components.submit', 'vars' => ['title', 'name', 'value', 'attrs']],
            'hidden' => ['blade' => 'lte3::components.hidden', 'vars' => ['name', 'value', 'attrs']],
            'text' => ['blade' => 'lte3::components.text', 'vars' => ['name', 'value', 'attrs']],
            'slug' => ['blade' => 'lte3::components.slug', 'vars' => ['name', 'value', 'attrs']],
            'textarea' => ['blade' => 'lte3::components.textarea', 'vars' => ['name', 'value', 'attrs']],
            'checkbox' => ['blade' => 'lte3::components.checkbox', 'vars' => ['name', 'value', 'attrs']],
            'radiogroup' => ['blade' => 'lte3::components.radiogroup', 'vars' => ['name', 'selected', 'options', 'attrs']],
            'links' => ['blade' => 'lte3::components.links', 'vars' => ['name', 'items', 'attrs']],
            'lists' => ['blade' => 'lte3::components.lists', 'vars' => ['name', 'items', 'attrs']],
            'colorpicker' => ['blade' => 'lte3::components.colorpicker', 'vars' => ['name', 'value', 'attrs']],
            'select2' => ['blade' => 'lte3::components.select2', 'vars' => ['name', 'selected', 'options', 'attrs']],

            'select2Tree' => ['blade' => 'lte3::components.select2Tree', 'vars' => ['name', 'attrs']],
            'treeview' => ['blade' => 'lte3::components.treeview', 'vars' => ['name', 'attrs']],
            'nestedset' => ['blade' => 'lte3::components.nestedset.tree', 'vars' => ['terms', 'attrs'], 'default' => ['item' => 'lte3::components.nestedset.item']],

            'xEditable' => ['blade' => 'lte3::components.xEditable', 'vars' => ['name', 'value', 'attrs']],
            'datepicker' => ['blade' => 'lte3::components.datepicker', 'vars' => ['name', 'value', 'attrs']],
            'timepicker' => ['blade' => 'lte3::components.timepicker', 'vars' => ['name', 'value', 'attrs']],
            'datetimepicker' => ['blade' => 'lte3::components.datetimepicker', 'vars' => ['name', 'value', 'attrs']],

            'file' => ['blade' => 'lte3::components.file', 'vars' => ['name', 'path', 'attrs']],
            'fileForm' => ['blade' => 'lte3::components.fileForm', 'vars' => ['name', 'attrs']],
            'mediaFile' => ['blade' => 'lte3::components.mediaFile', 'vars' => ['name', 'model', 'attrs']],
        ],

        'field_attrs' => ['autocomplete', 'autofocus', 'placeholder', 'required', 'disabled', 'readonly', 'max', 'min', 'step', 'rows', 'title', 'alt'],

        'next_destination_key' => '_destination'
    ],


];
