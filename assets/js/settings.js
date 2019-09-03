(function ($) {
    if ($('.tlp-color').length) {
        $('.tlp-color').wpColorPicker();
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
        if(cats.length){
            sc = sc + ' cat="' + cats.toString() + '"';
        }
        if($('input:checkbox[name="image"]').is(':checked')){
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
