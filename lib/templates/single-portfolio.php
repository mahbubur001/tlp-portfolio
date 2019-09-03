<?php
get_header();

global $TLPportfolio, $post;

while ( have_posts() ) : the_post();
	$designation = strip_tags( get_the_term_list( get_the_ID(), $TLPportfolio->taxonomies['category'], null, ',' ) );
	$iID         = get_the_ID();
	$settings    = get_option( $TLPportfolio->options['settings'] );
	?>
    <div class="container tlp-portfolio tlp-portfolio-detail">
        <div class="row">
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'tlp-single-detail' ); ?>>
                <div class="tlp-col-lg-6 tlp-col-md-6 tlp-col-sm-12 tlp-col-xs-12 ">
                    <div class="portfolio-feature-img">
						<?php
						if ( has_post_thumbnail() ) {
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $iID ), 'full' );
							$img   = $image[0];
						} else {
							$img = $TLPportfolio->assetsUrl . 'images/demo.jpg';
						}
						echo "<img src='{$img}' alt='" . get_the_title() . "'>";
						?>
                    </div>
                </div>
                <div class="portfolio-detail-desc tlp-col-lg-6 tlp-col-md-6 tlp-col-sm-12 tlp-col-xs-12">
                    <h2 class="portfolio-title"><?php the_title(); ?></h2>
                    <div class="portfolio-details"><?php the_content(); ?></div>
                    <div class="others-info">
						<?php echo $TLPportfolio->singlePortfolioMeta( $post->ID ); ?>
                    </div>
					<?php
					if ( isset( $settings['social_share_enable'] ) ) {
						echo $TLPportfolio->socialShare( get_the_permalink() );
					} ?>
                </div>

            </article>
        </div>
    </div>
<?php endwhile;
get_footer();