<?php
if(!class_exists('TPLportFrontEnd')):

    class TPLportFrontEnd
    {
        function __construct(){
            add_action( 'wp_head', array($this, 'custom_css') );
            add_action( 'wp_enqueue_scripts', array( $this, 'tlp_portfolio_wp_enqueue_scripts' ));
        }

        function custom_css(){
            global $TLPportfolio;
            $html = null;
            $settings = get_option($TLPportfolio->options['settings']);
            $pc = (isset($settings['primary_color']) ? ($settings['primary_color'] ? $settings['primary_color'] : '#0367bf' ) : '#0367bf');
            $cCss = (isset($settings['custom_css']) ? ($settings['custom_css'] ? $settings['custom_css'] : null) : null );
            if($cCss || $pc) {
                $html .= "<style type='text/css'>";
                $html .= '.tlp-team .short-desc, .tlp-team .tlp-team-isotope .tlp-content, .tlp-team .button-group .selected, .tlp-team .layout1 .tlp-content, .tlp-team .tpl-social a, .tlp-team .tpl-social li a.fa,.tlp-portfolio button.selected,.tlp-portfolio .layoutisotope .tlp-portfolio-item .tlp-content,.tlp-portfolio button:hover {';
                $html .= 'background: ' . $pc;
                $html .= '}';
                $html .= '.tlp-portfolio .layoutisotope .tlp-overlay,.tlp-portfolio .layout1 .tlp-overlay,.tlp-portfolio .layout2 .tlp-overlay,.tlp-portfolio .layout3 .tlp-overlay, .tlp-portfolio .slider .tlp-overlay {';
                $html .= "background:" . $TLPportfolio->TLPhex2rgba($pc, .8).";";
                $html .= '}';
                $html .= $cCss;
            }
            $html .= "</style>";
            echo $html;
        }

        function tlp_portfolio_wp_enqueue_scripts(){
            global $TLPportfolio;
            wp_enqueue_style( 'tlpportfolio-css', $TLPportfolio->assetsUrl . 'css/tlpportfolio.css' );
        }

    }
endif;