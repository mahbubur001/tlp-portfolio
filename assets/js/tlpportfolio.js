(function ($, window) {

    window.pfpFixLazyLoadToAll = function () {
        $('.tlp-portfolio-container').each(function () {
            // jetpack Lazy load
            $(this).find('img.jetpack-lazy-image:not(.jetpack-lazy-image--handled)').each(function () {
                $(this).addClass('jetpack-lazy-image--handled').removeAttr('srcset').removeAttr('data-lazy-src').attr('data-lazy-loaded', 1);
            });

            //
            $(this).find('img.lazyload').each(function () {
                var src = $(this).attr('data-src') || '';
                if (src) {
                    $(this).attr('src', src).removeClass('lazyload').addClass('lazyloaded');
                }
            });

            $(this).find("img[data-lazy-src]:not(.lazyloaded)").each(function () {
                $imgUrl = $(this).data("lazy-src");
                $(this).attr('src', $imgUrl).addClass('lazyloaded');
            });
        });
    };

    window.pfpFixLazyLoad = function (container) {
        if (container === undefined)
            return;

        // jetpack Lazy load
        container.find('img.jetpack-lazy-image:not(.jetpack-lazy-image--handled)').each(function () {
            $(this).addClass('jetpack-lazy-image--handled').removeAttr('srcset').removeAttr('data-lazy-src').attr('data-lazy-loaded', 1);
        });

        //
        container.find('img.lazyload').each(function () {
            var src = $(this).attr('data-src') || '';
            if (src) {
                $(this).attr('src', src).removeClass('lazyload').addClass('lazyloaded');
            }
        });

        container.find("img[data-lazy-src]:not(.lazyloaded)").each(function () {
            var imgUrl = $(this).data("lazy-src");
            $(this).attr('src', imgUrl).addClass('lazyloaded');
        });
    };

    window.pfpOverlayIconResize = function () {
        $('.tlp-item').each(function () {
            var holder_height = $(this).height();
            var a_height = $(this).find('.tlp-overlay .link-icon').height();
            var h = (holder_height - a_height) / 2;
            $(this).find('.link-icon').css('margin-top', h + 'px');
        });
    };

    window.initTlpPortfolio = function () {
        $(".tlp-portfolio-container").each(function () {
            var container = $(this),
                isIsotope = container.hasClass("is-isotope"),
                isCarousel = container.find('is-carousel');
            pfpFixLazyLoad(container);
            setTimeout(function () {
                container.imagesLoaded().progress(function (instance, image) {
                    container.trigger('pfp_image_loading');
                }).done(function (instance) {
                    container.trigger('pfp_item_before_load');
                    if (isIsotope) {
                        var isoHolder = container.find('.tlp-portfolio-isotope');
                        if (isoHolder.length) {
                            isoHolder.isotope({
                                itemSelector: '.tlp-isotope-item',
                            });
                            container.trigger('pfp_item_after_load');
                            setTimeout(function () {
                                isoHolder.isotope();
                            }, 10);
                            var $isotopeButtonGroup = container.find('.tlp-portfolio-isotope-button');
                            $isotopeButtonGroup.on('click', 'button', function (e) {
                                e.preventDefault();
                                var filterValue = $(this).attr('data-filter');
                                isoHolder.isotope({filter: filterValue});
                                $(this).parent().find('.selected').removeClass('selected');
                                $(this).addClass('selected');
                            });
                        }
                    }
                    setTimeout(function () {
                        $(document).trigger("pfp_loaded");
                    }, 10);
                });
            }, 10);
        });
    };

    window.initPfpMagicPopup = function () {
        if ($.fn.magnificPopup) {
            $('.tlp-portfolio-container').each(function () {
                $(this).magnificPopup({
                    delegate: '.tlp-zoom',
                    type: 'image',
                    preload: [1, 3],
                    gallery: {
                        enabled: true
                    }
                });
            });
        }
    };

    $(document).on('pfp_loaded pfp_item_after_load', function () {
        initPfpMagicPopup();
        pfpOverlayIconResize();
    });
    $(function () {
        initPfpMagicPopup();
        initTlpPortfolio();
    });
    $(window).on('resize', function () {
        $(".tlp-portfolio-container").trigger("pfp_loaded");
    });

})(jQuery, window);
