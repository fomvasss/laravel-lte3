var initJsVerificationSlugField = function () {},
    initColorpicker = function () {},
    initSortableY =  function () {},
    initSelect2 = function () {},
    initCheckbox = function () {},
    initSelect2Tree = function () {},
    initTreeview = function () {}

$(function () {
    'use strict';

    const USE_TOASTR = true;
    const LANGUAGE = $('html').attr('lang') || 'en';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Show message
    function lteAlert(status, msg) {
        if (USE_TOASTR) {
            toastr[status](msg)
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
        $(this).hide().show(100);
    });

    // LTE: Set active item in Sidebar menu
    if ($('.nav-sidebar.js-activeable').length) {
        var pathnameUrl = window.location.pathname,
            url = window.location.href,
            path = url.split('?')[0];

        $('.nav-sidebar.js-activeable li>a').each(function () {
            var aHref = $(this).attr("href"),
                regexp = $(this).data('pat') ? new RegExp($(this).data('pat')) : false;

            if (path === aHref || pathnameUrl === aHref || regexp && regexp.test(url)) {
                $(this).closest('.nav-pills>.nav-item').addClass('menu-open');
                $(this).addClass('active');
                $(this).closest('.menu-open').children('a').addClass('active');
            }
        });
    }

    // Component: formOpen
    // Autosabmit form after change file
    $(document).on('change', '.js-form-submit-file-changed input[type="file"]', function() {
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
        var url = $(this).data('url'),
            target = $(this).data('target'),
            initFunctionsStr = $(this).data('fn-inits'); // "fn1,fn2,..."
        $.get(url, function (data) {
            $(`${target} .modal-content`).html(data.html)
            $(`${target}`).modal();

            if (initFunctionsStr) {
                console.log(initFunctionsStr)
                initFunctionsStr.split(/\s*,\s*/).forEach(function (str) {
                    console.log('Init function: ' + str);
                    window[str]();
                });
            }
        });
    })

    // Validate form before Save
    // Add in php controller after validation: if($request->prevalidate) {return 'ok';}
    $(document).on('click', '.js-form-submit-prevalidate', function (e) {
        e.preventDefault();
        var $form = $(this).closest('form');
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize() + '&prevalidate=1',
            success: function(data) {
                console.log(data)
                $form.submit()
            },
            error: function (data) {
                var response = JSON.parse(data.responseText);

                if (response && response.errors !== undefined) {
                    $.each(response.errors, function (key, value) {
                        value.forEach(function (item, /*i, value*/) {
                            console.log(item)
                            lteAlert('error', item)
                        });
                    })
                }
            }
        });
    });

    // Change select
    $(document).on('change', '.js-change-url-submit', function () {
        window.location = $(this).val();
    })

    // Change radio
    $(document).on('change', '.js-radio-submit', function (e) {
        e.preventDefault();
        var $this = $(this),
            strConfirm = $this.data('confirm') ? confirm($this.data('confirm')) : true;
        if (strConfirm && ($this.data('url') || $this.attr('value'))) {
            var $form = $('#js-action-form');
            console.log($this.data('url') || $this.attr('value'))
            $form.attr('action', $this.data('url') || $this.attr('value')).submit();
        }
        return false;
    })

    $(document).on('click', '.js-click-submit', function (e) {
        e.preventDefault()
        var $form = $('#js-action-form'),
            $this = $(this),
            method = $this.data('method') || 'POST',
            url = $(this).data('url') || $(this).attr('href'),
            strConfirm = $this.data('confirm') ? confirm($this.data('confirm')) : true,
            destination = $(this).data('destination')

        if (url && $form && strConfirm) {
            $form.find('input[name="_method"]').val(method)
            if (destination) {
                $form.find('input.f-dest').val(destination)
            }
            $form.attr('action', url).submit()
        }
    });

    $('[data-toggle="tooltip"]').tooltip()

    var sortableNestedVar = $('.js-sortable-nested').sortableNested({
            //group: 'serialization',
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
                console.log(data, url, method)

                if (url) {
                    $.ajax({
                        method: method,
                        url: url,
                        dataType: 'json',
                        data: {'data': data[0]},
                        success: function (data) {
                            lteAlert('success', data.message);
                        },
                        error: function () {
                            lteAlert('error', 'Error SortableNested Ajax!')
                        }
                    })
                }
            }
        })


    // jQuery UI sortable
    initSortableY = function() {
        $( ".sortable-y" ).sortable({
            distance: 5,
            placeholder: "sortable-placeholder",
            axis: 'y',
            update: function (event, ui) {
                var $this = $(this),
                    url = $this.data('url'),
                    inputWeightClass = $this.data('input-weight-class'),
                    method = $this.data('method') || 'POST',
                    order = $this.sortable('toArray');

                console.log(order);

                if (inputWeightClass) {
                    $this.find('.'+inputWeightClass).each(function(i) {
                        $(this).val(i)
                    })
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
                    })
                }
            }
        });
    }
    initSortableY();

    // Component: File
    $(document).on('click', '.f-file .f-file-item .js-btn-delete', function(e) {
        e.preventDefault();
        if (confirm('Confirm?')) {
            var $this = $(this);
            $this.siblings('.js-input-delete').val($this.data('id'));
            $this.closest('.f-file-item').hide()
        }
    })
    $(document).on('click', '.f-media-file .f-file-item .js-btn-delete', function(e) {
        e.preventDefault();
        if (confirm('Confirm?')) {
            var $this = $(this);
            $this.siblings('.js-input-delete').val($this.data('id'));
            $this.closest('.f-file-item').hide()
        }
    })

    // Show info about input chuse file
    $(document).on('change', '.js-files-input', function () {
        var $this = $(this),
            $info = $this.closest('.f-wrap').find('.js-files-info');

        if ($info.length) {
            var text = '';
            $info.text(text);
            $.each(this.files, function(index, value) {
                text = text + `${value.name} (${humanFileSize(value.size)}), `;
            });
            $info = $info.text('Selected: ' + text.slice(0, -2))
        }
    });
    function humanFileSize(bytes, si=false, dp=1) {
        const thresh = si ? 1000 : 1024;

        if (Math.abs(bytes) < thresh) {
          return bytes + ' B';
        }

        const units = si
          ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
          : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        let u = -1;
        const r = 10**dp;

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
                if(this.checked) {
                    $wrap.find('input.js-slug-field-input')
                        .prop('readonly', false)
                        .prop('disabled', false)
                } else {
                    $wrap.find('input.js-slug-field-input')
                        .prop('readonly', true)
                        .prop('disabled', true)
                }
            })
        }
    }
    initJsVerificationSlugField();

    // Component: Colorpicker
    initColorpicker = function() {
        $('.f-colorpicker').colorpicker()
        $(document).on('colorpickerChange', '.f-colorpicker', function(event) {
          $('.f-colorpicker .fa-square').css('color', event.color.toString());
        })
    }
    initColorpicker();

    // Component: Select2
    // https://select2.org/
    initSelect2 = function () {
        $('.f-select2').each(function () {
            var $this = $(this),

                urlSave = $this.data('url-save'),
                urlSuggest = $this.data('url-suggest'),
                urlTags = $this.data('url-tags');

            // Autosave after change
            if (urlSave) {
                var fieldName = $this.data('name'),
                    method = $this.data('method-save') || 'POST',
                    $select2 = $this.select2({
                        language: LANGUAGE,
                        tags: false
                    });

                    $this.on('change', function (e) {
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
                    tokenSeparators: tokenSeparators,

                    ajax: urlTags ? {
                        delay: 250,
                        url: urlTags,
                        dataType: 'json',
                        processResults: function(data) {
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
            }
            else if (urlSuggest) {
                $this.select2({
                    language: LANGUAGE,
                    tags: false,
                    ajax: {
                        delay: 250,
                        url: urlSuggest,
                        dataType: 'json'
                    }
                });
            } else {
                $this.select2({
                    language: LANGUAGE,
                    tags: false
                });
            }
        });

        // Displaying blocks depending on the selection in the selection
        $('.f-select2-wrap .js-map-blocks').each(function () {
            if ($(this).find(':selected')) {
                toggleSelectableBlocks($(this).find(':selected').val(), $(this).data('map'))
            }
        })
        $('.f-radiogroup .js-map-blocks').each(function () {
            if ($(this).is(':checked')) {
                console.log($(this).val(), $(this).data('map'));
                toggleSelectableBlocks($(this).val(), $(this).data('map'))
            }
        })
    }
    $(document).on('change', '.js-map-blocks', function () {
        if ($(this).data('map')) {
            toggleSelectableBlocks($(this).val(), $(this).data('map'))
        }
    });
    function toggleSelectableBlocks($val, selectBlocksMap) {
        for (var key in selectBlocksMap) {
            //Pace.restart()
            var id = 0;
            if ($val === key) {
                for (id in selectBlocksMap[key]) {
                    //console.log(selectBlocksMap[key][id])
                    $(selectBlocksMap[key][id]).show()
                }
            } else {
                for (id in selectBlocksMap[key]) {
                    $(selectBlocksMap[key][id]).hide()
                }
            }
        }
    }
    initSelect2();

    //$(document).on('change', '.f-radiogroup')

    // Component: checkbox
    initCheckbox = function() {
        $('.f-checkbox-ajax').each(function(){
            var $this = $(this),
                url = $this.data('url-save'),
                method = $this.data('method-save') || 'POST',
                fieldName = $this.attr('name'),
                rawFieldName = $this.data('raw-name'),
                format = $this.data('format');

            // AJAX Save
            if (url) {
                $this.on('change', function () {
                    var value = this.checked ? 1 : 0,
                        data = format === 'name,value' ? {name: rawFieldName, value: value} : {[rawFieldName] : value};
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
                    })
                });
            }
        });
    }
    initCheckbox();

    // Component: Select2Tree
    // https://github.com/clivezhg/select2-to-tree
    initSelect2Tree = function() {
        $('.f-select2-tree-wrap').each(function () {
            var $this = $(this),
                $input = $this.find('.f-select2-tree-input'),
                url = $input.data('url'),
                methodGet = $input.data('method-get') || 'GET',
                valFld = $input.data('valFld') || 'id',
                labelFld = $input.data('labelFld') || 'name',
                incFld = $input.data('incFld') || 'children';

            $.ajax({
                method: methodGet,
                url: url,
                dataType: 'json',
                data: {data: ''},
                success: function (data) {
                    $input.select2ToTree({
                        treeData: {
                            dataArr: data.data,
                            dftVal: data.selected,
                            valFld: valFld,
                            labelFld: labelFld,
                            'incFld': incFld
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
    initTreeview = function() {
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
                    $inputs.html('')
                    var array = []
                    obj.forEach(element => {
                        $inputs.append('<input type="hidden" name="' + fieldName + '[]" value="' + element.id + '" />')
                    })
                    return array;
                }
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
                            collapseIcon:'fas fa-minus',
                            expandIcon:'fas fa-plus',
                            checkedIcon:'far fa-check-square',
                            uncheckedIcon:'far fa-square'
                        })

                        getCheckedIds($tree.treeview('getChecked'))

                        $tree.on('nodeChecked', function (event, data) {
                            getCheckedIds($(this).treeview('getChecked'))
                        })
                        $tree.on('nodeUnchecked', function (event, data) {
                            getCheckedIds($(this).treeview('getChecked'))
                        })
                    },
                    error: function () {
                        console.log('Error Treeview Ajax!')
                    },
                    complete: function () {
                        $base.find('.overlay').fadeOut(200)
                    }
                });
            } else if (staticData) {
                $tree.treeview({
                    data: staticData,
                    showIcon: showIcon,
                    showCheckbox: showCheckbox,
                    collapseIcon:'fas fa-minus',
                    expandIcon:'fas fa-plus',
                    checkedIcon:'far fa-check-square',
                    uncheckedIcon:'far fa-square'
                })

                getCheckedIds($tree.treeview('getChecked'))

                $tree.on('nodeChecked', function (event, data) {
                    getCheckedIds($(this).treeview('getChecked'))
                })
                $tree.on('nodeUnchecked', function (event, data) {
                    getCheckedIds($(this).treeview('getChecked'))
                })
                $base.find('.overlay').fadeOut(200)
            }

        });
    }
    function makeTreeview($wrap, data) {
        // TODO
    }
    initTreeview();

   // Component: Links
    $(document).on('click', '.field-links .js-btn-add', function (e) {
        e.preventDefault()
        var n = $(this).parents('.field-links').find('.js-btn-add').index(this),
            length = $(this).parents('.field-links').find('.js-btn-add').length,
            fieldName = $(this).parents('.field-links').data('field-name'),
            keyKey = $(this).parents('.field-links').data('key'),
            keyValue = $(this).parents('.field-links').data('value'),
            placeholderKey = $(this).parents('.field-links').data('placeholder-key'),
            placeholderValue = $(this).parents('.field-links').data('placeholder-value'),
            item = '<tr class="item">'
                + '<td class="align-middle text-center"><i class="fa fa-arrows-alt-v"></i></td>'
                + '<td>'
                + '<div class="input-group input-group-sm">'
                + '<input name="' + fieldName + '[' + (length) + '][' + keyKey + ']" class="form-control" placeholder="' + placeholderKey + '" type="text">'
                + '<input name="' + fieldName + '[' + (length) + '][' + keyValue + ']" class="form-control" placeholder="' + placeholderValue + '" type="text">'
                + '<input type="hidden" name="" value="0">'
                + '<span class="input-group-append">'
                + '<button type="button" class="btn btn-success btn-flat js-btn-add"><i class="fas fa-plus"></i></button>'
                + '<button type="button" class="btn btn-danger btn-flat js-btn-remove"><i class="fas fa-minus"></i></button>'
                + '</span>'
                + '</div>'
                + '</td>'
                + '</tr>"'

        $(this).parents('.field-links').find('.item').eq(n).after(item)
    })
    $(document).on('click', '.field-links .js-btn-remove', function (e) {
        e.preventDefault()

        var length = $(this).parents('.field-links').find('.js-btn-remove').length;
        if (length > 1) {
            var n = $(this).parents('.field-links').find('.js-btn-remove:not(.first)').index(this)

            $(this).parents('.field-links').find('.item').eq(n).remove()
        }
    })

    // Component: Lists
    $(document).on('click', '.field-linear-list .js-btn-add', function (e) {
        e.preventDefault()
        console.log(1)
        var n = $(this).parents('.field-linear-list').find('.js-btn-add').index(this),
            length = $(this).parents('.field-linear-list').find('.js-btn-add').length,
            fieldName = $(this).parents('.field-linear-list').data('field-name'),
            placeholderValue = $(this).parents('.field-linear-list').data('placeholder-value'),
            item = '<tr class="item">'
                + '<td class="align-middle text-center"><i class="fa fa-arrows-alt-v"></i></td>'
                + '<td>'
                + '<div class="input-group input-group-sm">'
                + '<input name="' + fieldName + '[' + (length) + ']" placeholder="' + placeholderValue + ' ' + (parseInt(length) + 1) + '" class="form-control" type="text">'
                + '<span class="input-group-append">'
                + '<button type="button" class="btn btn-success btn-flat js-btn-add"><i class="fas fa-plus"></i></button>'
                + '<button type="button" class="btn btn-danger btn-flat js-btn-remove"><i class="fas fa-minus"></i></button></span>'
                + '</div>'
                + '</td>'
                + '</tr>"'
        $(this).parents('.field-linear-list').find('.item').eq(n).after(item)
    })
    $(document).on('click', '.field-linear-list .js-btn-remove', function (e) {
        e.preventDefault()

        var length = $(this).parents('.field-linear-list').find('.js-btn-remove').length;
        if (length > 1) {
            var n = $(this).parents('.field-linear-list').find('.js-btn-remove:not(.first)').index(this)

            $(this).parents('.field-linear-list').find('.item').eq(n).remove()
        }
    })
});
