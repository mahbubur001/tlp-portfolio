(function ($) {
    if ($('.tlp-color').length && $.fn.wpColorPicker) {
        $('.tlp-color').wpColorPicker();
    }
    if ($("select.rt-select2").length && $.fn.select2) {
        $("select.rt-select2").select2({
            theme: "classic",
            dropdownAutoWidth: true,
            width: '100%'
        });
    }
    if ($("#scg-wrapper .tlp-color").length) {
        var cOptions = {
            defaultColor: false,
            change: function (event, ui) {
                createShortCode();
            },
            clear: function () {
                createShortCode();
            },
            hide: true,
            palettes: true
        };
        $("#scg-wrapper .tlp-color").wpColorPicker(cOptions);
    }
    if ($("#tlp_portfolio_sc_settings_meta .rt-color").length && $.fn.wpColorPicker) {
        var cOptions = {
            defaultColor: false,
            change: function (event, ui) {
                renderTlpTeamPreview();
            },
            clear: function () {
                renderTlpTeamPreview();
            },
            hide: true,
            palettes: true
        };
        $("#tlp_portfolio_sc_settings_meta .rt-color").wpColorPicker(cOptions);
    }



    /* rt tab active navigation */
    $("#tlp_portfolio_sc_settings_meta .rt-tab-nav li").on('click', 'a', function (e) {
        e.preventDefault();
        var container = $(this).parents('.rt-tab-container');
        var nav = container.children('.rt-tab-nav');
        var content = container.children(".rt-tab-content");
        var $this, $id;
        $this = $(this);
        $id = $this.attr('href');
        content.hide();
        nav.find('li').removeClass('active');
        $this.parent().addClass('active');
        container.find($id).show();
    });

    imageSize();
    $(window).on('load', function () {
        createShortCode();
    });
    $("#rt-feature-img-size").on('change', function () {
        imageSize();
    });
    $("#scg-wrapper").on('change', 'select,input', function () {
        createShortCode();
    });
    $("#scg-wrapper").on('change', 'input:checkbox[name="image"]', function () {
        createShortCode();
    });
    $("#scg-wrapper").on("input propertychange", function () {
        createShortCode();
    });

    function createShortCode() {
        var sc = "[tlpportfolio";
        $("#scg-wrapper").find('input[name],select[name]').each(function (index, item) {
            var v = $(this).val(),
                name = this.name;
            if (name == "cat[]" || name == "image") {
                return;
            }
            sc = v ? sc + " " + name + "=" + '"' + v + '"' : sc;
        });
        var cats = [];
        $('input:checkbox[name="cat[]"]').each(function () {
            if ($(this).is(':checked')) {
                cats.push($(this).val());
            }
        });
        if (cats.length) {
            sc = sc + ' cat="' + cats.toString() + '"';
        }
        if ($('input:checkbox[name="image"]').is(':checked')) {
            var imgV = $('input:checkbox[name="image"]').val();
            sc = sc + ' image="' + imgV + '"';
        }

        sc = sc + "]";
        $("#sc-output textarea").val(sc);
    }

    $("#sc-output textarea").on('click', function () {
        $(this).select();
        document.execCommand('copy');
    });

    function imageSize() {
        var size = $("#rt-feature-img-size").val();
        if (size == "rt_custom") {
            $(".rt-custom-image-size-wrap").show();
        } else {
            $(".rt-custom-image-size-wrap").hide();
        }
    }
})(jQuery);

function tlpPortfolioSettings(e) {
    jQuery('#response').hide();
    arg = jQuery(e).serialize();
    bindElement = jQuery('#tlpSaveButton');
    AjaxCall(bindElement, 'tlpPortSettings', arg, function (data) {
        if (data.error) {
            jQuery('#response').removeClass('error');
            jQuery('#response').show('slow').text(data.msg);
        } else {
            jQuery('#response').addClass('error');
            jQuery('#response').show('slow').text(data.msg);
        }
    });
}

function AjaxCall(element, action, arg, handle) {
    if (action) data = "action=" + action;
    if (arg) data = arg + "&action=" + action;
    if (arg && !action) data = arg;
    data = data;
    var n = data.search("tlp_nonce");
    if (n < 0) {
        data = data + "&tlp_nonce=" + tpl_port_var.tpl_nonce;
    }
    jQuery.ajax({
        type: "post",
        url: ajaxurl,
        data: data,
        beforeSend: function () {
            jQuery("<span class='tlp_loading'></span>").insertAfter(element);
        },
        success: function (data) {
            jQuery(".tlp_loading").remove();
            handle(data);
        }
    });
}
