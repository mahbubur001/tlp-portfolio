<?php
global $post;
$designation = strip_tags( get_the_term_list( $post->ID, TLPPortfolio()->taxonomies['category'], null, ',' ) );
$settings    = get_option( TLPPortfolio()->options['settings'] );
?>
<div class="tlp-portfolio-container tlp-single-detail">
    <div class="tlp-portfolio-detail-wrap">
		<?php if ( has_post_thumbnail() ) { ?>
            <div class="tlp-portfolio-image">
                <div class="portfolio-feature-img">
					<?php the_post_thumbnail( 'full' ); ?>
                </div>
            </div>
		<?php } ?>
        <div class="portfolio-detail-desc">
            <h2 class="portfolio-title"><?php the_title(); ?></h2>
            <div class="portfolio-details"><?php echo $content; ?></div>
            <div class="others-info">
				<?php echo TLPPortfolio()->singlePortfolioMeta( $post->ID ); ?>
            </div>
			<?php
			if ( isset( $settings['social_share_enable'] ) ) {
				echo TLPPortfolio()->socialShare( get_the_permalink() );
			} ?>
        </div>

    </div>
</div>
