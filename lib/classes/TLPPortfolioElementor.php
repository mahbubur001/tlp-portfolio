<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists('TLPPortfolioElementor') ):

	class TLPPortfolioElementor {
		function __construct() {
			if ( did_action( 'elementor/loaded' ) ) {
				add_action( 'elementor/widgets/widgets_registered', array( $this, 'init' ) );
			}
		}

		function init() {
		    global $TLPportfolio;
			require_once( $TLPportfolio->incPath . '/vendor/TlpPortfolioElementorWidget.php' );

			// Register widget
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new TlpPortfolioElementorWidget() );
		}
	}

endif;