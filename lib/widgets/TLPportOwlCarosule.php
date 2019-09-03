<?php

if ( ! class_exists( 'TLPportOwlCarosule' ) ):


	/**
	 *
	 */
	class TLPportOwlCarosule extends WP_Widget {
		private $caroA = array();

		/**
		 * TLP TEAM widget setup
		 */
		public function __construct() {

			$widget_ops = array( 'classname'   => 'widget_tlp_port_owl_carousel',
			                     'description' => __( 'Display the portfolio as carousel.', 'tlp-portfolio' )
			);
			parent::__construct( 'widget_tlp_port_owl_carousel', __( 'TPL Portfolio', 'tlp-portfolio' ), $widget_ops );

			add_action( 'wp_enqueue_scripts', array( $this, 'carousel_script' ) );

		}

		function carousel_script() {
			global $TLPportfolio;
			wp_enqueue_style( 'tlpportfolio-css', $TLPportfolio->assetsUrl . 'css/tlpportfolio.css' );
		}

		/**
		 * display the widgets on the screen.
		 */
		function widget( $args, $instance ) {

			$caroID = $args['widget_id'] . '-port-carousel';

			global $TLPportfolio;

			extract( $args );

			$total = ( isset( $instance['total'] ) ? ( $instance['total'] ? (int) $instance['total'] : 8 ) : 8 );
			$limit = ( isset( $instance['letter-limit'] ) ? (int) $instance['letter-limit'] : 0 );
			echo $before_widget;
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', ( isset( $instance['title'] ) ? $instance['title'] : "Portfolio" ) ) . $args['after_title'];
			}
			?>
            <div class="tlp-portfolio">
				<?php
				$args_q = array(
					'post_type'      => $TLPportfolio->post_type,
					'post_status'    => 'publish',
					'posts_per_page' => $total,
					'orderby'        => 'date',
					'order'          => 'DESC',
				);

				$teamQuery = new WP_Query( $args_q );
				$html      = null;
				if ( $teamQuery->have_posts() ) {
					$settings = get_option( $TLPportfolio->options['settings'] );
					$fSize    = ! empty( $settings['feature_img_size'] ) ? $settings['feature_img_size'] : $TLPportfolio->options['tlp-portfolio-thumb'];
					$html     .= "<div class='rt-container-fluid tlp-portfolio'>";
					$html     .= '<div class="row">';
					$html     .= "<div id='$caroID' class='slider'>";
					while ( $teamQuery->have_posts() ) : $teamQuery->the_post();
						$title    = get_the_title();
						$plink    = get_permalink();
						$sDetails = get_post_meta( get_the_ID(), 'short_description', true );
						if ( has_post_thumbnail() ) {
							$image     = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $fSize );
							$timg      = $image[0];
							$imageFull = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
							$imgFull   = $imageFull[0];
						} else {
							$timg = $TLPportfolio->assetsUrl . 'images/demo.jpg';
						}

						$grid = ( ! empty( $instance['number'] ) ? (int) $instance['number'] : 4 );
						$grid = round( 12 / $grid );
						$html .= "<div class='tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 tlp-equal-height'>";
						$html .= '<div class="tlp-portfolio-item">';
						$html .= '<div class="tlp-portfolio-thum tlp-item">';
						$html .= '<img class="img-responsive" src="' . $timg . '" alt="' . $title . '"/>';
						$html .= '<div class="tlp-overlay">';
						$html .= '<p class="link-icon">';
						$html .= '<a class="tlp-zoom" href="' . $imgFull . '"><i class="fa fa-search-plus"></i></a>';
						$html .= '<a target="_blank" href="' . $plink . '"><i class="fa fa-external-link"></i></a>';
						$html .= '</p>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= sprintf( '<div class="tlp-content"><div class="tlp-content-holder"><h3><a href="%s">%s</a></h3><div class="tlp-portfolio-sd">%s</div></div></div>',
							$plink,
							$title,
							$limit > 0 ? substr( $sDetails, 0, $limit ) : html_entity_decode( $sDetails )
						);
						$html .= '</div>';
						$html .= '</div>';


					endwhile;
					wp_reset_postdata();

					$html .= '</div>';
					$html .= '</div>';
					$html .= '</div>';
				} else {
					$html .= "<p>" . __( 'No post found', 'tlp-portfolio' ) . "</p>";
				}
				echo $html;

				?>
            </div>

			<?php
			echo $after_widget;

			$this->caroA[] = array(
				'id'  => $caroID,
				'opt' => array(
					'items'       => ( isset( $instance['number'] ) ? ( $instance['number'] ? (int) $instance['number'] : 4 ) : 4 ),
					'speed'       => ( isset( $instance['speed'] ) ? ( $instance['speed'] ? (int) $instance['speed'] : 800 ) : 800 ),
					'auto_play'   => ( isset( $instance['auto_play'] ) ? 'true' : 'false' ),
					'pagination'  => ( isset( $instance['pagination'] ) ? 'true' : 'false' ),
					'navigation'  => ( isset( $instance['navigation'] ) ? 'true' : 'false' ),
					'stop_hover'  => ( isset( $instance['stop_hover'] ) ? 'true' : 'false' ),
					'responsive'  => ( isset( $instance['responsive'] ) ? 'true' : 'false' ),
					'auto_height' => ( isset( $instance['auto_height'] ) ? 'true' : 'false' ),
					'lazy_load'   => ( isset( $instance['lazy_load'] ) ? 'true' : 'false' )
				)
			);
			add_action( 'wp_footer', array( $this, 'register_scripts' ) );
			add_action( 'wp_footer', array( $this, 'low_footer_script' ), 100 );
		}

		function register_scripts() {
			wp_enqueue_style( array( 'tlp-fontawsome' ) );
			wp_enqueue_script( array(
				'jquery',
				'tlp-magnific',
				'tlp-owl-carousel',
				'tlp-portfolio'
			) );
		}

		function low_footer_script() {
			foreach ( $this->caroA as $ca ) {
				if ( isset( $ca ) && is_array( $ca ) ) {
					echo $this->croScript( $ca );
				}
			}
		}

		function croScript( $ca ) {
			$caro = null;
			$caro .= "<script>";
			$caro .= '(function($){
							$("#' . $ca['id'] . '").owlCarousel({
							    // Most important owl features
							    items : ' . $ca['opt']['items'] . ',
							    paginationSpeed : ' . $ca['opt']['speed'] . ',
							    autoPlay : ' . $ca['opt']['auto_play'] . ',
							    stopOnHover : ' . $ca['opt']['stop_hover'] . ',
							    navigation : ' . $ca['opt']['navigation'] . ',
							    navigationText : ["<i class=\'fa fa-chevron-left\'></i>","<i class=\'fa fa-chevron-right\'></i>"],
							    pagination : ' . $ca['opt']['pagination'] . ',
							    responsive: ' . $ca['opt']['responsive'] . ',
							    lazyLoad : ' . $ca['opt']['lazy_load'] . ',
							    autoHeight : ' . $ca['opt']['auto_height'] . '
							});
		    			})(jQuery)';
			$caro .= "</script>";

			return $caro;
		}


		function form( $instance ) {

			$defaults = array(
				'title'        => "Portfolio",
				'number'       => 4,
				'total'        => 8,
				'letter-limit' => 0,
				'speed'        => 800,
				'auto_play'    => 1,
			);
			global $TLPportfolio;
			foreach ( $TLPportfolio->owl_property() as $key => $item ) {
				$defaults[ $key ] = 1;
			}
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'tlp-portfolio' ); ?></label>
                <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo @$instance['title']; ?>"
                       style="width:100%;"/></p>

            <p>
                <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of item per slide:', 'tlp-portfolio' ); ?></label>
                <input type="text" size="2" id="<?php echo $this->get_field_id( 'number' ); ?>"
                       name="<?php echo $this->get_field_name( 'number' ); ?>"
                       value="<?php echo $instance['number']; ?>"/></p>

            <p>
            <p>
                <label for="<?php echo $this->get_field_id( 'letter-limit' ); ?>"><?php _e( 'Letter limit for short description:', 'tlp-portfolio' ); ?></label>
                <input type="text" size="2" id="<?php echo $this->get_field_id( 'letter-limit' ); ?>"
                       name="<?php echo $this->get_field_name( 'letter-limit' ); ?>"
                       value="<?php echo $instance['letter-limit']; ?>"/></p>

            <p>
                <label for="<?php echo $this->get_field_id( 'total' ); ?>"><?php _e( 'Total Number of item:', 'tlp-portfolio' ); ?></label>
                <input type="text" size="2" id="<?php echo $this->get_field_id( 'total' ); ?>"
                       name="<?php echo $this->get_field_name( 'total' ); ?>"
                       value="<?php echo $instance['total']; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'speed' ); ?>"><?php _e( 'Slide Speed:', 'tlp-portfolio' ); ?></label>
                <input type="text" size="4" id="<?php echo $this->get_field_id( 'speed' ); ?>"
                       name="<?php echo $this->get_field_name( 'speed' ); ?>"
                       value="<?php echo $instance['speed']; ?>"/>
            </p>
			<?php
			echo "<p>";
			foreach ( $TLPportfolio->owl_property() as $key => $value ) {
				$checked = ( $instance[ $key ] ? "checked" : null );
				$html    = null;
				$html    .= '<input type="checkbox" ' . $checked . ' value="1" class="checkbox" id="' . $this->get_field_id( $key ) . '" name="' . $this->get_field_name( $key ) . '">
                            <label for="' . $this->get_field_id( $key ) . '">' . $value . '</label><br>';

				echo $html;
			}
			echo "</p>";
		}

		public function update( $new_instance, $old_instance ) {

			$instance                 = array();
			$instance['title']        = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['number']       = ( ! empty( $new_instance['number'] ) ) ? (int) ( $new_instance['number'] ) : '';
			$instance['letter-limit'] = ( ! empty( $new_instance['letter-limit'] ) ) ? (int) ( $new_instance['letter-limit'] ) : '';
			$instance['total']        = ( ! empty( $new_instance['total'] ) ) ? (int) ( $new_instance['total'] ) : '';
			$instance['speed']        = ( ! empty( $new_instance['speed'] ) ) ? (int) ( $new_instance['speed'] ) : '';

			global $TLPportfolio;
			$options = $TLPportfolio->owl_property();
			if ( ! empty( $options ) ) {
				foreach ( $options as $key => $value ) {
					$instance[ $key ] = ( ! empty( $new_instance[ $key ] ) ) ? (int) ( $new_instance[ $key ] ) : '';
				}
			}

			return $instance;
		}
	}
endif;
