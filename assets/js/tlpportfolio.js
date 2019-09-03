(function ($) {
    $(document).ready(function () {
        $('.tlp-portfolio').each(function () { // the containers for all your galleries
            $(this).magnificPopup({
                delegate: '.tlp-zoom', // the selector for gallery item
                type: 'image',
                preload: [1, 3],
                gallery: {
                    enabled: true
                }
            });
        });
    });

    $('.tlp-layout-isotope').each(function () {
        var isoHolder = $(this);
        var $isotop = $('.tlp-portfolio-isotope', isoHolder).imagesLoaded(function () {
            $.when(HeightResize()).done(function () {
                $isotop.isotope({
                    itemSelector: '.tlp-single-item',
                });
            });
        });
        $('#tlp-portfolio-isotope-button', isoHolder).on('click', 'button', function () {
            var filterValue = $(this).attr('data-filter');
            $isotop.isotope({filter: filterValue});
            $(this).parent().find('.selected').removeClass('selected');
            $(this).addClass('selected');
        });
    });

    $(window).resize(function () {
        overlayIconResize();
        HeightResize();
    });
    $(window).load(function () {
        overlayIconResize();
        HeightResize();
    });

    function overlayIconResize() {
        $('.tlp-item').each(function () {
            var holder_height = $(this).height();
            var a_height = $(this).find('.tlp-overlay .link-icon').height();
            var h = (holder_height - a_height) / 2;
            $(this).find('.link-icon').css('margin-top', h + 'px');
        });
    }

    function HeightResize() {
        if ($(window).width() > 768) {
            fixLazyLoad();
            $(document).imagesLoaded(function () {
                $(".tlp-portfolio").each(function () {
                    var tlpMaxH = 0;
                    $(this).children('.row').children(".tlp-equal-height").children(".tlp-portfolio-item").height("auto");
                    $(this).children('.row').children(".tlp-equal-height").each(function () {
                        var $thisH = $(this).children(".tlp-portfolio-item").outerHeight();
                        if ($thisH > tlpMaxH) {
                            tlpMaxH = $thisH;
                        }
                    });
                    $(this).children('.row').children(".tlp-equal-height").children(".tlp-portfolio-item").height(tlpMaxH + "px");
                });

                var tlpMaxH = 0;
                $(".tlp-portfolio-isotope").children(".tlp-equal-height").children(".tlp-portfolio-item").height("auto");
                $(".tlp-portfolio-isotope > .tlp-equal-height").each(function () {
                    var $thisH = $(this).children(".tlp-portfolio-item").outerHeight();
                    if ($thisH > tlpMaxH) {
                        tlpMaxH = $thisH;
                    }
                });
                $(".tlp-portfolio-isotope").children(".tlp-equal-height").children(".tlp-portfolio-item").height(tlpMaxH + "px");
            });

        } else {
            $(".tlp-portfolio").children('.row').children(".tlp-equal-height").children(".tlp-portfolio-item").height("auto");
            $(".tlp-portfolio-isotope").children(".tlp-equal-height").children(".tlp-portfolio-item").height("auto");
        }
    }

    function fixLazyLoad() {
        $(".tlp-portfolio img[data-lazy-src]:not(.lazyloaded)").each(function () {
           $imgUrl = $(this).data("lazy-src");
           $(this).attr('src', $imgUrl).addClass('lazyloaded');
        });
    }

})(jQuery);
