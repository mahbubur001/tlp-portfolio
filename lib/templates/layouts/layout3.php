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
 * @var string $image_area
 * @var string $content_area
 */
$imgHtml = null;
if ( $img ) {
	$imgHtml = sprintf( '<div class="tlp-portfolio-thum tlp-item">
                %s
                <div class="tlp-overlay">
                    <p class="link-icon">
                        <a class="tlp-zoom" href="%s"><i class="fa fa-search-plus"></i></a>
                        %s
                   </p>
                </div>
            </div>', wp_kses_post($img), $imgFull,
		$link ?
			sprintf( '<a href="%s"%s><i class="fa fa-external-link"></i></a>', $item_link, $link_target ? " target='{$link_target}'" :
				null )
			: null );
}
?>
<div class="<?php echo esc_attr( $grid ) ?>">
    <div class="tlp-portfolio-item">
		<?php echo $imgHtml ?>
		<?php echo sprintf( '<div class="tlp-content">
                                        <div class="tlp-content-holder">
                                            %s
                                        </div>
                                    </div>',
			$link ?
				sprintf( '<h3><a href="%s"%s>%s</a></h3>', $item_link, $link_target ? " target='{$link_target}'" : null, $title ) :
				sprintf( '<h3>%s</h3>', $title ) ); ?>
    </div>
</div>
