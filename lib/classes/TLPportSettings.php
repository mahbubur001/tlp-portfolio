<?php
if ( ! class_exists( 'TLPportSettings' ) ):
	/**
	 *
	 */
	class TLPportSettings {

		function __construct() {
			add_action( 'init', array( $this, 'tlp_pluginInit' ) );
			add_action( 'plugins_loaded', array( $this, 'tlp_portfolio_load_text_domain' ) );
			add_action( 'admin_menu', array( $this, 'tlp_menu_register' ) );
			add_action( 'wp_ajax_tlpPortSettings', array( $this, 'tlpPortSettings' ) );
			add_filter( 'plugin_action_links_' . TLP_PORTFOLIO_PLUGIN_ACTIVE_FILE_NAME,
				array( $this, 'tlp_portfolio_marketing' ) );
		}

		function tlp_portfolio_marketing( $links ) {
			$links[] = '<a target="_blank" href="' . esc_url( 'http://demo.radiustheme.com/wordpress/plugins/tlp-portfolio/' ) . '">Demo</a>';
			$links[] = '<a target="_blank" href="' . esc_url( 'https://radiustheme.com/how-to-setup-and-configure-tlp-portfolio-free-version-for-wordpress/' ) . '">Documentation</a>';
			$links[] = '<a target="_blank" style="color: #39b54a;font-weight: 700;"  href="' . esc_url( 'https://radiustheme.com/tlp-portfolio-pro-for-wordpress/' ) . '">Get Pro</a>';

			return $links;
		}


		function tlp_pluginInit() {
			global $TLPportfolio;
			$settings = get_option( $TLPportfolio->options['settings'] );
			$width    = isset( $settings['feature_img']['width'] ) ? ( $settings['feature_img']['width'] ? (int) $settings['feature_img']['width'] : 350 ) : 350;
			$height   = isset( $settings['feature_img']['height'] ) ? ( $settings['feature_img']['height'] ? (int) $settings['feature_img']['height'] : 250 ) : 250;
			add_image_size( $TLPportfolio->options['tlp-portfolio-thumb'], $width, $height, true );
		}


		function tlpPortSettings() {
			global $TLPportfolio;
			$error = true;
			if ( $TLPportfolio->verifyNonce() ) {
				unset( $_REQUEST['action'] );
				unset( $_REQUEST['tlp_nonce'] );
				unset( $_REQUEST['_wp_http_referer'] );
				update_option( $TLPportfolio->options['settings'], $_REQUEST );
				flush_rewrite_rules();
				$response = array(
					'error' => $error,
					'msg'   => __( 'Settings successfully updated', 'tlp-portfolio' )
				);
			} else {
				$response = array(
					'error' => true,
					'msg'   => __( 'Security Error!!!', 'tlp-portfolio' )
				);
			}
			wp_send_json( $response );
			die();
		}


		/**
		 *  TLP menu register
		 */
		function tlp_menu_register() {
			$sc   = add_submenu_page( 'edit.php?post_type=portfolio', __( 'Shortcode generator', 'tlp-portfolio' ),
				__( 'ShortCode', 'tlp-portfolio' ), 'administrator', 'tlp_portfolio_sc',
				array( $this, 'tlp_portfolio_sc' ) );
			$page = add_submenu_page( 'edit.php?post_type=portfolio', __( 'TLP Portfolio Settings', 'tlp-portfolio' ),
				__( 'Settings', 'tlp-portfolio' ), 'administrator', 'tlp_portfolio_settings',
				array( $this, 'tlp_portfolio_settings' ) );

			add_action( 'admin_print_styles-' . $page, array( $this, 'tlp_style' ) );
			add_action( 'admin_print_scripts-' . $page, array( $this, 'tlp_script' ) );
			add_action( 'admin_print_styles-' . $sc, array( $this, 'tlp_style' ) );
			add_action( 'admin_print_scripts-' . $sc, array( $this, 'tlp_script' ) );
		}

		/**
		 *  Portfolio style
		 */
		function tlp_style() {
			global $TLPportfolio;
			wp_enqueue_style( 'tlpport-setting-css', $TLPportfolio->assetsUrl . 'css/settings.css' );
		}

		function tlp_script() {
			global $TLPportfolio;
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'tlpport-setting-js', $TLPportfolio->assetsUrl . 'js/settings.js',
				array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker' ), '', true );
			$nonce = wp_create_nonce( $TLPportfolio->nonceText() );
			wp_localize_script( 'tlpport-setting-js', 'tpl_port_var', array( 'tlp_nonce' => $nonce ) );
		}

		/**
		 *  Render settings view
		 */
		function tlp_portfolio_settings() {
			global $TLPportfolio;
			$TLPportfolio->render( 'settings' );
		}

		function tlp_portfolio_sc() {
			global $TLPportfolio;
			$TLPportfolio->render( 'sc' );
		}

		public function tlp_portfolio_load_text_domain() {

			load_plugin_textdomain( 'tlp-portfolio', false, TLP_PORTFOLIO_LANGUAGE_PATH );

		}

	}
endif;
