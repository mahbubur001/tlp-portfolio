<?php

if(!class_exists('PortfolioPostTypeRegistrations')):

	class PortfolioPostTypeRegistrations {

		function __construct() {
			// Add the team post type and taxonomies
			add_action( 'init', array( $this, 'register' ) );
		}
		/**
		 * Initiate registrations of post type and taxonomies.
		 *
		 * @uses Portfolio_Post_Type_Registrations::register_post_type()
		 * @uses Portfolio_Post_Type_Registrations::register_taxonomy_category()
		 * @uses Portfolio_Post_Type_Registrations::register_taxonomy_tag()
		 */
		function register() {
			$this->register_post_type();
			$this->register_taxonomy_category();
			$this->register_taxonomy_tag();
		}
		/**
		 * Register the custom post type.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		protected function register_post_type() {
			global $TLPportfolio;
			$labels = array(
				'name'               => __( 'Portfolio', 'tlp-portfolio' ),
				'singular_name'      => __( 'Portfolio', 'tlp-portfolio' ),
				'add_new'            => __( 'Add Portfolio', 'tlp-portfolio' ),
				'all_items'          => __( 'All Portfolios', 'tlp-portfolio'),
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
			$args = array(
				'labels'          => $labels,
				'supports'        => $supports,
				'hierarchical'        => false,
				'public'              => true,
				'rewrite'			  => array('slug' => $TLPportfolio->post_type_slug),
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 20,
				'menu_icon'			=> $TLPportfolio->assetsUrl .'images/portfolio.png',
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
		 *
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
			$TagArgs = array(
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
		 *
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
			$args = array(
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

	}

endif;
