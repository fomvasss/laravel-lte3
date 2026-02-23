var initJsVerificationSlugField = function () {},
    initColorpicker = function () {},
    initSortableY = function () {},
    initSelect2 = function () {},
    initCheckbox = function () {},
    initSelect2Tree = function () {},
    initTreeview = function () {},
    initInputCalc = function () {},
    initTooltip = function () {}
;

$(function () {
    'use strict';

    const USE_TOASTR = true;
    const LANGUAGE = $('html').attr('lang') || 'en';

    localStorage.setItem('AdminLTE:Demo:MessageShowed', (Date.now()) + (15 * 60 * 1000));

    $(document).ajaxStart(function () {
        Pace.restart();
    });

    initTooltip = function () {
        $('[data-toggle="tooltip"]').tooltip();
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
            initFunctionsStr.split(/\s*,\s*/).forEach(function (str) {
                console.log('Init function: ' + str);
                window[str]();
            });
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
    initSortableY = function () {
        $(".sortable-y").sortable({
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

    // LFM
    $(document).on('click', '.f-lfm .f-wrap-item .js-lfm-btn-clear', function (e) {
        e.preventDefault();
        var $this = $(this),
            $wrapItem = $this.closest('.f-wrap-item');
        $wrapItem.find('.js-lfm-input').val('');
        $wrapItem.find('.preview-block').html('');
    });
    $(document).on('click', '.f-lfm .f-wrap-item .js-lfm-btn-delete', function (e) {
        e.preventDefault();
        var $this = $(this),
            $wrapItem = $this.closest('.f-wrap-item');
        if ($this.data('id')) {
            $wrapItem.closest('.js-input-delete').val($this.data('id'));
        }
        $wrapItem.find('.js-lfm-input').remove();
        $wrapItem.hide();
    });
    $(document).on('click', '.f-lfm .js-lfm-btn-add', function (e) {
        e.preventDefault();
        var $wrap = $(this).closest('.f-wrap'),
            length = $wrap.find('.f-wrap-item').length,
            fieldName = $wrap.data('field-name'),

            item = '<tr class="f-wrap-item">'
                +'<td class="align-middle">'
                +'<div class="input-group">'
                +'<input class="form-control js-lfm-input" name="' + fieldName + '[' + (length) + ']" type="text">'
                +'<div class="input-group-append">'
                + '<span class="btn btn-info btn-flat f-lfm-btn">Browse</span>'
                +'</div>'
                +'</div>'
                +'</td>'
                +'<td style="width: 15%;" class="preview-block"></td>'
                +'<td class="align-middle" style="width: 5%;">'
                +'<a href="#" class="btn btn-danger btn-xs js-lfm-btn-delete"><i class="fas fa-times"></i></a>'
                +'</td>'
                +'</tr>';

        $wrap.find('.f-wrap-items').find('.f-wrap-item').eq(length-1).after(item);
        $wrap.find('.f-lfm-btn').filemanager();
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
    initJsVerificationSlugField = function () {
        if ($('.js-verification-slug-field').length) {
            if ($('input.js-slug-field-change').is(':checked')) {
                $('.js-verification-slug-field input.js-slug-field-input')
                    .prop('readonly', false)
                    .prop('disabled', false)
            }
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
        }
    };
    initJsVerificationSlugField();

    // Component: Colorpicker
    initColorpicker = function () {
        $('.f-colorpicker').colorpicker().each(function () {
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
    initSelect2 = function () {
        $('.f-select2').each(function () {
            var $this = $(this),

                urlSave = $this.data('url-save'),
                urlSuggest = $this.data('url-suggest'),
                urlTags = $this.data('url-tags'),
                closeOnSelect = $this.data('close-on-select') || true;

            // Autosave after change
            if (urlSave) {
                var fieldName = $this.data('name'),
                    method = $this.data('method-save') || 'POST';
                    $this.select2({
                        language: LANGUAGE,
                        tags: false,
                        closeOnSelect: closeOnSelect,
                        dropdownParent: $this.closest('.f-select2-wrap'),
                    });

                $this.on('change', function () {
                    var values = $this.first(':selected').val();

                    $.ajax({
                        method: method,
                        url: urlSave,
                        dataType: 'json',
                        data: {name: fieldName, value: values},
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
                        },
                        complete: function () {
                            //
                        }
                    });
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
                    dropdownParent: $this.closest('.f-select2-wrap')
                });
            }
        });

        // Displaying blocks depending on the selection in the selection
        $('.f-select2-wrap .js-map-blocks').each(function () {
            if ($(this).find(':selected')) {
                toggleSelectableBlocks($(this).find(':selected').val(), $(this).data('map'));
            }
        });
        $('.f-radiogroup .js-map-blocks').each(function () {
            if ($(this).is(':checked')) {
                toggleSelectableBlocks($(this).val(), $(this).data('map'));
            }
        });
    }

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
    initCheckbox = function () {
        $('.f-checkbox-ajax').each(function () {
            var $this = $(this),
                url = $this.data('url-save'),
                method = $this.data('method-save') || 'POST',
                rawFieldName = $this.data('raw-name'),
                format = $this.data('format');

            // AJAX Save
            if (url) {
                $this.on('change', function () {
                    var value = this.checked ? 1 : 0,
                        data = format === 'name,value' ? {name: rawFieldName, value: value} : {[rawFieldName]: value};
                    $.ajax({
                        method: method,
                        url: url,
                        dataType: 'json',
                        data: data,
                        success: function (data) {
                            lteAlert('success', data.message);
                        },
                        error: function () {
                            console.log('Error Ajax!')
                            lteAlert('success', 'Error Ajax!');
                        },
                        complete: function () {
                            //...
                        }
                    });
                });
            }
        });
    }
    initCheckbox();

    // Component: Select2Tree
    // https://github.com/clivezhg/select2-to-tree
    initSelect2Tree = function () {
        $('.f-select2-tree-wrap').each(function () {
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
    initTreeview = function () {
        $('.f-treeview-wrap').each(function () {
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

    $('.dropdown').hover(
        function(){
            $(this).closest('.table-responsive').css('overflow-x', 'clip');
        }
    );

    initInputCalc = function() {
        $('.js-input-calc').on('blur keypress', function(event) {
            if (event.type === 'blur' || (event.which === 13 && event.type === 'keypress')) {
                var expression = $(this).val();
                var result = eval(expression);
                $(this).val(result);
            }
        });
        $('.js-input-calc').on('input', function() {
            var sanitized = $(this).val().replace(/[^0-9()+\-*\/\.\s]/g, '');
            $(this).val(sanitized);
        });
    }
    initInputCalc();

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

        if (initFunctionsStr) {
            console.log(initFunctionsStr);
            initFunctionsStr.split(/\s*,\s*/).forEach(function (str) {
                console.log('Init function: ' + str);
                window[str]();
            });
        }
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
                        } else {
                            console.warn('Контейнер .js-html-container не знайдено для вставки html');
                        }
                    }
                }

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

                let initFunctionsStr = $btn.data('fn-inits');
                if (initFunctionsStr) {
                    initFunctionsStr.split(/\s*,\s*/).forEach(function (fnName) {
                        fnName = fnName.trim();
                        if (fnName && typeof window[fnName] === 'function') {
                            console.log('Calling post-AJAX init:', fnName);
                            try {
                                window[fnName]($btn); // можна передати елемент, якщо треба
                            } catch (e) {
                                console.error('Error in fn-inits:', fnName, e);
                            }
                        } else {
                            console.warn('No such function:', fnName);
                        }
                    });
                }
            }
        });
    });
});
