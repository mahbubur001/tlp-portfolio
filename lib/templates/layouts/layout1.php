<?php
$imgHtml = null;
if ( $img ) {
	$imgHtml = sprintf( '<div class="tlp-portfolio-thum tlp-item">
                <img class="img-responsive" src="%s" title="%s">
                <div class="tlp-overlay">
                    <p class="link-icon">
                        <a class="tlp-zoom" href="%s"><i class="fa fa-search-plus"></i></a>
                        %s
                   </p>
                </div>
            </div>', $img, $title, $imgFull,
		$link ? sprintf( '<a href="%s"%s><i class="fa fa-external-link"></i></a>', $item_link, $link_target ? " target='{$link_target}'" : null ) : null
	);
}
?>
<div class="<?php echo esc_attr( $grid ) ?>">
    <div class="tlp-portfolio-item">
		<?php echo $imgHtml ?>
		<?php echo sprintf( '<div class="tlp-content">
                                    <div class="tlp-content-holder">
                                        %s
                                        <div class="tlp-portfolio-sd">%s</div>
                                    </div>
                                   </div>',
			$link ?
				sprintf( '<h3><a href="%s"%s>%s</a></h3>', $item_link, $link_target ? " target='{$link_target}'" : null, $title ) :
				sprintf( '<h3>%s</h3>', $title ),
			$short_d
		); ?>
    </div>
</div>
