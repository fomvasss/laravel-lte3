<script2>
    xEditable = {mode:"inline", inputclass:"form-control-sm"},
    colorpickerOptions = {},
    datetimepickerOptions = {
        format: 'Y-m-d H:i:s'
    },
    datepickerOptions = {
        timepicker:false,
        format:'Y-m-d'
    },
    timepickerOptions = {
        datepicker:false,
        format: 'H:i:s',
    }
</script2>

<script>
    const LANGUAGE = $('html').attr('lang') || 'en';

    var initEditors = function () {},
        initDatetimepickerOptions = function () {};

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
        $('.f-summernote').summernote()

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

    // Component: xEditable
    // https://vitalets.github.io/x-editable/docs.html
    // https://coderthemes.com/moltran/layouts/red-vertical/form-xeditable.html
    $(".f-x-editable").editable({
        mode:"inline",inputclass:"form-control-sm",
        success: function(response, newValue) {
            if (response.message) toastr.success(response.message);
        }
    })
    $.fn.editableform.buttons =
        '<button type="submit" class="btn btn-primary editable-submit btn-sm waves-effect waves-light"><i class="fa fa-check"></i></button><button type="button" class="btn btn-danger editable-cancel btn-sm waves-effect"><i class="fa fa-times"></i></button>';

</script>

{{-- CKEditor --}}
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
        allowedContent: true,
        removePlugins: "exportpdf",
        filebrowserImageBrowseUrl: '/filemanager?type=Images',
        filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '/filemanager?type=Files',
        filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
    };

    // CKEditor
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


<form action="" hidden class="hidden" method="POST" id="js-action-form">
    @csrf
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="{{ config('lte3.view.next_destination_key', '_destination') }}" value="{{ Request::fullUrl() }}" class="f-dest">
</form>

@stack('modals')
<div class="modal fade" id="modal-sm"><div class="modal-dialog modal-sm"><div class="modal-content"></div></div></div>
<div class="modal fade" id="modal-lg"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<div class="modal fade" id="modal-xl"><div class="modal-dialog modal-xl"><div class="modal-content"></div></div></div>
