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
                renderTlpPortfolioPreview();
            },
            clear: function () {
                renderTlpPortfolioPreview();
            },
            hide: true,
            palettes: true
        };
        $("#tlp_portfolio_sc_settings_meta .rt-color").wpColorPicker(cOptions);
    }


    /* rt tab active navigatiosn */
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

    /* Settings */
    $("#tlp-portfolio-settings").on('click', '#tlpSaveButton', function (e) {
        e.preventDefault();
        var button = $(this),
            form = button.closest('form'),
            responseHolder = $('#response'),
            arg = form.serialize();
        responseHolder.hide();
        AjaxCall(button, 'tlpPortSettings', arg, function (data) {
            if (data.error) {
                responseHolder.removeClass('error');
                responseHolder.show('slow').text(data.msg);
            } else {
                responseHolder.addClass('error');
                responseHolder.show('slow').text(data.msg);
            }
        });
        return false;
    });

    /* ShortCode preview */
    function useEffectImageSize() {
        /* custom image size jquery */
        var fImageSize = $("#pfp_image_size").val();
        if (fImageSize == "pfp_custom") {
            $("#pfp_custom_image_size_holder").show();
        } else {
            $("#pfp_custom_image_size_holder").hide();
        }
    }

    function useEffectDetailLink() {
        var item = $(".field-holder.pfp-detail-page-link-item");
        if ($("#pfp_detail_page_link").is(":checked")) {
            item.show();
        } else {
            item.hide();
        }
    }

    function useEffectDetailLinkType() {
        if ($("#pfp_detail_page_link").is(":checked")) {
            var val = $("#pfp_detail_page_link_type").find("input[name=pfp_detail_page_link_type]:checked").val(),
                item = $("#pfp_link_target_holder");
            if (val == "external_link") {
                item.show();
            } else {
                item.hide();
            }
        } else {
            $(".field-holder.pfp-detail-page-link-item").hide();
        }
    }

    function useEffectLayout() {
        var layout = $("#pfp_layout").val(),
            isIsotope = false,
            isCarousel = false;
        if (layout) {
            isCarousel = layout.match(/^carousel/i);
            isIsotope = layout.match(/^isotope/i);
            $("#pfp_carousel_autoplay_timeout_holder").hide();
            if (isCarousel) {
                $(".field-holder.pfp-carousel-item").show();
                $(".field-holder.pfp-isotope-item,.field-holder.pagination, #pfp_column_holder").hide();

                var autoPlay = $("#pfp_carousel_options-autoplay").prop("checked");
                if (autoPlay) {
                    $("#pfp_carousel_autoplay_timeout_holder").show();
                }

            } else if (isIsotope) {
                $(".field-holder.pfp-isotope-item,.field-holder.pagination,#pfp_column_holder").show();
                $(".field-holder.pfp-carousel-item").hide();
            } else {
                $(".field-holder.pfp-isotope-item,.field-holder.pfp-carousel-item").hide();
                $(".field-holder.pagination, #pfp_column_holder").show();
            }
        }


        if ($("#pfp_pagination").is(':checked') && !isCarousel) {
            $(".field-holder.pfp-pagination-item").show();
        } else {
            $(".field-holder.pfp-pagination-item").hide();
        }

        useEffectImageSize();
        useEffectDetailLink();
        useEffectDetailLinkType();
    }

    function AjaxCall(element, action, arg, handle) {
        var data;
        if (action) data = "action=" + action;
        if (arg) data = arg + "&action=" + action;
        if (arg && !action) data = arg;
        var n = data.search(tlp_portfolio_obj.nonceId);
        if (n < 0) {
            data = data + "&" + tlp_portfolio_obj.nonceId + "=" + tlp_portfolio_obj.nonce;
        }
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: data,
            beforeSend: function () {
                $("<span class='tlp_loading'></span>").insertAfter(element);
            },
            success: function (data) {
                $(".tlp_loading").remove();
                handle(data);
            }
        });
    }

    function TlpPortfolioPreviewAjaxCall(element, action, arg, callack) {
        var data;
        if (action) data = "action=" + action;
        if (arg) data = arg + "&action=" + action;
        if (arg && !action) data = arg;

        var n = data.search(tlp_portfolio_obj.nonceId);
        if (n < 0) {
            data = data + "&" + tlp_portfolio_obj.nonceId + "=" + tlp_portfolio_obj.nonce;
        }
        var previewHolder = $('#pfp-preview-container'),
            response = $('#pfp-response .spinner');
        $.ajax({
            type: "post",
            url: tlp_portfolio_obj.ajaxurl,
            data: data,
            beforeSend: function () {
                previewHolder.addClass('loading');
                response.addClass('is-active');
            },
            success: function (data) {
                previewHolder.removeClass('loading');
                response.removeClass('is-active');
                callack(data);
            }
        });
    }

    function renderTlpPortfolioPreview() {
        var target = $('#tlp_portfolio_sc_settings_meta');
        if (target.length) {
            var data = target.find('input[name],select[name],textarea[name]').serialize();
            // Add Shortcode ID
            data = data + '&' + $.param({'sc_id': $('#post_ID').val() || 0});
            TlpPortfolioPreviewAjaxCall(null, 'tlp_portfolio_preview_ajax_call', data, function (data) {
                if (!data.error) {
                    $("#pfp-preview-container").html(data.data);
                    initTlpPortfolio();
                }
            });
        }
    }

    $("#pfp_image_size").on('change', function () {
        useEffectImageSize();
    });
    $("#pfp_layout").on('change', function () {
        useEffectLayout();
    });
    $("#pfp_pagination").on('change', function () {
        if (this.checked) {
            $(".field-holder.pfp-pagination-item").show();
        } else {
            $(".field-holder.pfp-pagination-item").hide();
        }
    });

    $("#pfp_carousel_options-autoplay").on('change', function () {
        if (this.checked) {
            $("#pfp_carousel_autoplay_timeout_holder").show();
        } else {
            $("#pfp_carousel_autoplay_timeout_holder").hide();
        }
    });

    $("#pfp_detail_page_link").on('change', function () {
        useEffectDetailLink();
    });
    $("#pfp_detail_page_link_type").on("click", "input[type='radio']", function () {
        useEffectDetailLinkType();
    });

    $("#tlp_portfolio_sc_settings_meta").on('change', 'select,input', function () {
        renderTlpPortfolioPreview();
    });
    $("#tlp_portfolio_sc_settings_meta").on("input propertychange", function () {
        renderTlpPortfolioPreview();
    });
    useEffectLayout();
    renderTlpPortfolioPreview();

})(jQuery);
