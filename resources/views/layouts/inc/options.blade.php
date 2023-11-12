<form action="" class="hidden" method="POST" id="js-action-form" style="display: none">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="{{ config('lte3.view.next_destination_key', '_destination') }}" value="{{ Request::fullUrl() }}" class="f-dest">
</form>

@stack('modals')

<div class="modal fade" id="modal-sm"><div class="modal-dialog modal-sm"><div class="modal-content"></div></div></div>
<div class="modal fade" id="modal-lg"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<div class="modal fade" id="modal-xl"><div class="modal-dialog modal-xl"><div class="modal-content"></div></div></div>

@php($modalKey = config('lte3.view.modal_key', '_modal'))
@if($modal = old($modalKey) ?: request($modalKey) ?: session()->get($modalKey))
    <script>
        $('{{$modal}}').modal()
    </script>
@endif

<script>
    const LANGUAGE = $('html').attr('lang') || 'en';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            //'X-EXAMPLE': 'your-var'
        }
    });

    var initEditors = function () {
        },
        initDatetimepickerOptions = function () {
        },
        initPopupImage = function () {
        }

    initDatetimepickerOptions = function() {
        // Component: datetimepicker
        // https://xdsoft.net/jqplugins/datetimepicker/
        $('.f-datetimepicker').datetimepicker({
            timepicker:true,
            format:'Y-m-d H:i:s',
        });
        $('.f-datepicker').datetimepicker({
            timepicker:false,
            format:'Y-m-d'
        });
        $('.f-timepicker').datetimepicker({
            datepicker:false,
            format: 'H:i:s'
        });
    }
    initDatetimepickerOptions();

    initEditors = function() {
        // Summernote
        $('.f-summernote').summernote({
            height: 300,
            lang: 'uk-UA'
        })

        // CodeMirror
        $('.f-codeMirror').each(function(index, elem){
            CodeMirror.fromTextArea(elem, {
                mode: "htmlmixed",
                lineNumbers: true,
                theme: "monokai",
                tabMode: "indent",
            });
        });
    }
    initEditors();

    // Popup Image
    initPopupImage = function () {
        $('.js-popup-image').magnificPopup({
            type:'image',
            zoom: {
                enabled: true,
                duration: 400,
            }
        });
        $('.js-popup-images').magnificPopup({
            delegate: 'a',
            type: 'image'
        });
    }
    initPopupImage();

    // Component: xEditable
    // https://vitalets.github.io/x-editable/docs.html
    // https://coderthemes.com/moltran/layouts/red-vertical/form-xeditable.html
    if ($(".f-x-editable").length) {
        $(".f-x-editable").editable({
            mode:"inline",inputclass:"form-control-sm",
            success: function(response, newValue) {
                if (response.message) toastr.success(response.message);
            }
        })
        $.fn.editableform.buttons =
            '<button type="submit" class="btn btn-primary editable-submit btn-sm waves-effect waves-light"><i class="fa fa-check"></i></button><button type="button" class="btn btn-danger editable-cancel btn-sm waves-effect"><i class="fa fa-times"></i></button>';
    }

    $('table tbody.sortable-y').sortable({
        helper: fixWidthHelper
    }).disableSelection();
    function fixWidthHelper(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    }

</script>

{{--
    CKEditor
    See official repository https://github.com/ckeditor/ckeditor4
    Download actual version https://ckeditor.com/ckeditor-4/download/
--}}
@if(is_dir(public_path('vendor/ckeditor')))
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>

<script>
    var ckMini = {
        language: LANGUAGE,
        toolbar: [
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
            },
            {name: 'links', items: ['Link', 'Image', 'Anchor']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ],
        removePlugins: "exportpdf",
    },
    ckSmall = {
        language: LANGUAGE,
        allowedContent: true,
        toolbar: [
            {
                name: 'basicstyles',
                items: ['Source', 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
            },
            {name: 'links', items: ['Link', 'Image', 'Anchor']},
            {name: 'styles', items: ['Format', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ],
        removePlugins: "exportpdf",
        filebrowserImageBrowseUrl: '/filemanager?type=Images',
        filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '/filemanager?type=Files',
        filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
    },
    ckFull = {
        language: LANGUAGE,
        height: "25em",
        allowedContent: true,
        removePlugins: "exportpdf",
        filebrowserImageBrowseUrl: '/filemanager?type=Images',
        filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '/filemanager?type=Files',
        filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
    };

    if ($('textarea.f-cke-mini').length) {
        $('textarea.f-cke-mini').ckeditor(ckMini || {})
    }

    if ($('textarea.f-cke-small').length) {
        $('textarea.f-cke-small').ckeditor(ckSmall || {})
    }

    if ($('textarea.f-cke-full').length) {
        $('textarea.f-cke-full').ckeditor(ckFull || {})
    }
</script>
@endif


{{--
    TinyMce
    See official docs
    Download actual version
--}}
@if(is_dir(public_path('vendor/tinymce')))
    <script src="/vendor/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        var pathAbsolute = "{{url('/')}}/",
            tinymceSelector = '.f-tinymce'
        tinymceOptions = {
            selector: tinymceSelector,
            // https://www.tiny.cloud/get-tiny/language-packages/
            language: LANGUAGE,
            // https://www.tiny.cloud/docs/tinymce/6/plugins/
            plugins: 'anchor code table lists autolink emoticons image link visualblocks media preview fullscreen wordcount',
            toolbar: 'fullscreen | undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | blockquote | bullist numlist | code | table | link | image | media | preview | wordcount',
            link_assume_external_targets: true,

            document_base_url: pathAbsolute,
            remove_script_host : false,
            relative_urls: false,

            relative_urls: false,
            path_absolute : pathAbsolute,
            file_picker_callback : function(callback, value, meta) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = pathAbsolute + 'filemanager?editor=' + meta.fieldname;
                if (meta.filetype == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.openUrl({
                    url : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no",
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }

        };
        var initTinyMce = function () {
            if ($(tinymceSelector).length) {
                tinymce.init(tinymceOptions);
            }
        }
        initTinyMce();
    </script>
@endif

@if(is_dir(public_path('vendor/laravel-filemanager')))
    <script src="/vendor/lte3/plugins/laravel-filemanager/js/stand-alone-button.js" referrerpolicy="origin"></script>
    <script>
        var initLfmBtn = function() {
            $('.f-lfm-btn').filemanager();
        }
        initLfmBtn();
    </script>
@endif
