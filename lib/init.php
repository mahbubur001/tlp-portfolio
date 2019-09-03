<?php
if ( ! class_exists( 'TLPportfolio' ) ):


	class TLPportfolio {

		public $options;
		public $post_type_slug;
		private $sc_post_type;
		public $post_type;
		public $assetsUrl;

		protected static $_instance;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}


		function __construct() {
			$this->options = array(
				'settings'            => 'tpl_portfolio_settings',
				'version'             => TLP_PORTFOLIO_VERSION,
				'tlp-portfolio-thumb' => 'tlp-portfolio-thumb',
				'installed_version'   => 'tlp_portfolio_installed_version'
			);

			$this->post_type      = 'portfolio';
			$settings             = get_option( $this->options['settings'] );
			$this->post_type_slug = isset( $settings['slug'] ) ? ( $settings['slug'] ? sanitize_title_with_dashes( $settings['slug'] ) : 'portfolio' ) : 'portfolio';
			$this->taxonomies     = array(
				'category' => $this->post_type . "-category",
				'tag'      => $this->post_type . "-tag",
			);
			$this->incPath        = dirname( __FILE__ );
			$this->functionsPath  = $this->incPath . '/functions/';
			$this->classesPath    = $this->incPath . '/classes/';
			$this->modelsPath     = $this->incPath . '/models/';
			$this->widgetsPath    = $this->incPath . '/widgets/';
			$this->viewsPath      = $this->incPath . '/views/';
			$this->assetsUrl      = TLP_PORTFOLIO_PLUGIN_URL . '/assets/';
			$this->templatePath   = $this->incPath . '/template/';

			$this->TLPLoadModel( $this->modelsPath );
			$this->TPLloadClass( $this->classesPath );
			$this->defaultSettings = array(
				'primary_color'    => '#0367bf',
				'feature_img_size' => 'medium',
				'slug'             => 'portfolio',
				'link_detail_page' => 'yes',
				'custom_css'       => null
			);


			register_activation_hook( TLP_PORTFOLIO_PLUGIN_ACTIVE_FILE_NAME, array( $this, 'activate' ) );
			register_deactivation_hook( TLP_PORTFOLIO_PLUGIN_ACTIVE_FILE_NAME, array( $this, 'deactivate' ) );
		}

		public function activate() {
			flush_rewrite_rules();
			$this->insertDefaultData();
		}

		public function deactivate() {
			flush_rewrite_rules();
		}

		function TPLloadClass( $dir ) {
			if ( ! file_exists( $dir ) ) {
				return;
			}

			$classes = array();

			foreach ( scandir( $dir ) as $item ) {
				if ( preg_match( "/.php$/i", $item ) ) {
					require_once( $dir . $item );
					$className = str_replace( ".php", "", $item );
					$classes[] = new $className;
				}
			}

			if ( $classes ) {
				foreach ( $classes as $class ) {
					$this->objects[] = $class;
				}
			}
		}

		function TLPLoadModel( $dir ) {
			if ( ! file_exists( $dir ) ) {
				return;
			}
			foreach ( scandir( $dir ) as $item ) {
				if ( preg_match( "/.php$/i", $item ) ) {
					require_once( $dir . $item );
				}
			}
		}

		function loadWidget( $dir ) {
			if ( ! file_exists( $dir ) ) {
				return;
			}
			foreach ( scandir( $dir ) as $item ) {
				if ( preg_match( "/.php$/i", $item ) ) {
					require_once( $dir . $item );
					$class = str_replace( ".php", "", $item );

					if ( method_exists( $class, 'register_widget' ) ) {
						$caller = new $class;
						$caller->register_widget();
					} else {
						register_widget( $class );
					}
				}
			}
		}

		function render( $viewName, $args = array() ) {
			global $TLPportfolio;

			$viewPath = $TLPportfolio->viewsPath . $viewName . '.php';
			if ( ! file_exists( $viewPath ) ) {
				return;
			}

			if ( $args ) {
				extract( $args );
			}
			$pageReturn = include $viewPath;
			if ( $pageReturn AND $pageReturn <> 1 ) {
				return $pageReturn;
			}
		}

		/**
		 * Dynamicaly call any  method from models class
		 * by pluginFramework instance
		 */
		function __call( $name, $args ) {
			if ( ! is_array( $this->objects ) ) {
				return;
			}
			foreach ( $this->objects as $object ) {
				if ( method_exists( $object, $name ) ) {
					$count = count( $args );
					if ( $count == 0 ) {
						return $object->$name();
					} elseif ( $count == 1 ) {
						return $object->$name( $args[0] );
					} elseif ( $count == 2 ) {
						return $object->$name( $args[0], $args[1] );
					} elseif ( $count == 3 ) {
						return $object->$name( $args[0], $args[1], $args[2] );
					} elseif ( $count == 4 ) {
						return $object->$name( $args[0], $args[1], $args[2], $args[3] );
					} elseif ( $count == 5 ) {
						return $object->$name( $args[0], $args[1], $args[2], $args[3], $args[4] );
					} elseif ( $count == 6 ) {
						return $object->$name( $args[0], $args[1], $args[2], $args[3], $args[4], $args[5] );
					}
				}
			}
		}

		private function insertDefaultData() {
			global $TLPportfolio;
			update_option( $TLPportfolio->options['installed_version'], $TLPportfolio->options['version'] );
			if ( ! get_option( $TLPportfolio->options['settings'] ) ) {
				update_option( $TLPportfolio->options['settings'], $TLPportfolio->defaultSettings );
			}
		}
	}

endif;

global $TLPportfolio;
if ( ! is_object( $TLPportfolio ) ) {
	$TLPportfolio = new TLPportfolio;
}