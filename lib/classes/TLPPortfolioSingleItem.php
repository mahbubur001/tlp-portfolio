<?php
if ( ! class_exists( 'TLPPortfolioSingleItem' ) ):


	class TLPPortfolioSingleItem {

		function __construct() {
			add_filter( 'the_content', array( __CLASS__, 'single_portfolio_the_content' ), 999 );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'single_portfolio_script' ) );
			add_filter( 'body_class', array( __CLASS__, 'portfolio_body_classes' ) );

		}

		public static function portfolio_body_classes( $classes ) {

			if ( is_singular( TLPPortfolio()->post_type ) || is_post_type_archive( TLPPortfolio()->post_type ) ) {
				$classes[] = 'tlp-portfolio';
			}

			return $classes;
		}

		public static function single_portfolio_script() {
			if ( is_singular( TLPPortfolio()->post_type ) || is_post_type_archive( TLPPortfolio()->post_type ) ) {
				wp_enqueue_style( 'tlp-fontawsome' );
			}
		}

		public static function single_portfolio_the_content( $content ) {
			if ( is_singular( TLPPortfolio()->post_type ) && in_the_loop() && is_main_query() ) {
				$args    = array( 'content' => $content );
				$html    = TLPPortfolio()->render( 'single-portfolio', $args, true );
				$content = apply_filters( 'tlp_portfolio_single_content', $html, $content );
			}

			return $content;
		}


	}

endif;