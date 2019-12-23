<?php

if ( ! class_exists( 'PortfolioInitRegister' ) ):

	class PortfolioInitRegister {

		function __construct() {
			// Add the team post type and taxonomies
			add_action( 'init', array( $this, 'register' ) );
		}

		/**
		 * Initiate registrations of post type and taxonomies.
		 * @uses Portfolio_Post_Type_Registrations::register_post_type()
		 * @uses Portfolio_Post_Type_Registrations::register_taxonomy_category()
		 * @uses Portfolio_Post_Type_Registrations::register_taxonomy_tag()
		 */
		function register() {
			$this->register_post_type();
			$this->register_taxonomy_category();
			$this->register_taxonomy_tag();
			$this->register_scPT();
			$this->registerScriptStyle();
		}

		/**
		 * Register the custom post type.
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		protected function register_post_type() {
			global $TLPportfolio;
			$labels   = array(
				'name'               => __( 'Portfolio', 'tlp-portfolio' ),
				'singular_name'      => __( 'Portfolio', 'tlp-portfolio' ),
				'add_new'            => __( 'Add Portfolio', 'tlp-portfolio' ),
				'all_items'          => __( 'All Portfolios', 'tlp-portfolio' ),
				'add_new_item'       => __( 'Add Portfolio', 'tlp-portfolio' ),
				'edit_item'          => __( 'Edit Portfolio', 'tlp-portfolio' ),
				'new_item'           => __( 'New Portfolio', 'tlp-portfolio' ),
				'view_item'          => __( 'View Portfolio', 'tlp-portfolio' ),
				'search_items'       => __( 'Search Portfolio', 'tlp-portfolio' ),
				'not_found'          => __( 'No Portfolios found', 'tlp-portfolio' ),
				'not_found_in_trash' => __( 'No Portfolios in the trash', 'tlp-portfolio' ),
			);
			$supports = array(
				'title',
				'editor',
				'thumbnail',
				'page-attributes'
			);
			$args     = array(
				'labels'              => $labels,
				'supports'            => $supports,
				'hierarchical'        => false,
				'public'              => true,
				'rewrite'             => array( 'slug' => $TLPportfolio->post_type_slug ),
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 20,
				'menu_icon'           => $TLPportfolio->assetsUrl . 'images/portfolio.png',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);

			register_post_type( $TLPportfolio->post_type, $args );
			flush_rewrite_rules();
		}

		/**
		 * Register a taxonomy for Portfolio Tags.
		 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
		 */
		protected function register_taxonomy_tag() {
			global $TLPportfolio;
			$TagLabels = array(
				'name'                       => __( 'Tags', 'tlp-portfolio' ),
				'singular_name'              => __( 'Tag', 'tlp-portfolio' ),
				'menu_name'                  => __( 'Tags', 'tlp-portfolio' ),
				'edit_item'                  => __( 'Edit Tag', 'tlp-portfolio' ),
				'update_item'                => __( 'Update Tag', 'tlp-portfolio' ),
				'add_new_item'               => __( 'Add New Tag', 'tlp-portfolio' ),
				'new_item_name'              => __( 'New Tag Name', 'tlp-portfolio' ),
				'parent_item'                => __( 'Parent Tag', 'tlp-portfolio' ),
				'parent_item_colon'          => __( 'Parent Tag', 'tlp-portfolio' ),
				'all_items'                  => __( 'All Tags', 'tlp-portfolio' ),
				'search_items'               => __( 'Search Tags', 'tlp-portfolio' ),
				'popular_items'              => __( 'Popular Tags', 'tlp-portfolio' ),
				'separate_items_with_commas' => __( 'Separate Tags with commas', 'tlp-portfolio' ),
				'add_or_remove_items'        => __( 'Add or remove Tags', 'tlp-portfolio' ),
				'choose_from_most_used'      => __( 'Choose from the most used Tags', 'tlp-portfolio' ),
				'not_found'                  => __( 'No Tags found.', 'tlp-portfolio' ),
			);
			$TagArgs   = array(
				'labels'            => $TagLabels,
				'public'            => true,
				'show_in_nav_menus' => true,
				'show_ui'           => true,
				'show_tagcloud'     => true,
				'hierarchical'      => false,
				'show_admin_column' => true,
				'query_var'         => true,
			);

			register_taxonomy( $TLPportfolio->taxonomies['tag'], $TLPportfolio->post_type, $TagArgs );
			flush_rewrite_rules();
		}

		/**
		 * Register a taxonomy for Team Categories.
		 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
		 */
		protected function register_taxonomy_category() {
			$labels = array(
				'name'                       => __( 'Categories', 'tlp-portfolio' ),
				'singular_name'              => __( 'Category', 'tlp-portfolio' ),
				'menu_name'                  => __( 'Categories', 'tlp-portfolio' ),
				'edit_item'                  => __( 'Edit Category', 'tlp-portfolio' ),
				'update_item'                => __( 'Update Category', 'tlp-portfolio' ),
				'add_new_item'               => __( 'Add New Category', 'tlp-portfolio' ),
				'new_item_name'              => __( 'New Category Name', 'tlp-portfolio' ),
				'parent_item'                => __( 'Parent Category', 'tlp-portfolio' ),
				'parent_item_colon'          => __( 'Parent Category:', 'tlp-portfolio' ),
				'all_items'                  => __( 'All Categories', 'tlp-portfolio' ),
				'search_items'               => __( 'Search Categories', 'tlp-portfolio' ),
				'popular_items'              => __( 'Popular Categories', 'tlp-portfolio' ),
				'separate_items_with_commas' => __( 'Separate categories with commas', 'tlp-portfolio' ),
				'add_or_remove_items'        => __( 'Add or remove categories', 'tlp-portfolio' ),
				'choose_from_most_used'      => __( 'Choose from the most used  categories', 'tlp-portfolio' ),
				'not_found'                  => __( 'No categories found.', 'tlp-portfolio' ),
			);
			$args   = array(
				'labels'            => $labels,
				'public'            => true,
				'show_in_nav_menus' => true,
				'show_ui'           => true,
				'show_tagcloud'     => true,
				'hierarchical'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
			);
			global $TLPportfolio;
			register_taxonomy( $TLPportfolio->taxonomies['category'], $TLPportfolio->post_type, $args );
			flush_rewrite_rules();
		}

		protected function register_scPT() {

			$sc_args = array(
				'label'               => __( 'ShortCode', 'tlp-portfolio' ),
				'description'         => __( 'TLP Portfolio ShortCode generator', 'tlp-portfolio' ),
				'labels'              => array(
					'all_items'          => __( 'ShortCodes', 'tlp-portfolio' ),
					'menu_name'          => __( 'ShortCode', 'tlp-portfolio' ),
					'singular_name'      => __( 'ShortCode', 'tlp-portfolio' ),
					'edit_item'          => __( 'Edit ShortCode', 'tlp-portfolio' ),
					'new_item'           => __( 'New ShortCode', 'tlp-portfolio' ),
					'view_item'          => __( 'View ShortCode', 'tlp-portfolio' ),
					'search_items'       => __( 'ShortCode Locations', 'tlp-portfolio' ),
					'not_found'          => __( 'No ShortCode found.', 'tlp-portfolio' ),
					'not_found_in_trash' => __( 'No ShortCode found in trash.', 'tlp-portfolio' )
				),
				'supports'            => array( 'title' ),
				'public'              => false,
				'rewrite'             => false,
				'show_ui'             => true,
				'show_in_menu'        => 'edit.php?post_type=' . TLPPortfolio()->post_type,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
			);
			register_post_type( TLPPortfolio()->getScPostType(), apply_filters( 'tlp-portfolio-register-sc-args', $sc_args ) );
		}

		private function registerScriptStyle() {

			// register team scripts and styles
			$scripts = array();
			$styles = array();
			$version = defined('WP_DEBUG') && WP_DEBUG ? time() : TLP_PORTFOLIO_VERSION;

			$scripts['tlp-magnific'] = array(
				'src'    => TLPPortfolio()->assetsUrl . "vendor/jquery.magnific-popup.min.js",
				'deps'   => array('jquery'),
				'footer' => true
			);
			$scripts['tlp-owl-carousel'] = array(
				'src'    => TLPPortfolio()->assetsUrl . "vendor/owl-carousel/owl.carousel.min.js",
				'deps'   => array('jquery', 'imagesloaded'),
				'footer' => true
			);
			$scripts['tlp-isotope'] = array(
				'src'    => TLPPortfolio()->assetsUrl . "vendor/isotope/isotope.pkgd.min.js",
				'deps'   => array('jquery', 'imagesloaded'),
				'footer' => true
			);
			$scripts['tlp-team-block'] = array(
				'src'    => TLPPortfolio()->assetsUrl . "js/tlp-team-blocks.min.js",
				'deps'   => array('jquery'),
				'footer' => true
			);
			$scripts['tlp-portfolio'] = array(
				'src'    => TLPPortfolio()->assetsUrl . "js/tlpportfolio.js",
				'deps'   => array('jquery'),
				'footer' => true
			);
			// register acf styles
			$styles['tlp-fontawsome'] = TLPPortfolio()->assetsUrl . 'vendor/font-awesome/css/font-awesome.min.css';
			$styles['tlp-owl-carousel'] = TLPPortfolio()->assetsUrl . 'vendor/owl-carousel/owl.carousel.min.css';
			$styles['tlp-owl-carousel-theme'] = TLPPortfolio()->assetsUrl . 'vendor/owl-carousel/owl.theme.default.min.css';
			$styles['tlp-portfolio'] = TLPPortfolio()->assetsUrl . 'css/tlpportfolio.css';

			if (is_admin()) {
				$scripts['tlp-select2'] = array(
					'src'    => TLPPortfolio()->assetsUrl . "vendor/select2/select2.min.js",
					'deps'   => array('jquery'),
					'footer' => false
				);

				$scripts['tlp-portfolio-admin'] = array(
					'src'    => TLPPortfolio()->assetsUrl . "js/settings.js",
					'deps'   => array('jquery'),
					'footer' => true
				);

				$scripts['wp-color-picker-alpha'] = array(
					'src'    => TLPPortfolio()->assetsUrl . "js/wp-color-picker-alpha.js",
					'deps'   => array('wp-color-picker'),
					'footer' => true
				);
				$styles['tlp-select2'] = TLPPortfolio()->assetsUrl . 'vendor/select2/select2.min.css';
				$styles['tlp-portfolio-admin'] = TLPPortfolio()->assetsUrl . 'css/settings.css';
			}
			foreach ($scripts as $handle => $script) {
				wp_register_script($handle, $script['src'], $script['deps'], $version, $script['footer']);
			}
			foreach ($styles as $k => $v) {
				wp_register_style($k, $v, false, $version);
			}
            wp_localize_script('tlp-portfolio-admin', 'tlp_portfolio_obj', array(
                'ajaxurl' => admin_url('admin-ajax.php '),
                'nonce'   => wp_create_nonce(TLPPortfolio()->nonceText()),
                'nonceId' => TLPPortfolio()->nonceId()
            ));
		}

	}

endif;
