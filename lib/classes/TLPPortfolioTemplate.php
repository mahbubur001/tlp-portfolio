<?php

if(!class_exists('TLPPortfolioTemplate')):

    /**
     *
     */
    class TLPPortfolioTemplate
    {

        function __construct()
        {
            add_filter( 'template_include', array( $this, 'template_loader' ) );
        }

        public static function template_loader( $template ) {
            $find = array();
            $file = null;
            global $TLPportfolio;
            if ( is_single() && get_post_type() == $TLPportfolio->post_type ) {

                $file 	= 'single-portfolio.php';
                $find[] = $file;
                $find[] = $TLPportfolio->templatePath . $file;

            }

            if ( @$file ) {

                $template = locate_template( array_unique( $find ) );
                if ( ! $template ) {
                    $template = $TLPportfolio->templatePath  . $file;
                }
            }

            return $template;
        }

        public function load_template_script(){
            global $TLPportfolio;
            if(get_post_type() == $TLPportfolio->post_type || is_post_type_archive($TLPportfolio->post_type)){
                wp_enqueue_style( 'tlpportfolio-fontawsome', $TLPportfolio->assetsUrl . 'vendor/font-awesome/css/font-awesome.min.css' );
            }
        }


    }

endif;
