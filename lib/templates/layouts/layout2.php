<?php
$imgHtml = null;
if ($img) {
    $imgHtml = sprintf('<div class="tlp-portfolio-thum tlp-item">
                <img class="img-responsive" src="%s" title="%s">
                <div class="tlp-overlay">
                    <p class="link-icon">
                        <a class="tlp-zoom" href="%s"><i class="fa fa-search-plus"></i></a>
                        <a target="_blank" href="%s"><i class="fa fa-external-link"></i></a>
                   </p>
                </div>
            </div>', $img, $title, $imgFull, $plink);
}
?>
<div class="<?php echo esc_attr($grid) ?>">
    <div class="tlp-portfolio-item">
        <?php echo $imgHtml ?>
        <?php echo sprintf('<div class="tlp-content2 %s"><div class="tlp-content-holder"><h3><a href="%s">%s</a></h3><div class="tlp-portfolio-sd">%s</div></div></div>',
            $content_area,
            $plink,
            $title,
            $short_d
        ); ?>
    </div>
</div>