<?php
if (!defined('WPINC')) {
    die;
}

if (!class_exists( 'TLPPortfolioGutenBerg' )):

    class TLPPortfolioGutenBerg
    {
        protected $version;

        function __construct() {
            $this->version = (defined('WP_DEBUG') && WP_DEBUG) ? time() : TLP_PORTFOLIO_VERSION;
            add_action('enqueue_block_assets', array($this, 'block_assets'));
            add_action('enqueue_block_editor_assets', array($this, 'block_editor_assets'));
            if (function_exists('register_block_type')) {
                register_block_type('radiustheme/tlp-portfolio', array(
                    'render_callback' => array($this, 'render_shortcode'),
                ));

                register_block_type('rt-portfolio/tlp-portfolio-pro', array(
                    'render_callback' => array($this, 'render_shortcode_pro'),
                ));
            }
        }

        static function render_shortcode($atts) {

            $shortcode = '[tlpportfolio';
	        if (isset($atts['layout']) && !empty($atts['layout']) && $atts['layout']) {
		        $shortcode .= ' layout="' . $atts['layout'] . '"';
	        }
	        if (isset($atts['column']) && !empty($atts['column']) && $atts['column']) {
		        $shortcode .= ' col="' . $atts['column'] . '"';
	        }
	        if (isset($atts['orderby']) && !empty($atts['orderby'])) {
		        $shortcode .= ' orderby="' . $atts['orderby'] . '"';
	        }
	        if (isset($atts['order']) && !empty($atts['order'])) {
		        $shortcode .= ' order="' . $atts['order'] . '"';
	        }
	        if (isset($atts['number']) && !empty($atts['number']) && $atts['number']) {
		        $shortcode .= ' number="' . absint($atts['number']) . '"';
	        }
	        if (isset($atts['cats']) && !empty($atts['cats']) && is_array($atts['cats'])) {
                $cats = array_filter($atts['cats']);
		        if(!empty($cats)){
			        $shortcode .= ' cat="' . implode(',',$cats) . '"';
		        }
	        }
	        if (isset($atts['isImageHide']) && !empty($atts['isImageHide'])) {
		        $shortcode .= ' image="false"';
	        }
	        if (isset($atts['letterLimit']) && !empty($atts['letterLimit']) && $limit = absint($atts['letterLimit'])) {
		        $shortcode .= ' letter-limit="' . $limit . '"';
	        }
	        if (isset($atts['titleColor']) && !empty($atts['titleColor'])) {
		        $shortcode .= ' title-color="' . $atts['titleColor'] . '"';
	        }
	        if (isset($atts['titleFontSize']) && !empty($atts['titleFontSize'])) {
		        $shortcode .= ' title-font-size="' . $atts['titleFontSize'] . '"';
	        }
	        if (isset($atts['titleFontWeight']) && !empty($atts['titleFontWeight'])) {
		        $shortcode .= ' title-font-weight="' . $atts['titleFontWeight'] . '"';
	        }
	        if (isset($atts['titleAlignment']) && !empty($atts['titleAlignment'])) {
		        $shortcode .= ' title-alignment="' . $atts['titleAlignment'] . '"';
	        }
	        if (isset($atts['sdColor']) && !empty($atts['sdColor'])) {
		        $shortcode .= ' short-desc-color="' . $atts['sdColor'] . '"';
	        }
	        if (isset($atts['sdFontSize']) && !empty($atts['sdFontSize'])) {
		        $shortcode .= ' short-desc-font-size="' . $atts['sdFontSize'] . '"';
	        }
	        if (isset($atts['sdFontWeight']) && !empty($atts['sdFontWeight'])) {
		        $shortcode .= ' short-desc-font-weight="' . $atts['sdFontWeight'] . '"';
	        }
	        if (isset($atts['sdAlignment']) && !empty($atts['sdAlignment'])) {
		        $shortcode .= ' short-desc-alignment="' . $atts['sdAlignment'] . '"';
	        }
	        if (isset($atts['wrapperClass']) && !empty($atts['wrapperClass'])) {
		        $shortcode .= ' class="' . $atts['wrapperClass'] . '"';
	        }
            $shortcode .= ']';
            return do_shortcode($shortcode);
        }

	    static function render_shortcode_pro( $atts ){
		    if(!empty($atts['gridId']) && $id = absint($atts['gridId'])){
			    return do_shortcode( '[tlpportfolio id="' . $id . '"]' );
		    }
	    }


        function block_assets() {
            wp_enqueue_style('wp-blocks');
        }

        function block_editor_assets() {
            // Scripts.
            wp_enqueue_script(
                'rt-tlp-portfolio-gb-block-js',
                TLPportfolio()->assetsUrl . "js/tlp-portfolio-blocks.min.js",
                array('wp-blocks', 'wp-i18n', 'wp-element'),
                $this->version,
                true
            );
            wp_localize_script('rt-tlp-portfolio-gb-block-js', 'rtPortfolio', array(
                'layout'      => TLPportfolio()->oldScLayouts(),
                'column'      => TLPportfolio()->scColumns(),
                'orderby'     => TLPportfolio()->scOrderBy(),
                'order'       => TLPportfolio()->scOrder(),
                'alignments'  => TLPportfolio()->scAlignment(),
                'fontWeights' => TLPportfolio()->scTextWeight(),
                'fontSizes'   => TLPportfolio()->scFontSize(),
                'cats'        => TLPportfolio()->getAllPortFolioCategoryList(),
	            'short_codes' => TLPportfolio()->get_shortCode_list(),
                'icon'        => TLPportfolio()->assetsUrl . 'images/portfolio.png',
            ));
            wp_enqueue_style('wp-edit-blocks');
        }
    }

endif;