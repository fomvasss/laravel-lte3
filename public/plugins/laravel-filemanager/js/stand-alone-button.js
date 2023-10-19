(function( $ ){

    $.fn.filemanager = function(type, options) {
        this.on('click', function(e) {
            var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager',
                $this = $(this),
                $wrap = $this.closest('.f-wrap'),
                $wrapItem = $this.closest('.f-wrap-item'),
                $input = $wrapItem.find('input.js-lfm-input'),
                previewBlock = $wrapItem.find('.preview-block'),
                category = $wrap.data('lfm-category') || 'file',
                isImage = $wrap.data('is-image') || false,
                trimHost = $wrap.data('trim-host') || false,
                host = window.location.protocol + '//' + window.location.host
            ;

            window.open(route_prefix + '?type=' + category, 'FileManager', 'width=900,height=600');
            window.SetUrl = function (items) {
                console.log(items)
                var file_path = items.map(function (item) {
                    if (trimHost) {
                        return item.url.replace(host, '');
                    }
                    return item.url;
                }).join(',');

                // set the value of the desired input to image url
                $input.val('').val(file_path).trigger('change');

                // // clear previous preview
                previewBlock.html('');
                if (isImage) {
                    // // set or change the preview image src
                    items.forEach(function (item) {
                        previewBlock.append(
                            $('<img>').css('height', '60px').attr('src', item.thumb_url)
                        );
                    });
                } else {
                    // // set or change the preview image src
                    items.forEach(function (item) {
                        previewBlock.append(
                            $('<span>'+item.name.substr(-4)+'</span>')
                        );
                    });
                }
                // trigger change event
                previewBlock.trigger('change');
            };
            return false;
        });
    }

})(jQuery);
