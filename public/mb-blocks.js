(function ($) {

    // .mb-items container that belongs directly to this $wrap (not nested)
    function ownMbItems($wrap) {
        return $wrap.find('.mb-items').filter(function () {
            return $(this).closest('.mb-wrap')[0] === $wrap[0];
        }).first();
    }

    // [name] inputs that belong directly to this $item (not nested mb-items)
    function ownInputs($item) {
        return $item.find('[name]').filter(function () {
            return $(this).closest('.mb-item')[0] === $item[0];
        });
    }

    function getNextIdx($items) {
        var max = -1;
        $items.find('> .mb-item').each(function () {
            var v = parseInt($(this).data('mb-idx'), 10);
            if (!isNaN(v) && v > max) max = v;
        });
        return max + 1;
    }

    // Replace last occurrence of search in str
    function replaceLast(str, search, replacement) {
        var idx = str.lastIndexOf(search);
        if (idx === -1) return str;
        return str.slice(0, idx) + replacement + str.slice(idx + search.length);
    }

    function callFnInits(str) {
        if (!str) return;
        str.split(/\s*,\s*/).forEach(function (fn) {
            if (typeof window[fn] === 'function') window[fn]();
        });
    }

    function updateAddBtn($wrap) {
        var max = parseInt($wrap.data('mb-max'), 10);
        if (isNaN(max)) return;
        var count = ownMbItems($wrap).find('> .mb-item:not(.mb-item--removing)').length;
        $wrap.find('.mb-btn-add').filter(function () {
            return $(this).closest('.mb-wrap')[0] === $wrap[0];
        }).prop('disabled', count >= max).toggleClass('disabled', count >= max);
    }

    function updateDeleteBtns($wrap) {
        var min = parseInt($wrap.data('mb-min'), 10);
        if (isNaN(min)) return;
        var $active = ownMbItems($wrap).find('> .mb-item:not(.mb-item--removing)');
        var count = $active.length;
        $active.find('> .mb-item-controls .mb-btn-delete')
            .prop('disabled', count <= min).toggleClass('disabled', count <= min);
    }

    function updateItemNumbers($wrap) {
        var i = 1;
        ownMbItems($wrap).find('> .mb-item').each(function () {
            $(this).find('> .mb-item-controls .mb-item-num').text(
                $(this).hasClass('mb-item--removing') ? '' : '#' + i++
            );
        });
    }

    // Init on load
    $(function () {
        $('.mb-wrap').each(function () {
            updateAddBtn($(this));
            updateDeleteBtns($(this));
            updateItemNumbers($(this));
        });
    });

    // Update numbers after drag-drop sort
    $(document).on('sortupdate', '.mb-items', function () {
        updateItemNumbers($(this).closest('.mb-wrap'));
    });

    // Add item
    $(document).on('click', '.mb-wrap .mb-btn-add', function (e) {
        e.preventDefault();
        var $btn = $(this);
        if ($btn.prop('disabled')) return;

        var $wrap = $btn.closest('.mb-wrap');
        var $items = ownMbItems($wrap);
        var tpl = $items.children('template.mb-template')[0];
        if (!tpl) return;

        var idx = getNextIdx($items);
        // Support custom placeholder ($j, $k...) via data-mb-placeholder on <template>
        var placeholder = $(tpl).data('mb-placeholder') || '$i';
        var html = tpl.innerHTML.split(placeholder).join(idx);

        $items.find('> .mb-empty').remove();
        $items.append(html);

        callFnInits($wrap.data('fn-inits'));
        updateAddBtn($wrap);
        updateDeleteBtns($wrap);
        updateItemNumbers($wrap);
    });

    // Delete item (confirm or undo)
    $(document).on('click', '.mb-wrap .mb-btn-delete', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var confirmMsg = $btn.data('confirm');
        var $item = $btn.closest('.mb-item');
        var $items = $item.closest('.mb-items');
        var $wrap = $items.closest('.mb-wrap');

        if (confirmMsg) {
            if (!confirm(confirmMsg)) return;
            $item.remove();
            if (!$items.find('> .mb-item').length) {
                $items.append('<p class="mb-empty">Елементів не додано</p>');
            }
            updateAddBtn($wrap);
            updateDeleteBtns($wrap);
            updateItemNumbers($wrap);
            return;
        }

        $item.addClass('mb-item--removing');
        $item.find('> .mb-item-controls .mb-btn-delete, > .mb-item-controls .mb-btn-clone').prop('disabled', true);
        $item.find('> .mb-item-controls .mb-item-actions').prepend(
            '<button type="button" class="btn btn-xs btn-warning mb-btn-undo">Скасувати</button>'
        );

        var timer = setTimeout(function () {
            $item.remove();
            if (!$items.find('> .mb-item').length) {
                $items.append('<p class="mb-empty">Елементів не додано</p>');
            }
            updateAddBtn($wrap);
            updateDeleteBtns($wrap);
            updateItemNumbers($wrap);
        }, 2000);

        $item.data('mb-remove-timer', timer);
        updateDeleteBtns($wrap);
        updateAddBtn($wrap);
        updateItemNumbers($wrap);
    });

    // Undo delete
    $(document).on('click', '.mb-wrap .mb-btn-undo', function (e) {
        e.preventDefault();
        var $item = $(this).closest('.mb-item');
        var $wrap = $item.closest('.mb-wrap');

        clearTimeout($item.data('mb-remove-timer'));
        $item.removeClass('mb-item--removing');
        $item.find('.mb-btn-undo').remove();
        $item.find('> .mb-item-controls .mb-btn-delete, > .mb-item-controls .mb-btn-clone').prop('disabled', false);

        updateDeleteBtns($wrap);
        updateAddBtn($wrap);
        updateItemNumbers($wrap);
    });

    // Clone item
    $(document).on('click', '.mb-wrap .mb-btn-clone', function (e) {
        e.preventDefault();
        var $wrap = $(this).closest('.mb-wrap');
        var $items = ownMbItems($wrap);
        var $item = $(this).closest('.mb-item');
        var oldIdx = parseInt($item.data('mb-idx'), 10);
        var newIdx = getNextIdx($items);
        var fnInits = $wrap.data('fn-inits');

        var selectValues = {};
        ownInputs($item).filter('select').each(function () {
            selectValues[$(this).attr('name')] = $(this).val();
        });

        var $clone = $item.clone(false);

        $clone.find('.select2-container').remove();
        $clone.find('select').removeClass('select2-hidden-accessible')
            .removeAttr('data-select2-id aria-hidden tabindex');

        $clone.removeClass('mb-item--removing').removeData('mb-remove-timer');
        $clone.find('.mb-btn-undo').remove();
        $clone.find('> .mb-item-controls .mb-btn-delete, > .mb-item-controls .mb-btn-clone').prop('disabled', false);

        $clone.attr('data-mb-idx', newIdx);

        var nestedRe = new RegExp('\\[' + oldIdx + '\\]\\[', 'g');

        // Own-level inputs: replaceLast targets own level regardless of depth
        ownInputs($clone).each(function () {
            var oldName = $(this).attr('name');
            var newName = replaceLast(oldName, '[' + oldIdx + '][', '[' + newIdx + '][');
            $(this).attr('name', newName);
            if ($(this).is('select') && selectValues[oldName] !== undefined) {
                $(this).val(selectValues[oldName]);
            }
        });
        // Nested inputs: replace first occurrence (outer index position)
        $clone.find('[name]').not(ownInputs($clone)).each(function () {
            $(this).attr('name', $(this).attr('name').replace('[' + oldIdx + '][', '[' + newIdx + ']['));
        });
        // Update inner templates so new items added to the clone use correct outer index
        $clone.find('template.mb-template').each(function () {
            this.innerHTML = this.innerHTML.replace(nestedRe, '[' + newIdx + '][');
        });
        $clone.find('[id]').each(function () {
            var id = $(this).attr('id') || '';
            $(this).attr('id', id.replace(new RegExp('(\\D)' + oldIdx + '(\\D|$)', 'g'), '$1' + newIdx + '$2'));
        });
        $clone.find('label[for]').each(function () {
            var f = $(this).attr('for') || '';
            $(this).attr('for', f.replace(new RegExp('(\\D)' + oldIdx + '(\\D|$)', 'g'), '$1' + newIdx + '$2'));
        });
        $clone.find('[data-field-name]').each(function () {
            $(this).attr('data-field-name',
                $(this).attr('data-field-name').replace('[' + oldIdx + '][', '[' + newIdx + '][')
            );
        });

        $items.find('> .mb-empty').remove();
        $item.after($clone);

        callFnInits(fnInits);
        updateAddBtn($wrap);
        updateDeleteBtns($wrap);
        updateItemNumbers($wrap);
    });

    // Reindex + inject weight before submit
    $(document).on('submit', 'form:has(.mb-wrap)', function () {
        $(this).find('.mb-item--removing').each(function () {
            clearTimeout($(this).data('mb-remove-timer'));
            $(this).remove();
        });

        $(this).find('.mb-items').each(function () {
            $(this).find('> .mb-item').each(function (newIdx) {
                var $item = $(this);
                var oldIdx = parseInt($item.data('mb-idx'), 10);

                // Nested inputs: first occurrence = outer index position (cascade)
                $item.find('[name]').not(ownInputs($item)).each(function () {
                    var n = $(this).attr('name');
                    $(this).attr('name', n.replace('[' + oldIdx + '][', '[' + newIdx + ']['));
                });
                // Own inputs: last occurrence = own level index (correct for any depth)
                ownInputs($item).each(function () {
                    var n = $(this).attr('name');
                    $(this).attr('name', replaceLast(n, '[' + oldIdx + '][', '[' + newIdx + ']['));
                });

                // basePath only from own-level to avoid picking nested item paths
                var basePath = null;
                ownInputs($item).each(function () {
                    var m = $(this).attr('name').match(/^(.+\[\d+\])\[/);
                    if (m) { basePath = m[1]; return false; }
                });

                if (basePath) {
                    var wName = basePath + '[weight]';
                    var $w = $item.find('> input[name="' + wName + '"]');
                    if ($w.length) {
                        $w.val(newIdx);
                    } else {
                        $item.append('<input type="hidden" name="' + wName + '" value="' + newIdx + '">');
                    }
                }

                $item.attr('data-mb-idx', newIdx);
            });
        });
    });

}(jQuery));
