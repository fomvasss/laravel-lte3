var initJsVerificationSlugField = function () {},
    initColorpicker = function () {},
    initSortableY = function () {},
    initSelect2 = function () {},
    initCheckbox = function () {},
    initSelect2Tree = function () {},
    initTreeview = function () {},
    initInputCalc = function () {},
    initTooltip = function () {},
    initMediaFile = function () {},
    initLfmFile = function () {}
;

// Ініціалізація полів у HTML, вставленому після завантаження сторінки: модалка .js-modal-fill-html, блоки .f-multyblocks і .mb-wrap,
// html з відповіді .js-ajax-send. Пакет викликає Lte3.init(root) сам, data-fn-inits потрібен лише для незареєстрованих функцій.
// Зареєстрована функція приймає root і безпечна при повторному виклику. Після неї на root тригериться подія lte3:init
var Lte3 = {
    inits: {},
    register: function (name, fn) {
        this.inits[name] = fn;
    },
    init: function (root) {
        $.each(this.inits, function (name, fn) {
            try {
                fn(root);
            } catch (e) {
                console.error('Lte3 init ' + name + ':', e);
            }
        });
        $(root).trigger('lte3:init');
    },
};

// Select2 4.0 кешує екземпляр під ключем data-select2-id, а без нього — під id елемента. Два select з однаковим id
// (поле на сторінці й таке саме в модалці) ділять ключ, і ініціалізація другого знищує перший.
// Тому перед ініціалізацією select отримує унікальний ключ: якщо його немає або він збігається з чужим (розмітка скопійована)
if ($.fn.select2) {
    (function (select2) {
        var uid = 0;

        $.fn.select2 = function (options) {
            if (typeof options !== 'string') {
                this.each(function () {
                    var key = this.getAttribute('data-select2-id');
                    if (!$(this).data('select2') && (key === null || $('[data-select2-id="' + CSS.escape(key) + '"]').length > 1)) {
                        this.setAttribute('data-select2-id', 'lte3-' + (++uid));
                    }
                });
            }
            return select2.apply(this, arguments);
        };
        $.extend($.fn.select2, select2);
    })($.fn.select2);
}

$(function () {
    'use strict';

    const USE_TOASTR = true;
    const LANGUAGE = $('html').attr('lang') || 'en';

    localStorage.setItem('AdminLTE:Demo:MessageShowed', (Date.now()) + (15 * 60 * 1000));

    $(document).ajaxStart(function () {
        Pace.restart();
    });

    // Init-функції полів приймають root (елемент чи jQuery) — шукають лише в ньому, разом із самим root; без root — увесь документ.
    // Повторний виклик безпечний: уже ініціалізовані поля пропускаються, обробники не дублюються.
    function scoped(root, selector) {
        return root ? $(root).find(selector).addBack(selector) : $(selector);
    }

    // Лишає елементи, які ще не ініціалізовані під ключем key, і позначає їх.
    // Прапорець — у jQuery data, тож розмітка, скопійована з уже ініціалізованого поля (шаблони блоків), ініціалізується заново
    function once($els, key) {
        return $els.filter(function () {
            if ($(this).data(key)) {
                return false;
            }
            $(this).data(key, true);
            return true;
        });
    }

    function callFnInits(str) {
        (str || '').split(/\s*,\s*/).forEach(function (fn) {
            if (typeof window[fn] === 'function') {
                window[fn]();
            } else if (fn) {
                console.warn('No such function:', fn);
            }
        });
    }

    initTooltip = function (root) {
        scoped(root, '[data-toggle="tooltip"]').tooltip();
    };
    initTooltip();

    // Show message
    function lteAlert(status, msg) {
        if (USE_TOASTR) {
            toastr[status](msg);
        }
    }

    // Copy text to clipboard
    $(document).on('click', '.js-clipboard', function (e) {
        e.preventDefault()
        var $tmp = $("<textarea>"),
            $text = $(this).data('text') || $(this).text();
        $("body").append($tmp);
        $tmp.val($text).select();
        document.execCommand("copy");
        $tmp.remove();
        lteAlert('success', 'Copied!');
    });

    // Insert token into the field at the cursor position
    $(document).on('click', '.js-token-insert', function (e) {
        e.preventDefault()
        var token = String($(this).data('text')),
            el = $(this).closest('.position-relative').find('input.form-control, textarea.form-control').get(0);

        if (!el) {
            return;
        }

        var editor = window.tinymce ? (tinymce.get() || []).find(function (ed) { return ed.targetElm === el; }) : null;

        if (editor) {
            editor.insertContent(token);
            return;
        }

        var start = el.selectionStart !== null ? el.selectionStart : el.value.length,
            end = el.selectionEnd !== null ? el.selectionEnd : el.value.length;

        el.value = el.value.slice(0, start) + token + el.value.slice(end);
        el.focus();
        el.setSelectionRange(start + token.length, start + token.length);
        $(el).trigger('input').trigger('change');
    });

    function setSidebarActiveable($naw, $item) {
        $naw.find('li>a').removeClass('active');
        $item.closest('.nav-pills>.nav-item').addClass('menu-open');
        $item.addClass('active');
        $item.closest('.menu-open').children('a').addClass('active');

        return true;
    }
    // LTE: Set active item in Sidebar menu
    $('.nav-sidebar.js-activeable').each(function() {
        var $naw = $(this),
            pathnameUrl = window.location.pathname,
            url = window.location.href,
            path = url.split('?')[0];

        $naw.find('li>a').each(function () {
            var aHref = $(this).attr("href"),
                regexp = $(this).data('pat') ? new RegExp($(this).data('pat')) : false;

            if (regexp && regexp.test(url)) {
                return setSidebarActiveable($naw, $(this));
            }

            if (pathnameUrl === aHref) {
                return setSidebarActiveable($naw, $(this));
            }

            if (path === aHref) {
                return setSidebarActiveable($naw, $(this));
            }
        });
    });

    function setActiveableUrl($wrap, $item, tag, activeClass) {
        $wrap.find(tag).removeClass(activeClass);
        $item.addClass(activeClass);
        return true;
    }
    // Set active item to link: <ul class='js-activeable-url'><li><a href='#' data-pat='seo'></a></li></ul>
    $('.js-activeable-url').each(function () {
        var $this = $(this),
            tag = $this.data('tag') || 'a',
            activeClass = $this.data('class') || 'active';
        var pathnameUrl = window.location.pathname,
            url = window.location.href,
            path = url.split('?')[0];

        $this.find(tag).each(function () {
            var aHref = $(this).attr("href"),
                regexp = $(this).data('pat') ? new RegExp($(this).data('pat')) : false;

            if (regexp && regexp.test(url)) {
                return setActiveableUrl($this, $(this), tag, activeClass);
            }

            if (pathnameUrl === aHref) {
                return setActiveableUrl($this, $(this), tag, activeClass);
            }

            if (path === aHref) {
                return setActiveableUrl($this, $(this), tag, activeClass);
            }
        })
    });

    // Component: formOpen
    // Autosabmit form after change file
    $(document).on('change', '.js-form-submit-file-changed input[type="file"]', function () {
        $(this).closest('form').submit();
    });

    // Format numbers: 10000 -> 10 000
    $('.js-num-format').each(function (index, value) {
        var number = parseFloat(value.textContent);
        value.textContent = numberWithSpaces(number);
    });

    function numberWithSpaces(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    // Modal show with get AJAX content
    $(document).on('click', '.js-modal-fill-html', function (e) {
        e.preventDefault();
        var $this = $(this),
            url = $this.data('url') || $this.attr('href'),
            target = $this.data('target'),
            initFunctionsStr = $this.data('fn-inits'); // "fn1,fn2,..."
        $.get(url, function (data) {
            $(`${target} .modal-content`).html(data.html);
            $(`${target}`).modal();

            return true;
        }).done(function () {
            Lte3.init($(`${target} .modal-content`));
            callFnInits(initFunctionsStr);
        });
    });

    // Validate form before Save
    // Add in php controller after validation: if($request->prevalidate) {return 'ok';}
    $(document).on('click', '.js-form-submit-prevalidate', function (e) {
        e.preventDefault();
        var $form = $(this).closest('form');
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize() + '&prevalidate=1',
            success: function (data) {
                console.log(data);
                $form.submit();
            },
            error: function (data) {
                var response = JSON.parse(data.responseText);

                if (response && response.errors !== undefined) {
                    $.each(response.errors, function (key, value) {
                        value.forEach(function (item) {
                            console.log(item);
                            lteAlert('error', item);
                        });
                    });
                }
            }
        });
    });

    // Change select
    $(document).on('change', '.js-change-url-submit', function () {
        window.location = $(this).val();
    })

    // Radio submit
    $(document).on('change', '.js-radio-submit', function (e) {
        e.preventDefault();
        var $this = $(this),
            strConfirm = $this.data('confirm') ? confirm($this.data('confirm')) : true;
        if (strConfirm && ($this.data('url') || $this.attr('value'))) {
            var $form = $('#js-action-form');
            $form.attr('action', $this.data('url') || $this.attr('value')).submit();
        }
        return false;
    })

    // TODO: Deprecated
    //  Click submit
    $(document).on('click', '.js-click-submit', function (e) {
        e.preventDefault();
        var $form = $('#js-action-form'),
            $this = $(this),
            method = $this.data('method') || 'POST',
            url = $(this).data('url') || $(this).attr('href'),
            strConfirm = $this.data('confirm') ? confirm($this.data('confirm')) : true,
            destination = $(this).data('destination');

        if (url && strConfirm && $form) {
            $form.find('input[name="_method"]').val(method);
            if (destination) {
                $form.find('input.f-dest').val(destination);
            }
            $form.attr('action', url).submit();
        }
    });
    $(document).on('click', '.js-click-url', function (e) {
        e.preventDefault();
        var $this = $(this),
            url = $(this).data('url') || $(this).attr('href'),
            strConfirm = $this.data('confirm') ? confirm($this.data('confirm')) : true;

        if (url && strConfirm) {
            window.location = url;
        }
    });

    var sortableNestedVar = $('.js-sortable-nested').sortableNested({
        delay: 500,
        handle: '.handle',
        onDrop: function ($item, container, _super) {
            container.el.removeClass("active");
            _super($item, container);

            var
                $wrap = $item.closest('.f-sortable-nested-wrap'),
                data = sortableNestedVar.sortableNested("serialize").get(),
                url = $wrap.data('url'),
                method = $wrap.data('method') || 'POST';
            console.log(data, url, method);

            if (url) {
                $.ajax({
                    method: method,
                    url: url,
                    dataType: 'json',
                    data: {'data': data},
                    success: function (data) {
                        lteAlert('success', data.message);
                    },
                    error: function () {
                        lteAlert('error', 'Error SortableNested Ajax!');
                    }
                });
            }
        }
    });


    // jQuery UI sortable
    initSortableY = function (root) {
        scoped(root, '.sortable-y').filter(function () { return !$(this).data('ui-sortable'); }).sortable({
            distance: 5,
            placeholder: "sortable-placeholder",
            axis: 'y',
            update: function () {
                var $this = $(this),
                    url = $this.data('url'),
                    inputWeightClass = $this.data('input-weight-class'),
                    method = $this.data('method') || 'POST',
                    order = $this.sortable('toArray');

                console.log(order);

                if (inputWeightClass) {
                    $this.find('.' + inputWeightClass).each(function (i) {
                        $(this).val(i);
                    });
                }

                if (url) {
                    $.ajax({
                        method: method,
                        url: url,
                        dataType: 'json',
                        data: {data: order},
                        success: function (data) {
                            console.log(data)
                            lteAlert('success', 'Success Ajax!');
                        },
                        error: function () {
                            console.log('Error Ajax!')
                            lteAlert('error', 'Error Ajax!');
                        }
                    });
                }
            }
        });
    };
    initSortableY();

    // Component: File
    $(document).on('click', '.f-file .f-file-item .js-btn-delete', function (e) {
        e.preventDefault();
        if (confirm('Confirm?')) {
            var $this = $(this);
            $this.siblings('.js-input-delete').val($this.data('id'));
            $this.closest('.f-file-item').hide();
        }
    });
    $(document).on('click', '.f-media-file .f-file-item .js-btn-delete', function (e) {
        e.preventDefault();
        if (confirm('Confirm?')) {
            var $this = $(this);
            $this.siblings('.js-input-delete').val($this.data('id'));
            $this.closest('.f-file-item').hide();
        }
    });

    // LFM - AJAX save/clear
    function sendLfmAjax(url, fieldName, value) {
        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: {name: fieldName, value: value},
            success: function (data) {
                if (data.message) {
                    lteAlert('success', data.message);
                }
            },
            error: function () {
                console.log('Error Ajax!');
                lteAlert('error', 'Error Ajax!');
            }
        });
    }
    $(document).on('change', '.f-lfm .js-lfm-input', function () {
        var $input = $(this),
            urlSave = $input.closest('.f-lfm').data('url-save');

        if (urlSave) {
            sendLfmAjax(urlSave, $input.attr('name'), $input.val());
        }
    });

    // Show info about input chuse file
    $(document).on('change', '.js-files-input', function () {
        var $this = $(this),
            $info = $this.closest('.f-wrap').find('.js-files-info');

        if ($info.length) {
            var text = '';
            $info.text(text);
            $.each(this.files, function (index, value) {
                text = text + `${value.name} (${humanFileSize(value.size)}), `;
            });
            $info = $info.text('Selected: ' + text.slice(0, -2))
        }
    });

    function humanFileSize(bytes, si = false, dp = 1) {
        const thresh = si ? 1000 : 1024;

        if (Math.abs(bytes) < thresh) {
            return bytes + ' B';
        }

        const units = si
            ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
            : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        let u = -1;
        const r = 10 ** dp;

        do {
            bytes /= thresh;
            ++u;
        } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);

        return bytes.toFixed(dp) + ' ' + units[u];
    }

    // Component: Slug
    initJsVerificationSlugField = function (root) {
        scoped(root, '.js-verification-slug-field').each(function () {
            if ($(this).find('input.js-slug-field-change').is(':checked')) {
                $(this).find('input.js-slug-field-input')
                    .prop('readonly', false)
                    .prop('disabled', false)
            }
        });
    };
    $(document).on('change', '.js-verification-slug-field [type="checkbox"]', function () {
        var $wrap = $(this).closest('.js-verification-slug-field');
        if (this.checked) {
            $wrap.find('input.js-slug-field-input')
                .prop('readonly', false)
                .prop('disabled', false)
        } else {
            $wrap.find('input.js-slug-field-input')
                .prop('readonly', true)
                .prop('disabled', true)
        }
    });
    initJsVerificationSlugField();

    // Component: Colorpicker
    initColorpicker = function (root) {
        once(scoped(root, '.f-colorpicker'), 'lte3Colorpicker').each(function () {
            var $this = $(this), delayTimer;

            $this.colorpicker().on('colorpickerChange', function(event) {
                var $input = $(this).find('input'),
                    fieldName = $input.attr('name'),
                    urlSave = $input.data('url-save'),
                    value = event.color.toString();
                $this.find('.fa-square').css('color', value);
                clearTimeout(delayTimer);
                if (urlSave) {
                    delayTimer = setTimeout(function() {
                        sendColorpicker(urlSave, fieldName, value);
                    }, 500);
                }
            });
        });
    };
    function sendColorpicker(urlSave, fieldName, value) {
        $.ajax({
            url: urlSave,
            method: 'POST',
            dataType: 'json',
            data: {name: fieldName, value: value},
            success: function (data) {
                if (data.message) {
                    lteAlert('success', data.message);
                }
            },
            error: function () {
                console.log('Error Ajax!');
                lteAlert('error', 'Error Ajax!');
            },
            complete: function () {
                //
            }
        });
    }
    initColorpicker();

    function toggleSelectableBlocks($val, selectBlocksMap) {
        Pace.restart();
        for (var keyHide in selectBlocksMap) {
            var idHide = 0;

            if ($val !== keyHide) {
                for (idHide in selectBlocksMap[keyHide]) {
                    $(selectBlocksMap[keyHide][idHide]).hide();
                }
            }
        }

        for (var keyShow in selectBlocksMap) {
            var idShow = 0;

            if ($val === keyShow) {
                for (idShow in selectBlocksMap[keyShow]) {
                    $(selectBlocksMap[keyShow][idShow]).show();
                }
            }
        }
    }

    // Component: Select2
    // https://select2.org/
    initSelect2 = function (root) {
        scoped(root, '.f-select2').filter(function () { return !$(this).data('select2'); }).each(function () {
            var $this = $(this),

                urlSave = $this.data('url-save'),
                urlSuggest = $this.data('url-suggest'),
                urlTags = $this.data('url-tags'),
                placeholder = $this.attr('placeholder') || '',
                allowClear = $this.attr('allowClear') || false,
                closeOnSelect = $this.data('close-on-select') || true;

            // Autosave after change — делегований обробник нижче
            if (urlSave) {
                    $this.select2({
                        language: LANGUAGE,
                        tags: false,
                        closeOnSelect: closeOnSelect,
                        placeholder: placeholder,
                        allowClear: allowClear,
                        dropdownParent: $this.closest('.f-select2-wrap'),
                    });
            }

            if (urlTags) {
                var maximumSelection = $(this).data('max') || -1,
                    tokenSeparators = $(this).data('separators') || [',', ';'],
                    newTagLabel = $(this).data('new-tag-label') || ' (new)';

                $this.select2({
                    language: LANGUAGE,
                    tags: true,
                    closeOnSelect: closeOnSelect,
                    tokenSeparators: tokenSeparators,
                    dropdownParent: $this.closest('.f-select2-wrap'),
                    placeholder: placeholder,
                    allowClear: allowClear,
                    ajax: urlTags ? {
                        delay: 250,
                        url: urlTags,
                        dataType: 'json',
                        processResults: function (data) {
                            return {
                                results: data.results
                            }
                        }
                    } : undefined,

                    // Some nice improvements:

                    // max tags is 3
                    maximumSelectionLength: maximumSelection,

                    // add "(new tag)" for new tags
                    createTag: function (params) {
                        var term = $.trim(params.term);

                        if (term === '') {
                            return null;
                        }

                        return {
                            id: term,
                            text: term + newTagLabel
                        };
                    },
                });
            } else if (urlSuggest) {
                $this.select2({
                    language: LANGUAGE,
                    tags: false,
                    closeOnSelect: closeOnSelect,
                    placeholder: placeholder,
                    allowClear: allowClear,
                    dropdownParent: $this.closest('.f-select2-wrap'),
                    ajax: {
                        delay: 250,
                        url: urlSuggest,
                        dataType: 'json'
                    }
                });
            } else {
                $this.select2({
                    language: LANGUAGE,
                    tags: false,
                    closeOnSelect: closeOnSelect,
                    placeholder: placeholder,
                    allowClear: allowClear,
                    dropdownParent: $this.closest('.f-select2-wrap')
                });
            }
        });

        // Displaying blocks depending on the selection in the selection
        scoped(root, '.f-select2-wrap .js-map-blocks').each(function () {
            if ($(this).find(':selected')) {
                toggleSelectableBlocks($(this).find(':selected').val(), $(this).data('map'));
            }
        });
        scoped(root, '.f-radiogroup .js-map-blocks').each(function () {
            if ($(this).is(':checked')) {
                toggleSelectableBlocks($(this).val(), $(this).data('map'));
            }
        });
    }

    $(document).on('change', '.f-select2', function () {
        var $this = $(this),
            urlSave = $this.data('url-save');

        if (!urlSave) {
            return;
        }

        $.ajax({
            method: $this.data('method-save') || 'POST',
            url: urlSave,
            dataType: 'json',
            data: {name: $this.data('name'), value: $this.first(':selected').val()},
            success: function (data) {
                if (data.message) {
                    lteAlert('success', data.message);
                }
                if (data.operation === 'reload') {
                    window.location.reload();
                }
            },
            error: function () {
                console.log('Error Ajax!');
                lteAlert('error', 'Error Ajax!');
            }
        });
    });

    $(document).on('change', '.js-map-blocks', function () {
        if ($(this).data('map')) {
            toggleSelectableBlocks($(this).val(), $(this).data('map'));
        }
    });

    $(document).on('click', '.select2', function (e) {
        let el = document.querySelector('.select2-container.select2-container--default.select2-container--open .select2-dropdown.select2-dropdown--below .select2-search.select2-search--dropdown  .select2-search__field');
        if (el) {
            el.focus();
        }
    });

    initSelect2();

    // TODO
    //$(document).on('change', '.f-radiogroup')

    // Component: checkbox
    // AJAX Save
    $(document).on('change', '.f-checkbox-ajax', function () {
        var $this = $(this),
            url = $this.data('url-save'),
            rawFieldName = $this.data('raw-name'),
            format = $this.data('format');

        if (!url) {
            return;
        }

        let checkbox = this;
        let oldValue = !this.checked;
        var value = this.checked ? 1 : 0,
            data = format === 'name,value' ? {name: rawFieldName, value: value} : {[rawFieldName]: value};
        $.ajax({
            method: $this.data('method-save') || 'POST',
            url: url,
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.status === 'error') {
                    checkbox.checked = oldValue;
                    lteAlert('error', data.message);
                } else {
                    lteAlert('success', data.message);
                }
            },
            error: function () {
                console.log('Error Ajax!')
                checkbox.checked = oldValue;
                lteAlert('success', 'Error Ajax!');
            }
        });
    });
    // делегований обробник — окремої ініціалізації не треба; функція для data-fn-inits
    initCheckbox = function () {};

    // Component: Select2Tree
    // https://github.com/clivezhg/select2-to-tree
    initSelect2Tree = function (root) {
        once(scoped(root, '.f-select2-tree-wrap'), 'lte3Select2Tree').each(function () {
            var $this = $(this),
                $input = $this.find('.f-select2-tree-input'),
                url = $input.data('url'),
                methodGet = $input.data('method-get') || 'GET',
                valFld = $input.data('valfld') || 'id',
                labelFld = $input.data('labelfld') || 'name',
                incFld = $input.data('incfld') || 'children',
                expandAll = $input.data('expandall');

            $.ajax({
                method: methodGet,
                url: url,
                dataType: 'json',
                data: {data: ''},
                success: function (data) {
                    $input.select2ToTree({
                        treeData: {
                            dataArr: data.result || data.data,
                            dftVal: data.selected || data.default,
                            valFld: valFld,
                            labelFld: labelFld,
                            incFld: incFld,
                            expandAll: expandAll
                        }
                    })
                },
                error: function () {
                    lteAlert('error', 'Error Tree Ajax!');
                },
                complete: function () {
                    //...
                }
            })
        });
    }
    initSelect2Tree();

    // Component: Treeview
    // https://github.com/jonmiles/bootstrap-treeview
    initTreeview = function (root) {
        once(scoped(root, '.f-treeview-wrap'), 'lte3Treeview').each(function () {
            var $base = $(this),
                $tree = $base.find('.f-treeview-data'),
                url = $base.data('url'),
                staticData = $base.data('data'),
                methodGet = $base.data('method-get') || 'GET',
                showCheckbox = $base.data('showCheckbox') || true,
                showIcon = $base.data('showIcon') || false,
                fieldName = $base.data('field-name'),
                $inputs = $base.find('.f-treeview-inputs'),
                getCheckedIds = function (obj) {
                    $inputs.html('');
                    var array = [];
                    obj.forEach(element => {
                        $inputs.append('<input type="hidden" name="' + fieldName + '[]" value="' + element.id + '" />');
                    });
                    return array;
                };
            if (url) {
                $.ajax({
                    method: methodGet,
                    url: url,
                    dataType: 'json',
                    data: {data: ''},
                    success: function (data) {
                        //makeTreeview($base, data)
                        $tree.treeview({
                            data: data.data,
                            showIcon: showIcon,
                            showCheckbox: showCheckbox,
                            collapseIcon: 'fas fa-minus',
                            expandIcon: 'fas fa-plus',
                            checkedIcon: 'far fa-check-square',
                            uncheckedIcon: 'far fa-square'
                        });

                        getCheckedIds($tree.treeview('getChecked'));

                        $tree.on('nodeChecked', function (event, data) {
                            getCheckedIds($(this).treeview('getChecked'));
                        });
                        $tree.on('nodeUnchecked', function (event, data) {
                            getCheckedIds($(this).treeview('getChecked'));
                        });
                    },
                    error: function () {
                        console.log('Error Treeview Ajax!');
                    },
                    complete: function () {
                        $base.find('.overlay').fadeOut(200);
                    }
                });
            } else if (staticData) {
                $tree.treeview({
                    data: staticData,
                    showIcon: showIcon,
                    showCheckbox: showCheckbox,
                    collapseIcon: 'fas fa-minus',
                    expandIcon: 'fas fa-plus',
                    checkedIcon: 'far fa-check-square',
                    uncheckedIcon: 'far fa-square'
                });

                getCheckedIds($tree.treeview('getChecked'));

                $tree.on('nodeChecked', function () {
                    getCheckedIds($(this).treeview('getChecked'));
                });
                $tree.on('nodeUnchecked', function () {
                    getCheckedIds($(this).treeview('getChecked'));
                });
                $base.find('.overlay').fadeOut(200);
            }

        });
    }

    function makeTreeview($wrap, data) {
        // TODO
    }

    initTreeview();

    // Component: Links
    $(document).on('click', '.f-links .js-btn-add', function (e) {
        e.preventDefault()
        var $parent = $(this).parents('.f-links'),
            n = $parent.find('.js-btn-add').index(this),
            length = $parent.find('.js-btn-add').length,
            fieldName = $parent.data('field-name'),
            keyKey = $parent.data('key'),
            keyValue = $parent.data('value'),
            placeholderKey = $parent.data('placeholder-key'),
            placeholderValue = $parent.data('placeholder-value'),
            item = '<tr class="item">'
                + '<td class="align-middle text-center"><i class="fas fa-sort"></i></td>'
                + '<td class="w-100">'
                + '<div class="input-group input-group-sm">'
                + '<input name="' + fieldName + '[' + (length) + '][' + keyKey + ']" class="form-control" placeholder="' + placeholderKey + '" type="text">'
                + '<input name="' + fieldName + '[' + (length) + '][' + keyValue + ']" class="form-control" placeholder="' + placeholderValue + '" type="text">'
                + '<input type="hidden" name="" value="0">'
                + '<span class="input-group-append">'
                + '<button type="button" class="btn btn-success btn-flat js-btn-add"><i class="fas fa-plus"></i></button>'
                + '<button type="button" class="btn btn-danger btn-flat js-btn-delete"><i class="fas fa-minus"></i></button>'
                + '</span>'
                + '</div>'
                + '</td>'
                + '</tr>"'

        $parent.find('.item').eq(n).after(item);
    });
    $(document).on('click', '.f-links .js-btn-delete', function (e) {
        e.preventDefault();

        var $parent = $(this).parents('.f-links'),
            length = $parent.find('.js-btn-delete').length;
        if (length > 1) {
            var n = $parent.find('.js-btn-delete:not(.first)').index(this);

            $parent.find('.item').eq(n).remove();
        }
    });

    // Component: Lists
    $(document).on('click', '.f-lists .js-btn-add', function (e) {
        e.preventDefault()
        var $parent = $(this).parents('.f-lists'),
            n = $parent.find('.js-btn-add').index(this),
            length = $parent.find('.js-btn-add').length,
            fieldName = $parent.data('field-name'),
            placeholderValue = $parent.data('placeholder-value'),
            item = '<tr class="item">'
                + '<td class="align-middle text-center"><i class="fas fa-sort"></i></td>'
                + '<td class="w-100">'
                + '<div class="input-group input-group-sm">'
                + '<input name="' + fieldName + '[' + (length) + ']" placeholder="' + placeholderValue + ' ' + (parseInt(length) + 1) + '" class="form-control" type="text">'
                + '<span class="input-group-append">'
                + '<button type="button" class="btn btn-success btn-flat js-btn-add"><i class="fas fa-plus"></i></button>'
                + '<button type="button" class="btn btn-danger btn-flat js-btn-delete"><i class="fas fa-minus"></i></button></span>'
                + '</div>'
                + '</td>'
                + '</tr>"';
        $parent.find('.item').eq(n).after(item);
    });
    $(document).on('click', '.f-lists .js-btn-delete', function (e) {
        e.preventDefault();

        var $parent = $(this).parents('.f-lists'),
            length = $parent.find('.js-btn-delete').length;
        if (length > 1) {
            var n = $parent.find('.js-btn-delete:not(.first)').index(this);

            $parent.find('.item').eq(n).remove();
        }
    });

    $(document).on('mouseenter show.bs.dropdown', '.table-responsive .dropdown, .table-responsive .dropleft', function () {
        $(this).closest('.table-responsive').css('overflow-x', 'clip');
    });
    $(document).on('mouseleave hidden.bs.dropdown', '.table-responsive .dropdown, .table-responsive .dropleft', function () {
        $(this).closest('.table-responsive').css('overflow-x', '');
    });

    $(document).on('focusout keypress', '.js-input-calc', function(event) {
        if (event.type === 'focusout' || (event.which === 13 && event.type === 'keypress')) {
            var expression = $(this).val();
            var result = eval(expression);
            $(this).val(result);
        }
    });
    $(document).on('input', '.js-input-calc', function() {
        var sanitized = $(this).val().replace(/[^0-9()+\-*\/\.\s]/g, '');
        $(this).val(sanitized);
    });
    // делеговані обробники — окремої ініціалізації не треба; функція для data-fn-inits
    initInputCalc = function () {};

    $(document).on('keyup keypress', 'input.js-input-calc', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });


    function generateRandomPassword(length, complexity) {
        let charset = "abcdefghijklmnopqrstuvwxyz";

        if (complexity >= 2) {
            charset += "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        if (complexity >= 3) {
            charset += "0123456789";
        }
        if (complexity >= 4) {
            charset += "!@#$%^&*()_+~`|}{[]:;?><,./-=";
        }
        if (complexity >= 5) {
            charset += "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
        }

        let password = "";
        for (let i = 0, n = charset.length; i < length; ++i) {
            password += charset.charAt(Math.floor(Math.random() * n));
        }
        return password;
    }

    function getRandomLength(min, max) {
        if (!min && !max) {
            return 12; // Значення за замовчуванням, якщо не вказано довжину
        }
        if (min && !max) {
            return min;
        }
        if (!min && max) {
            return max;
        }
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    $(document).on('click', '.js-passgen', function() {
        const lengthFrom = parseInt($(this).data('length-from')) || 8;
        const lengthTo = parseInt($(this).data('length-to')) || 8;
        const length = getRandomLength(lengthFrom, lengthTo);
        const complexity = parseInt($(this).data('complexity'));
        const inputRecipientSelector = $(this).data('input-recipient');

        let $inputRecipient = null;
        if (inputRecipientSelector && $(inputRecipientSelector).length) {
            $inputRecipient = $(inputRecipientSelector);
        } else {
            $inputRecipient = $(this).closest('.form-group').find('input');
        }

        if ($inputRecipient && $inputRecipient.length) {
            const password = generateRandomPassword(length, complexity);
            $inputRecipient.attr('type', 'text');
            $inputRecipient.val(password);
        }
    });


    // TODO: Deprecated
    // Use md-blocks
    // Dynamic blocks
    $(document).on('click', '.f-multyblocks>*>.js-btn-add', function (e) {
        e.preventDefault();
        var $this = $(this),
            $wrap = $this.closest('.f-wrap'),
            $template = $wrap.find('.f-item-template'),
            $wrapItemTag = $template.data('wrap-tag'),
            initFunctionsStr = $wrap.data('fn-inits'),
            length = $wrap.find('.f-item').length;

        var $html = $wrap.find('.f-item-template').html();

        if ($wrapItemTag) {
            $wrap.find('.f-items').append(`<${$wrapItemTag} class="f-item">${$html}</${$wrapItemTag}>`);
        } else {
            $wrap.find('.f-items').append($html);
        }

        var $newItem = $wrap.find('.f-items>.f-item').last();
        // Імена для полів ітема блоку
        $.each($newItem.find('[name]'), function () {
            var name = ($(this).attr('name')).replace('$i', length);
            $(this).attr('name', name);
            if ($(this).data('class')) {
                $(this).addClass($(this).data('class'));
            }
            if ($(this).hasClass('js-input-weight')) {
                $(this).val(length)
            }
        });
        // Для Select2
        $.each($newItem.find('[id]'), function () {
            var name = ($(this).attr('id')).replace('$i', length);
            $(this).attr('id', name);
        });
        // Для полів LFM
        $.each($newItem.find('[data-field-name]'), function () {
            var name = ($(this).attr('data-field-name')).replace('$i', length);
            $(this).attr('data-field-name', name);
        });

        $wrap.find('.js-msg-empty').remove();

        Lte3.init($newItem);
        callFnInits(initFunctionsStr);
    });
    $(document).on('click', '.f-wrap .f-item>.js-btn-delete', function (e) {
        e.preventDefault();
        $(this).closest('.f-item').remove();
    });


    // Налаштування стовбців таблиці tableOptions
    function applyColumnOptions($table, userOptions) {

        const columnsOptions = $table.data('columns') || [];

        const hiddenKeys = columnsOptions
            .filter(col => col.hidden)
            .map(col => col.key);

        if (!userOptions) {
            userOptions = {};
        }

        columnsOptions
            .filter(col => !col.hidden)
            .forEach((col, index) => {

                if (!userOptions[col.key]) {
                    userOptions[col.key] = {
                        weight: Object.keys(userOptions).length,
                        active: col.default === false ? "0" : "1"
                    };
                }

            });

        Object.keys(userOptions).forEach(key => {
            const exists = columnsOptions.find(col => col.key === key);
            if (!exists || exists.hidden) {
                delete userOptions[key];
            }
        });

        const sortedKeys = Object.keys(userOptions)
            .sort((a, b) => userOptions[a].weight - userOptions[b].weight);

        sortedKeys.forEach((key, index) => {
            const columnClass = `js-table-options-${key}`;

            $table.find('tr').each(function () {
                const $row = $(this);
                const $cell = $row.children(`.${columnClass}`);

                if (!$cell.length) return;

                const $children = $row.children().not(
                    hiddenKeys.map(k => `.js-table-options-${k}`).join(',')
                );

                if ($children.eq(index)[0] !== $cell[0]) {

                    if (index >= $children.length) {
                        $row.append($cell);
                    } else {
                        $cell.insertBefore($children.eq(index));
                    }
                }
            });
        });

        Object.keys(userOptions).forEach(key => {
            const columnClass = `js-table-options-${key}`;

            if (userOptions[key].active === "0") {
                $table.find(`.${columnClass}`).addClass('d-none');
            } else {
                $table.find(`.${columnClass}`).removeClass('d-none');
            }
        });

        hiddenKeys.forEach(key => {
            $table.find(`.js-table-options-${key}`).addClass('d-none');
        });
    }

    const $table = $('.table');
    const options = $table.data('options');

    applyColumnOptions($table, options);

    // Preloader
    $('#table-preloader').fadeOut(250);

    // Заблюрений текст
    $('.js-blur-text').on('click', function () {
        const $el = $(this);

        if ($el.hasClass('revealed')) {
            $(this).removeClass('revealed');
            return;
        };

        $el.addClass('revealed');

        setTimeout(function () {
            $el.removeClass('revealed');
        }, 10000);
    });

    // Приховане значення поля input (як password) з можливістю відктивання
    $('.js-secret-value-btn').click(function() {
        const btn = $(this),
            input = btn.closest('.input-group').find('.js-secret-value');
        if (input.attr('type') === 'password') {
            const type = input.data('origin-type');
            input.attr('type', type);
            btn.find('i').attr('class', 'far fa-eye-slash');
        } else {
            input.attr('type', 'password');
            btn.find('i').attr('class', 'far fa-eye');
        }
    });

    /**
     * дії на кнопки (відправка, ajax, submit, actions)
     */
    $(document).on('click', '.js-ajax-send', function (e) {
        e.preventDefault();

        const $btn = $(this);
        const url = $btn.data('url') || $btn.attr('href');
        const method = ($btn.data('method') || 'POST').toUpperCase();

        // 🔸 підтвердження
        const confirmText = $btn.data('confirm');
        if (confirmText && !window.confirm(confirmText)) {
            return;
        }


        // 🔹 Якщо потрібен звичайний submit / перезавантаження
        if ($btn.data('submit') === true) {
            const dataAttr = $btn.data('data');
            let data = {};

            try {
                if (typeof dataAttr === 'string' && dataAttr.trim() !== '') {
                    data = JSON.parse(dataAttr);
                } else if (typeof dataAttr === 'object') {
                    data = dataAttr;
                }
            } catch (err) {
                console.error('Invalid JSON in data-data attribute', err);
            }

            // 🔹 Створюємо форму і сабмітимо
            const $form = $('<form>', {
                method: method === 'GET' ? 'GET' : 'POST',
                action: url
            }).appendTo('body');

            // Додаємо поля input
            for (const key in data) {
                if (data.hasOwnProperty(key)) {
                    $('<input>', {
                        type: 'hidden',
                        name: key,
                        value: data[key]
                    }).appendTo($form);
                }
            }

            // Якщо метод DELETE або PUT → додаємо _method для Laravel
            if (['PUT', 'PATCH', 'DELETE'].includes(method)) {
                $('<input>', {
                    type: 'hidden',
                    name: '_method',
                    value: method
                }).appendTo($form);
            }

            // Якщо POST → додаємо CSRF токен для Laravel
            if (method !== 'GET') {
                const csrf = $btn.data('csrf') || $('meta[name="csrf-token"]').attr('content');
                $('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: csrf
                }).appendTo($form);
            }

            $form.submit();
            return; // припиняємо подальший AJAX
        }

        // Приховуємо tooltip кнопки перед виконанням
        if ($btn.data('toggle') === 'tooltip') {
            $btn.tooltip('hide');
        }

        let data = $btn.data('data');

        // 🔸 обробка JSON у data-data (дані)
        if (typeof data === 'string' && data.trim().startsWith('{')) {
            try {
                data = JSON.parse(data);
            } catch (err) {
                console.error('Помилка парсингу JSON у data-data:', err);
                return;
            }
        }

        $btn.prop('disabled', true).addClass('loading');

        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function (response) {
                // ✅ toastr повідомлення
                if (response.message) {
                    const type = response.status || response.type || 'success';
                    if (toastr[type]) toastr[type](response.message);
                    else toastr.info(response.message);
                }

                // ✅ оновлення html
                const $updated = [];
                if (response.html) {
                    const htmlAppends = response.htmlAppends || [];
                    if (typeof response.html === 'object') {
                        // 🔸 якщо html — об’єкт {selector: html}
                        for (const key in response.html) {
                            if (!response.html.hasOwnProperty(key)) continue;

                            const selector =
                                key.startsWith('#') || key.startsWith('.')
                                    ? key
                                    : '.' + key;

                            const html = response.html[key];
                            const $el = $(selector);

                            if ($el.length) {
                                if (htmlAppends.includes(selector)) {
                                    $el.append(html);
                                } else {
                                    $el.html(html);
                                }
                                $updated.push($el);
                            } else {
                                console.warn(`Елемент ${selector} не знайдено`);
                            }
                        }
                    } else if (typeof response.html === 'string') {
                        // 🔸 якщо html — просто рядок
                        const $container = $btn.closest('.js-html-container');
                        if ($container.length) {
                            if (htmlAppends.includes('.js-html-container')) {
                                $container.append(html);
                            } else {
                                $container.html(response.html);
                            }
                            //$container.html(response.html);
                            $updated.push($container);
                        } else {
                            console.warn('Контейнер .js-html-container не знайдено для вставки html');
                        }
                    }
                }
                $updated.forEach(function ($el) { Lte3.init($el); });

                // ✅ дії після успіху
                if (response.action) {
                    switch (response.action) {
                        case 'reload':
                            setTimeout(() => location.reload(), 800);
                            break;
                        case 'redirect':
                            if (response.redirect_url)
                                window.location.href = response.redirect_url;
                            break;
                        case 'remove':
                            if (response.selector)
                                $(response.selector).remove();
                            else
                                $btn.closest('.item, tr, .card').remove();
                            break;
                    }
                }

                // ✅ кастомний callback
                // https://chatgpt.com/s/t_68e12fe06a688191ab80d831823e4b41
                if (typeof window.onAjaxSuccess === 'function') {
                    window.onAjaxSuccess(response, $btn);
                }

                // перевиконувалися JS-ініціалізації (наприклад, tooltips чи редактори)
                // https://chatgpt.com/s/t_68e1302e89988191afc2385de2f81966
                if (typeof window.onHtmlUpdated === 'function') {
                    window.onHtmlUpdated(response.html);
                }
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Request error';
                toastr.error(msg);
                console.error('AJAX Error:', xhr);
            },
            complete: function () {
                $btn.prop('disabled', false).removeClass('loading');

                callFnInits($btn.data('fn-inits'));
            }
        });
    });




    // видалити посиллання з меню btn-actions на сторінці, яка відповідає поточному URL
    const currentUrl = window.location.origin + window.location.pathname;
    $('.btn-actions.lte-actions a[href]').each(function () {
        const linkUrl = new URL(this.href, window.location.origin);

        if (linkUrl.origin + linkUrl.pathname === currentUrl) {
            $(this).hide();
        }
    });





    // порівняння значень в полі
    $(function () {
        function normalizeCompareValue(value) {
            return String(value ?? '').trim();
        }

        function checkCompareValue($input) {
            const currentValue = normalizeCompareValue($input.val());
            const expectedValue = normalizeCompareValue($input.data('compare-value'));

            const equalTargetSelector = $input.data('compare-equal-target');
            const notEqualTargetSelector = $input.data('compare-not-equal-target');

            const isEqual = currentValue === expectedValue;

            if (equalTargetSelector) {
                $(equalTargetSelector).toggle(isEqual);
            }

            if (notEqualTargetSelector) {
                $(notEqualTargetSelector).toggle(!isEqual);
            }
        }
        function checkAllCompareValues() {
            $('.js-compare-value').each(function () {
                checkCompareValue($(this));
            });
        }
        checkAllCompareValues();
        $(document).on('input change', '.js-compare-value', function () {
            checkCompareValue($(this));
        });
    });

    Lte3.register('initTooltip', initTooltip);
    Lte3.register('initSortableY', initSortableY);
    Lte3.register('initJsVerificationSlugField', initJsVerificationSlugField);
    Lte3.register('initColorpicker', initColorpicker);
    Lte3.register('initSelect2', initSelect2);
    Lte3.register('initSelect2Tree', initSelect2Tree);
    Lte3.register('initTreeview', initTreeview);
});

// Media file field (lte3::components.mediaFile): drop zone, previews, delete/restore, sorting, properties modal.
// Every picked file goes into its own <input type=file> of the row, so the form is sent by a regular submit
(function ($) {
    var icons = {pdf: 'fa-file-pdf', doc: 'fa-file-word', docx: 'fa-file-word', xls: 'fa-file-excel', xlsx: 'fa-file-excel', csv: 'fa-file-excel', zip: 'fa-file-archive', rar: 'fa-file-archive', '7z': 'fa-file-archive', txt: 'fa-file-alt', md: 'fa-file-alt'};

    function humanSize(bytes) {
        var units = ['B', 'KB', 'MB', 'GB'], i = 0;
        while (bytes >= 1024 && i < units.length - 1) { bytes /= 1024; i++; }
        return (i ? bytes.toFixed(1) : bytes) + ' ' + units[i];
    }

    function accepts(input, file) {
        var accept = (input.getAttribute('accept') || '').split(',').map(function (s) { return s.trim().toLowerCase(); }).filter(Boolean);
        if (!accept.length) {
            return true;
        }
        var name = file.name.toLowerCase(), type = (file.type || '').toLowerCase();
        return accept.some(function (a) {
            return a[0] === '.' ? name.endsWith(a) : (a.endsWith('/*') ? type.indexOf(a.slice(0, -1)) === 0 : type === a);
        });
    }

    // порядок у формі = порядок на екрані
    // одиночне поле з файлом — без зони вибору, заміна кнопкою на файлі
    function refresh($wrap) {
        $wrap.find('.f-media-items .js-media-weight').each(function (i) { $(this).val(i); });
        $wrap.find('.f-media-items .f-media-item').each(function () { markProps($(this)); });
        if ($wrap.data('multiple') != 1) {
            $wrap.toggleClass('has-file', $wrap.find('.f-media-items .f-media-item:not(.is-deleted):not(.is-replaced)').length > 0);
        }
    }

    // підпис під назвою — перша заповнена властивість; мітка «немає alt» — лише на картинці
    function markProps($item) {
        var values = $item.find('input[data-prop]').map(function () { return $(this).val(); }).get().filter(Boolean),
            $alt = $item.find('input[data-prop="alt"]');
        $item.find('.f-media-caption').text(values[0] || '');
        $item.toggleClass('is-noalt', $alt.length > 0 && !$alt.val() && !!$item.data('thumb'));
    }

    function removeNew($item) {
        $item.find('img').each(function () { URL.revokeObjectURL(this.src); });
        $item.remove();
    }

    // кожен файл — у власний input name[N][file] свого рядка (DataTransfer), форма шлеться звичайним submit
    function addFiles($wrap, list) {
        if (!$wrap.find('.f-media-input').length) {
            return;
        }
        var input = $wrap.find('.f-media-input')[0],
            multiple = $wrap.data('multiple') == 1,
            files = Array.prototype.filter.call(list, function (f) { return accepts(input, f); }),
            rejected = Array.prototype.filter.call(list, function (f) { return !accepts(input, f); });

        // що не підійшло під accept — не мовчки: назви файлів і дозволені типи під зоною вибору
        $wrap.find('.f-media-rejected').text(rejected.length
            ? String($wrap.data('rejected-text')).replace(':files', rejected.map(function (f) { return f.name; }).join(', '))
            : '');

        if (!multiple) {
            files = files.slice(0, 1);
            if (files.length) {
                removeNew($wrap.find('.f-media-items .f-media-item.is-new'));
            }
        }

        files.forEach(function (file) {
            var index = +$wrap.data('next'),
                $item = $($wrap.find('.f-media-template').html().replace(/__N__/g, index)),
                ext = file.name.split('.').pop().toLowerCase(),
                dt = new DataTransfer();

            $wrap.data('next', index + 1);
            dt.items.add(file);
            $item.find('.f-media-file-input')[0].files = dt.files;
            $item.data('name', file.name).data('icon', icons[ext] || 'fa-file');
            $item.find('.f-media-name').text(file.name).attr('title', file.name);
            $item.find('.f-media-meta-text').text(ext.toUpperCase() + ' · ' + humanSize(file.size));
            if ((file.type || '').indexOf('image/') === 0) {
                var src = URL.createObjectURL(file);
                $item.data('thumb', src);
                $item.find('.f-media-thumb').empty().append($('<img alt="">').attr('src', src));
            } else {
                $item.find('.f-media-thumb i').attr('class', 'far ' + (icons[ext] || 'fa-file'));
            }
            $wrap.find('.f-media-items').append($item);
        });

        // одиночне поле: новий файл замінить наявний — рядок наявного вимикаємо, щоб у формі був один рядок
        if (!multiple && files.length) {
            $wrap.find('.f-media-items .f-media-item:not(.is-new)').addClass('is-replaced').find('input').prop('disabled', true);
        }
        refresh($wrap);
    }

    $(document).on('change', '.f-media .f-media-input', function () {
        addFiles($(this).closest('.f-media'), this.files);
        this.value = '';
    });

    $(document).on('dragover dragenter', '.f-media .f-media-drop', function (e) {
        e.preventDefault();
        $(this).addClass('is-dragover');
    }).on('dragleave dragend drop', '.f-media .f-media-drop', function (e) {
        e.preventDefault();
        $(this).removeClass('is-dragover');
    }).on('drop', '.f-media .f-media-drop:not(.disabled)', function (e) {
        addFiles($(this).closest('.f-media'), e.originalEvent.dataTransfer.files);
    });

    $(document).on('click', '.f-media .js-media-delete', function () {
        var $item = $(this).closest('.f-media-item'), $wrap = $item.closest('.f-media');
        if ($item.hasClass('is-new')) {
            removeNew($item);
            $wrap.find('.f-media-item.is-replaced').removeClass('is-replaced').find('input').prop('disabled', false);
            refresh($wrap);
            return;
        }
        $item.addClass('is-deleted').find('.js-media-delete-input').val(function () { return $(this).data('on'); });
        refresh($wrap);
    });

    $(document).on('click', '.f-media .js-media-replace', function () {
        $(this).closest('.f-media').find('.f-media-input').trigger('click');
    });

    $(document).on('click', '.f-media .js-media-restore', function () {
        $(this).closest('.f-media-item').removeClass('is-deleted').find('.js-media-delete-input').val(function () { return $(this).data('off'); });
        refresh($(this).closest('.f-media'));
    });

    // головне фото — одне на колекцію
    $(document).on('click', '.f-media .js-media-main', function () {
        var $item = $(this).closest('.f-media-item');
        $item.closest('.f-media').find('.f-media-item').removeClass('is-main').find('.js-media-main-input').val(0);
        $item.addClass('is-main').find('.js-media-main-input').val(1);
    });

    // вікно властивостей читає й пише приховані поля рядка файлу; саме вікно — поза формою
    function fieldInput($modal, prop) {
        return $modal.find('[name="f_media_prop[' + prop + ']"]').last();
    }

    $(document).on('click', '.f-media .js-media-edit', function () {
        var $item = $(this).closest('.f-media-item'),
            $wrap = $item.closest('.f-media'),
            $modal = $wrap.data('modal') || $wrap.find('.f-media-modal').appendTo('body'),
            $preview = $modal.find('.f-media-modal-preview').empty();

        $wrap.data('modal', $modal);
        $modal.data('item', $item).find('.f-media-modal-title').text($item.data('name'));
        if ($item.data('thumb')) {
            $preview.append($('<img alt="">').attr('src', $item.data('thumb')));
        } else {
            $preview.append($('<i class="far"></i>').addClass($item.data('icon') || 'fa-file')).append($('<span></span>').text($item.data('name')));
        }

        $item.find('input[data-prop]').each(function () {
            var $field = fieldInput($modal, $(this).data('prop')), value = $(this).val();
            $field.is(':checkbox') ? $field.prop('checked', !!value && value !== '0') : $field.val(value).trigger('change');
        });
        $modal.one('shown.bs.modal', function () { $modal.find('.modal-body :input:visible').first().trigger('focus'); }).modal('show');
    });

    $(document).on('click', '.f-media-modal .js-media-props-save', function () {
        var $modal = $(this).closest('.f-media-modal'), $item = $modal.data('item');

        $item.find('input[data-prop]').each(function () {
            var $field = fieldInput($modal, $(this).data('prop'));
            $(this).val($field.is(':checkbox') ? ($field.is(':checked') ? ($field.val() || 1) : '') : $field.val());
        });
        markProps($item);
        $modal.modal('hide');
    });

    // повторний виклик (fn-inits модалки) безпечний: сортування вже ініціалізованих не чіпає
    initMediaFile = function (root) {
        root = root || document;
        $(root).find('.f-media').each(function () { refresh($(this)); });
        $(root).find('.f-media .js-media-sortable').each(function () {
            if ($(this).data('ui-sortable') || !$.fn.sortable) {
                return;
            }
            var isList = $(this).hasClass('f-media-list');
            $(this).sortable({
                // legacy задає порядок лише збереженим файлам — нові додаються в кінець
                items: $(this).closest('.f-media').data('expand') == 1 ? '> .f-media-item' : '> .f-media-item:not(.is-new)',
                handle: isList ? '.f-media-handle' : false,
                cancel: '.f-media-actions, .f-media-star, .f-media-deleted',
                placeholder: 'f-media-item f-media-sort-placeholder',
                tolerance: 'pointer',
                distance: 5,
                update: function () { refresh($(this).closest('.f-media')); },
            });
        });
    }

    $(function () { initMediaFile(); });
    Lte3.register('initMediaFile', initMediaFile);
})(jQuery);

// LFM file field (lte3::components.lfmFile): File Manager in a modal (LFM `callback` param), drag & drop upload to /upload,
// card of the picked file. The value is a URL string in .js-lfm-input; `change` on it triggers url_save (below)
(function ($) {
    var icons = {jpg: 'fa-file-image', jpeg: 'fa-file-image', png: 'fa-file-image', gif: 'fa-file-image', webp: 'fa-file-image', svg: 'fa-file-image', pdf: 'fa-file-pdf', doc: 'fa-file-word', docx: 'fa-file-word', xls: 'fa-file-excel', xlsx: 'fa-file-excel', csv: 'fa-file-excel', zip: 'fa-file-archive', rar: 'fa-file-archive', mp4: 'fa-file-video', mp3: 'fa-file-audio'},
        $current = null;

    // перекладені тексти — з data-texts поля (main.js статичний)
    function text($wrap, key) {
        return ($wrap.data('texts') || {})[key] || key;
    }

    function fileNameOf(url) {
        try { return decodeURIComponent((url || '').split('?')[0].split('/').pop()); } catch (e) { return url; }
    }

    // значення поля + картка файлу; change на input — для url_save (обробник у main.js)
    function setValue($wrap, url, thumb) {
        var $input = $wrap.find('.js-lfm-input'),
            $items = $wrap.find('.f-media-items').empty(),
            isImage = $wrap.data('is-image') == 1;

        if ($wrap.data('trim-host') == 1 && url) {
            url = url.replace(window.location.origin, '');
        }
        $input.val(url).trigger('change');
        $wrap.find('.f-lfm-body').toggleClass('has-file', !!url);
        if (!url) {
            return;
        }

        var name = fileNameOf(url),
            ext = name.split('.').pop().toLowerCase(),
            $item = $($wrap.find('.f-lfm-template').html());

        $item.find('.f-media-name').text(name).attr({href: url, title: name});
        $item.find('.f-media-path').text(url).attr('title', url);
        $item.find('.js-lfm-open').attr('href', url);
        $item.find('.f-media-thumb').attr('href', url);
        if (isImage) {
            $item.find('.f-media-thumb').addClass('js-popup-image').empty().append($('<img alt="">').attr('src', thumb || url));
        } else {
            $item.find('.f-media-thumb i').attr('class', 'far ' + (icons[ext] || 'fa-file'));
        }
        $items.append($item);
    }

    // File Manager у модалці: LFM з iframe викликає parent[callback](items)
    function modal() {
        var $modal = $('#f-lfm-modal');
        if (!$modal.length) {
            $modal = $('<div class="modal fade f-lfm-modal" id="f-lfm-modal" tabindex="-1" role="dialog" aria-hidden="true">'
                + '<div class="modal-dialog modal-xl modal-dialog-centered" role="document"><div class="modal-content">'
                + '<div class="modal-header py-2"><h5 class="modal-title"></h5>'
                + '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                + '<div class="modal-body"><iframe></iframe></div></div></div></div>').appendTo('body');
                        $modal.on('hidden.bs.modal', function () { $modal.find('iframe').attr('src', 'about:blank'); });
        }
        return $modal;
    }

    window.lteLfmPicked = function (items) {
        if ($current && items && items.length) {
            setValue($current, items[0].url, items[0].thumb_url);
        }
        modal().modal('hide');
    };

    $(document).on('click keydown', '.f-lfm .js-lfm-pick', function (e) {
        if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') {
            return;
        }
        e.preventDefault();
        var $wrap = $(this).closest('.f-lfm');
        $current = $wrap;
        modal().find('.modal-title').text(text($wrap, 'manager'));
        modal().find('iframe').attr('src', $wrap.data('lfm-prefix') + '?type=' + encodeURIComponent($wrap.data('lfm-category')) + '&callback=lteLfmPicked');
        modal().modal('show');
    });

    $(document).on('click', '.f-lfm .js-lfm-clear', function (e) {
        e.preventDefault();
        setValue($(this).closest('.f-lfm'), '');
    });

    // ручне введення URL ('editable' => true)
    $(document).on('input', '.f-lfm input.js-lfm-input[type=text]', function () {
        var $wrap = $(this).closest('.f-lfm'), url = $(this).val();
        clearTimeout($wrap.data('typing'));
        $wrap.data('typing', setTimeout(function () { setValue($wrap, url); }, 500));
    });

    // перетягнутий файл заливається в File Manager, його URL стає значенням поля
    $(document).on('dragover dragenter', '.f-lfm .js-lfm-pick', function (e) {
        e.preventDefault();
        $(this).addClass('is-dragover');
    }).on('dragleave dragend drop', '.f-lfm .js-lfm-pick', function (e) {
        e.preventDefault();
        $(this).removeClass('is-dragover');
    }).on('drop', '.f-lfm .js-lfm-pick', function (e) {
        var file = e.originalEvent.dataTransfer.files[0],
            $pick = $(this),
            $wrap = $pick.closest('.f-lfm'),
            $status = $pick.find('.f-lfm-status');

        if (!file) {
            return;
        }
        if ($wrap.data('is-image') == 1 && (file.type || '').indexOf('image/') !== 0) {
            $status.text(text($wrap, 'imagesOnly'));
            return;
        }

        var data = new FormData();
        data.append('upload', file);
        data.append('type', $wrap.data('lfm-category'));
        data.append('working_dir', $wrap.data('lfm-folder') || '');
        $pick.addClass('is-uploading');
        $status.text(text($wrap, 'uploading'));

        $.ajax({
            url: $wrap.data('lfm-prefix') + '/upload',
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (res) {
            if (res && res.url) {
                $status.text('');
                setValue($wrap, res.url);
            } else {
                $status.text((res && res.error && res.error.message) || text($wrap, 'failed'));
            }
        }).fail(function (xhr) {
            $status.text((xhr.responseJSON && xhr.responseJSON.message) || text($wrap, 'failed'));
        }).always(function () {
            $pick.removeClass('is-uploading');
        });
    });
    // делеговані обробники — окремої ініціалізації не треба; функція для data-fn-inits за аналогією з іншими полями
    initLfmFile = function () {};
})(jQuery);
