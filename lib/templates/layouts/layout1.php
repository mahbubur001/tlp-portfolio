<?php
/**
 * @var string $img
 * @var string $link
 * @var string $item_link
 * @var string $link_target
 * @var string $title
 * @var string $imgFull
 * @var string $grid
 * @var string $isoFilter
 * @var string $short_d
 */
$imgHtml = null;
if ($img) {
    $imgHtml = sprintf('<div class="tlp-portfolio-thum tlp-item">
                %s
                <div class="tlp-overlay">
                    <p class="link-icon">
                        <a class="tlp-zoom" href="%s"><i class="fa fa-search-plus"></i></a>
                        %s
                   </p>
                </div>
            </div>',
        wp_kses_post($img),
        esc_url($imgFull),
        $link ? sprintf('<a href="%s"%s><i class="fa fa-external-link"></i></a>', esc_url($item_link), $link_target ? " target='{$link_target}'" : null) : null
    );
}
?>
<div class="<?php echo esc_attr($grid) ?>">
    <div class="tlp-portfolio-item">
        <?php echo $imgHtml ?>
        <?php echo sprintf('<div class="tlp-content">
                                    <div class="tlp-content-holder">
                                        %s
                                        <div class="tlp-portfolio-sd">%s</div>
                                    </div>
                                   </div>',
            $link ?
                sprintf('<h3><a href="%s"%s>%s</a></h3>', esc_url($item_link), $link_target ? " target='{$link_target}'" : null, esc_html($title)) :
                sprintf('<h3>%s</h3>', esc_html($title)),
            $short_d
        ); ?>
    </div>
</div>
