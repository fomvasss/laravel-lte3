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

        https://xdsoft.net/jqplugins/datetimepicker/#lang
        $.datetimepicker.setLocale('uk');
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
                if (response.message) {
                    if (response.status === 'error') {
                        toastr.error(response.message)
                    } else {
                        toastr.success(response.message);
                    }
                }
                if (response.operation === 'reload') {
                    window.location.reload()
                }
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

    if ($('.f-highlight').length) {
        $('.f-highlight').each(function(i, block) {
            hljs.highlightBlock(block);
        });
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
            entity_encoding : "raw",
            skin: '{{ config('lte3.view.dark_mode') ? "oxide-dark" : "oxide" }}',

            // URL & file paths
            document_base_url: pathAbsolute,
            remove_script_host : false,
            relative_urls: false,
            path_absolute : pathAbsolute,

            target_list: [
                { title: 'None', value: '' },
                { title: 'Open in new tab', value: '_blank' }
            ],

            // LFM integration
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
            },

            // Register the cite format
            formats: {
                cite: {block: 'cite'}
            },
            // Populate the styleselect menu
            style_formats: [
                { title: 'Cite', format: 'cite' },
                { title: 'Blocks', items: [
                        { title: 'Black', block: 'div', wrapper: true, classes: 'black-block' },
                        { title: 'Green', block: 'div', wrapper: true, classes: 'green-block' },
                        { title: 'Blue', block: 'div', wrapper: true, classes: 'blue-block' },
                        { title: 'Yellow', block: 'div', wrapper: true, classes: 'yellow-block' },
                    ]
                }
            ],
            // This removes the WYSIWYG formatting within the styleselect menu
            preview_styles: false,
            content_style: 'div.black-block { background: black; color: white; } div.green-block { background: green; color: white; } div.blue-block { background: blue; color: white; } div.yellow-block { background: yellow; color: black; }',


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

@if(is_dir(public_path('vendor/editorjs')))
    <!-- Editor.js Плагін для заголовків -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
    <!-- Editor.js Плагін для списків -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
    <!-- Editor.js Плагін для checklist -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script>
    <!-- Editor.js Плагін для quote -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
    <!-- Editor.js Плагін для embed -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script>
    <!-- Editor.js Плагін для table -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script>
    <!-- Editor.js Плагін для image -->
{{--    <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>--}}
    <!-- Editor.js Плагін для simple-image -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script>
    <!-- Editor.js Плагін для raw -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/raw@latest"></script>
    <!-- Editor.js Плагін для marker -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script>
    <!-- Editor.js Плагін для warning -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest"></script>
    <!-- Editor.js Плагін для text-variant-tune -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/text-variant-tune@latest"></script>
    <!-- Editor.js -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="/vendor/editorjs/editorjs.mjs" referrerpolicy="origin"></script>

    <script>
        var lfmImageFolder = $('#editorjs').data('lfm-image-folder') ?? 'images';
        var lfmFileFolder = $('#editorjs').data('lfm-file-folder') ?? 'files';

        class Lfm {
            static get toolbox() {
                return {
                    title: 'LFM',
                    icon: '<svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 3v6h6M12 18v-6M9 15l3-3 3 3" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                };
            }

            constructor({ data, api }) {
                this.data = data || {};
                this.api = api;
                this.wrapper = null;
            }

            render() {
                this.wrapper = document.createElement('div');

                if (this.data && this.data.url) {
                    this._displayContent(this.data.url, this.data.type);
                } else {
                    const imageButton = document.createElement('button');
                    imageButton.type = 'button';
                    imageButton.innerText = 'Select Image';
                    imageButton.addEventListener('click', () => {
                        this.openFileManager('image');
                    });

                    const fileButton = document.createElement('button');
                    fileButton.type = 'button';
                    fileButton.innerText = 'Select File';
                    fileButton.addEventListener('click', () => {
                        this.openFileManager('file');
                    });

                    this.wrapper.appendChild(imageButton);
                    this.wrapper.appendChild(fileButton);
                }

                return this.wrapper;
            }

            _displayContent(url, type) {
                this.wrapper.innerHTML = '';

                if (type === 'image') {
                    const image = document.createElement('img');
                    image.src = url;
                    image.style.cursor = 'pointer';
                    image.style.maxWidth = '100%';
                    image.addEventListener('click', () => {
                        this.openFileManager('image');
                    });
                    this.wrapper.appendChild(image);
                } else {
                    const link = document.createElement('a');
                    link.href = url;
                    link.innerText = 'Open file';
                    link.target = '_blank';
                    link.style.cursor = 'pointer';
                    link.addEventListener('click', (event) => {
                        event.preventDefault();
                        this.openFileManager('file');
                    });
                    this.wrapper.appendChild(link);
                }
            }

            openFileManager(type) {
                var folder = type === 'image' ? lfmImageFolder : lfmFileFolder;
                window.open(`/filemanager?type=${folder}`, 'File', 'width=900,height=600');
                window.SetUrl = (url) => {
                    if (Array.isArray(url) && url.length > 0 && url[0].url) {
                        const fileUrl = url[0].url;
                        this._displayContent(fileUrl, type);
                        this.data = { url: fileUrl, type: type };
                    } else {
                        console.error('Invalid data format for file');
                    }
                };
            }

            save(blockContent) {
                return this.data;
            }
        }

        var initEditorJS = function() {
            const editorjsDataElement = document.getElementById('editorjs_data');
            if (editorjsDataElement) {
                const savedData = JSON.parse(editorjsDataElement.value || '{}');

                let tools = {};
                let tunes = [];

                if (typeof Lfm !== 'undefined') {
                    tools.lfm = {
                        class: Lfm,
                        shortcut: 'CMD+SHIFT+F',
                    };
                }

                if (typeof Header !== 'undefined') {
                    tools.header = {
                        class: Header,
                        shortcut: 'CMD+SHIFT+H',
                    };
                }

                if (typeof List !== 'undefined') {
                    tools.list = List;
                }

                if (typeof Checklist !== 'undefined') {
                    tools.checklist = Checklist;
                }

                if (typeof Quote !== 'undefined') {
                    tools.quote = Quote;
                }

                if (typeof TextVariantTune !== 'undefined') {
                    tunes.push('textVariant');
                    tools.textVariant = TextVariantTune;
                }

                if (typeof Embed !== 'undefined') {
                    tools.embed = Embed;
                }

                if (typeof RawTool !== 'undefined') {
                    tools.raw = RawTool;
                }

                if (typeof Marker !== 'undefined') {
                    tools.marker = {
                        class: Marker,
                        shortcut: 'CMD+SHIFT+M',
                    };
                }

                if (typeof Warning !== 'undefined') {
                    tools.warning = {
                        class: Warning,
                        inlineToolbar: true,
                        config: {
                            titlePlaceholder: 'Title',
                            messagePlaceholder: 'Message',
                        },
                    };
                }

                if (typeof Table !== 'undefined') {
                    tools.table = Table;
                }

                const editor = new EditorJS({
                    holder: 'editorjs',
                    tools: tools,
                    tunes: tunes,
                    data: savedData
                });

                editorjsDataElement.closest('form')?.addEventListener('submit', function(e) {
                    e.preventDefault();

                    editor.save().then((outputData) => {
                        editorjsDataElement.value = JSON.stringify(outputData);
                        this.submit();
                    }).catch((error) => {
                        console.error('Saving failed: ', error);
                    });
                });
            }
        };

        initEditorJS();
    </script>
@endif
