/* global jQuery, wp */
let on_icon_click_fn = function (targetEl) {
    let target = jQuery(targetEl);
    target.parent().find('.selected').not(targetEl).removeClass('selected');
    target.toggleClass('selected');
    if (target.hasClass('selected')) {
        jQuery('#icon_library').val(target.data('font-library'));
        jQuery('#icon_css').val(target.data('font-css'));
        jQuery('#icon_style').val(target.data('font-style'));
    } else {
        jQuery('#icon_library').val('');
        jQuery('#icon_css').val('');
        jQuery('#icon_style').val('');
    }
};
jQuery(function ($) {
    let temp_data = [];
    let total = 0;
    let acf_field;
    let template = wp.template('icon-library-icon-font');
    let icon_library_dialog = $('#icon-dialog');
    if (icon_library_dialog.length === 0) {
        return;
    }
    $('.gs-acf-icon-field-remove').on('click', function () {
        $('#icon_css').val('');
        $('#icon_style').val('');
        $('#icon_library').val('');
        let acf_field = $(this).data('acf-field');
        $('#' + acf_field).val('');
        $('div[data-acf-field="' + acf_field+'"]').html('');
    });

    icon_library_dialog.html(wp.template('icon-library-frame')());
    let library_list = $('.icon-library-list');
    if (library_list.length === 0) {
        return;
    }
    let library_list_wrapper = $('.icon-library-list-wrapper');
    if (library_list_wrapper.length === 0) {
        return;
    }
    $('.gs-acf-icon-field,.gs-acf-icon-field-select').on('click', function (e) {
        e.preventDefault();
        acf_field = $(this).data('acf-field');
        temp_data = gs_cacf_font_data;
        let val = $('.icon-library-frame-side-menu').find('input:checked').first().val();
        let data = val.split(':');
        let library = data[0];
        let style = data[1];
        let value = $('#icon-search-input').val();
        if (!template) {
            template = wp.template('icon-library-icon-font')
        }
        if (value) {
            temp_data = gs_cacf_font_data.filter(function (element) {
                    return new RegExp(".*" + value + '.*', "i").test(element.label)
                        && (library === 'all' || (library === element.library && element.styles.includes(style)));
                }
            );
            total = 36;
            if (total < temp_data.length) {
                $('.icon-library-load-more').show();
            } else {
                $('.icon-library-load-more').hide();
            }
            library_list.html(template({fonts: temp_data.slice(0, total), library: library, style: style}))
        } else {
            temp_data = gs_cacf_font_data.filter(function (element) {
                    return (library === 'all' || (library === element.library && element.styles.includes(style)));
                }
            );
            total = 36;
            if (total < temp_data.length) {
                $('.icon-library-load-more').show();
            } else {
                $('.icon-library-load-more').hide();
            }
            library_list.html(template({fonts: temp_data.slice(0, total), library: library, style: style}));
        }
        $('.icon-search-result-total').text(temp_data.length);
        icon_library_dialog.dialog('open');
    });
    icon_library_dialog.dialog({
        title: 'Icon Library',
        dialogClass: 'wp-dialog',
        autoOpen: false,
        draggable: false,
        width: '70vw',
        modal: true,
        resizable: false,
        closeOnEscape: true,
        position: {
            my: "center",
            at: "center",
            of: window
        },
        open: function () {
            // close dialog by clicking the overlay behind it
            $('.ui-dialog-titlebar-close').on('click', function () {
                icon_library_dialog.dialog('close');
            });

            $('.ui-widget-overlay').on('click', function () {
                icon_library_dialog.dialog('close');
            });
        },
        create: function () {
            // style fix for WordPress admin
            $('.ui-dialog-titlebar-close').addClass('ui-button');
        },
    });
    $('.gs-acf-icon-field-remove').on('click', function () {
        $('#icon_css').val('');
        $('#icon_style').val('');
        $('#icon_library').val('');
        let acf_field = $(this).data('acf-field');
        $('#' + acf_field).val('');
        $('div[data-acf-field="' + acf_field+'"]').html('');
    });
    $('.attach-icon-to-acf-field').on('click', function () {
        let css_el = $('#icon_css');
        let style_el = $('#icon_style');
        $('#' + acf_field).val($('#icon_library').val() + ':' + css_el.val() + ':' + style_el.val());
        let css = css_el.val();
        let style = style_el.val();
        let css_class = style.replace('%', css, style);
        if (css_class === style) {
            css_class = css;
        }
        $('#preview-' + acf_field).html(
            '<i class="' + css_class + '"></i>'
        );
        icon_library_dialog.dialog('close');
    });

    $('#icon-search-input').on('keyup', function () {
        let val = $('.icon-library-frame-side-menu').find('input:checked').first().val();
        let data = val.split(':');
        let library = data[0];
        let style = data[1];
        let self = $(this);
        temp_data = gs_cacf_font_data.filter(function (element) {
                return new RegExp(".*" + self.val() + '.*', "i").test(element.label)
                    && (library === 'all' || (library === element.library && element.styles.includes(style)));
            }
        );
        $('.icon-search-result-total').text(temp_data.length);
        if (temp_data.length > 0) {
            total = 36;
            if (total < temp_data.length) {
                $('.icon-library-load-more').show();
            } else {
                $('.icon-library-load-more').hide();
            }
            library_list.html(template({fonts: temp_data.slice(0, total), library: library, style: style}))
        } else {
            library_list.html('<p><strong>No icons found</strong></p>');
        }
    });
    $('.icon-library-selection').on('click', function () {
        let self = $(this);
        let val = self.find('input').first().val();
        let data = val.split(':');
        let library = data[0];
        let style = data[1];
        if (library === 'all')
            temp_data = gs_cacf_font_data;
        else {
            temp_data = gs_cacf_font_data.filter(element => element.styles.includes(style) && element.library === library);
        }
        if (temp_data.length > 0) {
            $('.icon-search-result-total').text(temp_data.length);
            library_list.html(template({fonts: temp_data.slice(0, 36), library: library, style: style}))
        } else {
            library_list.html('<p><strong>No icons found</strong></p>');
        }
    });
    library_list_wrapper.on('click', '.icon-library-load-more', function () {
        if (total === 0)
            total = 36;
        let val = $('.icon-library-frame-side-menu').find('input:checked').first().val();
        let data = val.split(':');
        let library = data[0];
        let style = data[1];
        total += 36;

        library_list.html(template({fonts: temp_data.slice(0, total), library: library, style: style}));
        if (total >= temp_data.length) {
            total = temp_data.length;
            $('.icon-library-load-more').hide();
        }
    });
});
let initiated = false;
acf.addAction('append', function () {
    if (initiated)
        return;
    initiated = true;
    jQuery(function ($) {
        let temp_data = [];
        let total = 0;
        let acf_field;
        let template = wp.template('icon-library-icon-font');
        let icon_library_dialog = $('#icon-dialog');
        icon_library_dialog.html(wp.template('icon-library-frame')());
        let library_list = $('.icon-library-list');
        let library_list_wrapper = $('.icon-library-list-wrapper');
        if (library_list_wrapper.length === 0) {
            return;
        }

        $('.gs-acf-icon-field,.gs-acf-icon-field-select').on('click', function (e) {
            e.preventDefault();
            acf_field = $(this).data('acf-field');
            temp_data = gs_cacf_font_data;
            let val = $('.icon-library-frame-side-menu').find('input:checked').first().val();
            let data = val.split(':');
            let library = data[0];
            let style = data[1];
            let value = $('#icon-search-input').val();
            if (!template) {
                template = wp.template('icon-library-icon-font')
            }
            if (value) {
                temp_data = gs_cacf_font_data.filter(function (element) {
                        return new RegExp(".*" + value + '.*', "i").test(element.label)
                            && (library === 'all' || (library === element.library && element.styles.includes(style)));
                    }
                );
                library_list.html(template({fonts: temp_data.slice(0, 36), library: library, style: style}))
            } else {
                temp_data = gs_cacf_font_data.filter(function (element) {
                        return (library === 'all' || (library === element.library && element.styles.includes(style)));
                    }
                );
                library_list.html(template({fonts: temp_data.slice(0, 36), library: library, style: style}));
            }
            $('.icon-search-result-total').text(temp_data.length);
            icon_library_dialog.dialog('open');
        });
        icon_library_dialog.dialog({
            title: 'Icon Library',
            dialogClass: 'wp-dialog',
            autoOpen: false,
            draggable: false,
            width: '70vw',
            modal: true,
            resizable: false,
            closeOnEscape: true,
            position: {
                my: "center",
                at: "center",
                of: window
            },
            open: function () {
                // close dialog by clicking the overlay behind it
                $('.ui-dialog-titlebar-close').on('click', function () {
                    icon_library_dialog.dialog('close');
                });

                $('.ui-widget-overlay').on('click', function () {
                    icon_library_dialog.dialog('close');
                });
            },
            create: function () {
                // style fix for WordPress admin
                $('.ui-dialog-titlebar-close').addClass('ui-button');
            },
        });
        $('.gs-acf-icon-field-remove').on('click', function () {
            $('#icon_css').val('');
            $('#icon_style').val('');
            $('#icon_library').val('');
            $('#' + acf_field).val('');
            $('div[data-acf-field="' + acf_field+'"]').html('');
        });
        $('.attach-icon-to-acf-field').on('click', function () {
            let css_el = $('#icon_css');
            let style_el = $('#icon_style');
            $('#' + acf_field).val($('#icon_library').val() + ':' + css_el.val() + ':' + style_el.val());
            let css = css_el.val();
            let style = style_el.val();
            let css_class = style.replace('%', css, style);
            if (css_class === style) {
                css_class = css;
            }

            $('div[data-acf-field="' + acf_field+'"]').html(
                '<i class="' + css_class + '"></i>'
            );
            icon_library_dialog.dialog('close');
        });

        $('#icon-search-input').on('keyup', function () {
            let val = $('.icon-library-frame-side-menu').find('input:checked').first().val();
            let data = val.split(':');
            let library = data[0];
            let style = data[1];
            let self = $(this);
            temp_data = gs_cacf_font_data.filter(function (element) {
                    return new RegExp(".*" + self.val() + '.*', "i").test(element.label)
                        && (library === 'all' || (library === element.library && element.styles.includes(style)));
                }
            );
            $('.icon-search-result-total').text(temp_data.length);
            if (temp_data.length > 0) {
                total = 36;
                library_list.html(template({fonts: temp_data.slice(0, total), library: library, style: style}));
                if (total < temp_data.length) {
                    $('.icon-library-load-more').show();
                }
            } else {
                library_list.html('<p><strong>No icons found</strong></p>');
            }
        });
        $('.icon-library-selection').on('click', function () {
            let self = $(this);
            let val = self.find('input').first().val();
            let data = val.split(':');
            let library = data[0];
            let style = data[1];
            if (library === 'all')
                temp_data = gs_cacf_font_data;
            else {
                temp_data = gs_cacf_font_data.filter(element => element.styles.includes(style) && element.library === library);
            }
            if (temp_data.length > 0) {
                $('.icon-search-result-total').text(temp_data.length);
                total = 36;
                library_list.html(template({fonts: temp_data.slice(0, total), library: library, style: style}));
                if (total < temp_data.length) {
                    $('.icon-library-load-more').show();
                }
            } else {
                library_list.html('<p><strong>No icons found</strong></p>');
            }
        });

        library_list_wrapper.on('click', '.icon-library-load-more', function () {
            if (total === 0)
                total += 36;
            let val = $('.icon-library-frame-side-menu').find('input:checked').first().val();
            let data = val.split(':');
            let library = data[0];
            let style = data[1];
            total += 36;
            library_list.html(template({fonts: temp_data.slice(0, total), library: library, style: style}));
            if (total >= temp_data.length) {
                $('.icon-library-load-more').hide();
                total = temp_data.length;
            }
        });
    });
});
