var initJsVerificationSlugField = function () {
    },
    initColorpicker = function () {
    },
    initSortableY = function () {
    },
    initSelect2 = function () {
    },
    initCheckbox = function () {
    },
    initSelect2Tree = function () {
    },
    initTreeview = function () {
    }

$(function () {
    'use strict';

    const USE_TOASTR = true;
    const LANGUAGE = $('html').attr('lang') || 'en';

    localStorage.setItem('AdminLTE:Demo:MessageShowed', (Date.now()) + (15 * 60 * 1000));

    $(document).ajaxStart(function () {
        Pace.restart();
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
        lteAlert('success', 'Copied!');
    });

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
    function setSidebarActiveable($naw, $item) {
        $naw.find('li>a').removeClass('active');
        $item.closest('.nav-pills>.nav-item').addClass('menu-open');
        $item.addClass('active');
        $item.closest('.menu-open').children('a').addClass('active');

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
    function setActiveableUrl($wrap, $item, tag, activeClass) {
        $wrap.find(tag).removeClass(activeClass);
        $item.addClass(activeClass);
        return true;
    }

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
            $(`${target} .modal-content`).html(data.html)
            $(`${target}`).modal();

            if (initFunctionsStr) {
                console.log(initFunctionsStr)
                initFunctionsStr.split(/\s*,\s*/).forEach(function (str) {
                    console.log('Init function: ' + str);
                    window[str]();
                });
            }
            return true;
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
            success: function (data) {
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

    // Click submit
    $(document).on('click', '.js-click-submit', function (e) {
        e.preventDefault();
        var $form = $('#js-action-form'),
            $this = $(this),
            method = $this.data('method') || 'POST',
            url = $(this).data('url') || $(this).attr('href'),
            strConfirm = $this.data('confirm') ? confirm($this.data('confirm')) : true,
            destination = $(this).data('destination')

        if (url && strConfirm && $form) {
            $form.find('input[name="_method"]').val(method)
            if (destination) {
                $form.find('input.f-dest').val(destination)
            }
            $form.attr('action', url).submit()
        }
    });
    $(document).on('click', '.js-click-url', function (e) {
        e.preventDefault();
        var $this = $(this),
            method = $this.data('method') || 'GET',
            url = $(this).data('url') || $(this).attr('href'),
            strConfirm = $this.data('confirm') ? confirm($this.data('confirm')) : true;

        if (url && strConfirm) {
            window.location = url;
        }
    });

    $('[data-toggle="tooltip"]').tooltip()

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
            console.log(data, url, method)

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
                        lteAlert('error', 'Error SortableNested Ajax!')
                    }
                })
            }
        }
    })


    // jQuery UI sortable
    initSortableY = function () {
        $(".sortable-y").sortable({
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
                    $this.find('.' + inputWeightClass).each(function (i) {
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
    $(document).on('click', '.f-file .f-file-item .js-btn-delete', function (e) {
        e.preventDefault();
        if (confirm('Confirm?')) {
            var $this = $(this);
            $this.siblings('.js-input-delete').val($this.data('id'));
            $this.closest('.f-file-item').hide()
        }
    })
    $(document).on('click', '.f-media-file .f-file-item .js-btn-delete', function (e) {
        e.preventDefault();
        if (confirm('Confirm?')) {
            var $this = $(this);
            $this.siblings('.js-input-delete').val($this.data('id'));
            $this.closest('.f-file-item').hide()
        }
    })

    // LFM
    $(document).on('click', '.f-lfm .f-wrap-item .js-btn-clear', function (e) {
        e.preventDefault();
        if (confirm('Confirm?')) {
            var $this = $(this),
                $wrapItem = $this.closest('.f-wrap-item');
            $wrapItem.find('.js-lfm-input').val('')
            $wrapItem.find('.preview-block').html('')
        }
    })
    $(document).on('click', '.f-lfm .f-wrap-item .js-btn-delete', function (e) {
        e.preventDefault();
        if (confirm('Confirm?')) {
            var $this = $(this),
                $wrapItem = $this.closest('.f-wrap-item');
            if ($this.data('id')) {
                $wrapItem.closest('.js-input-delete').val($this.data('id'));
            }
            $wrapItem.find('.js-lfm-input').remove()
            $wrapItem.hide()
        }
    })
    $(document).on('click', '.f-lfm .js-btn-add', function (e) {
        e.preventDefault()
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
                +'<a href="#" class="btn btn-danger btn-xs js-btn-delete"><i class="fas fa-times"></i></a>'
                +'</td>'
                +'</tr>'

        $wrap.find('.f-wrap-items').find('.f-wrap-item').eq(length-1).after(item)
        $wrap.find('.f-lfm-btn').filemanager();
    })

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
            })
        }
    }
    initJsVerificationSlugField();

    // Component: Colorpicker
    initColorpicker = function () {
        $('.f-colorpicker').colorpicker().each(function () {
            var $this = $(this)
            $this.colorpicker().on('colorpickerChange', function(event) {
                $this.find('.fa-square').css('color', event.color.toString());
            });
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
        Pace.restart();
        for (var key in selectBlocksMap) {
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
    initCheckbox = function () {
        $('.f-checkbox-ajax').each(function () {
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
                    })
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
                            collapseIcon: 'fas fa-minus',
                            expandIcon: 'fas fa-plus',
                            checkedIcon: 'far fa-check-square',
                            uncheckedIcon: 'far fa-square'
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
                    collapseIcon: 'fas fa-minus',
                    expandIcon: 'fas fa-plus',
                    checkedIcon: 'far fa-check-square',
                    uncheckedIcon: 'far fa-square'
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
                + '<td class="align-middle text-center"><i class="fa fa-arrows-alt-v"></i></td>'
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

        $parent.find('.item').eq(n).after(item)
    })
    $(document).on('click', '.f-links .js-btn-delete', function (e) {
        e.preventDefault()

        var $parent = $(this).parents('.f-links'),
            length = $parent.find('.js-btn-delete').length;
        if (length > 1) {
            var n = $parent.find('.js-btn-delete:not(.first)').index(this)

            $parent.find('.item').eq(n).remove()
        }
    })

    // Component: Lists
    $(document).on('click', '.f-lists .js-btn-add', function (e) {
        e.preventDefault()
        console.log(1)
        var $parent = $(this).parents('.f-lists'),
            n = $parent.find('.js-btn-add').index(this),
            length = $parent.find('.js-btn-add').length,
            fieldName = $parent.data('field-name'),
            placeholderValue = $parent.data('placeholder-value'),
            item = '<tr class="item">'
                + '<td class="align-middle text-center"><i class="fa fa-arrows-alt-v"></i></td>'
                + '<td class="w-100">'
                + '<div class="input-group input-group-sm">'
                + '<input name="' + fieldName + '[' + (length) + ']" placeholder="' + placeholderValue + ' ' + (parseInt(length) + 1) + '" class="form-control" type="text">'
                + '<span class="input-group-append">'
                + '<button type="button" class="btn btn-success btn-flat js-btn-add"><i class="fas fa-plus"></i></button>'
                + '<button type="button" class="btn btn-danger btn-flat js-btn-delete"><i class="fas fa-minus"></i></button></span>'
                + '</div>'
                + '</td>'
                + '</tr>"'
        $parent.find('.item').eq(n).after(item)
    })
    $(document).on('click', '.f-lists .js-btn-delete', function (e) {
        e.preventDefault()

        var $parent = $(this).parents('.f-lists'),
            length = $parent.find('.js-btn-delete').length;
        if (length > 1) {
            var n = $parent.find('.js-btn-delete:not(.first)').index(this)

            $parent.find('.item').eq(n).remove()
        }
    })
});
