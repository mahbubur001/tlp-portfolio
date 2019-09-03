<?php

if (!class_exists('TLPportShortCode')):

    /**
     *
     */
    class TLPportShortCode
    {

        function __construct() {
            add_shortcode('tlpportfolio', array($this, 'portfolio_shortcode'));
        }

        function portfolio_shortcode($atts, $content = "") {
            global $TLPportfolio;
            wp_enqueue_style('tlpportfolio-fontawsome',
                $TLPportfolio->assetsUrl . 'vendor/font-awesome/css/font-awesome.min.css');
            wp_enqueue_script('tlpportfolio-magnific',
                $TLPportfolio->assetsUrl . 'vendor/jquery.magnific-popup.min.js', array('jquery'), null, true);
            wp_enqueue_script('tlpportfolio-isotope-image',
                $TLPportfolio->assetsUrl . 'vendor/isotope/imagesloaded.pkgd.min.js', array('jquery'), null, true);
            wp_enqueue_script('tlpportfolio-isotope', $TLPportfolio->assetsUrl . 'vendor/isotope/isotope.pkgd.min.js',
                array(
                    'jquery',
                    'tlpportfolio-isotope-image'
                ), null, true);
            wp_enqueue_script('tlpportfolio-js', $TLPportfolio->assetsUrl . 'js/tlpportfolio.js', array('jquery'),
                null, true);

            $atts = shortcode_atts(array(
                'orderby'                => 'date',
                'order'                  => 'DESC',
                'image'                  => 'true',
                'number'                 => -1,
                'col'                    => 3,
                'layout'                 => 1,
                'letter-limit'           => 0,
                'cat'                    => null,
                'title-color'            => null,
                'title-font-size'        => null,
                'title-font-weight'      => null,
                'title-alignment'        => null,
                'short-desc-color'       => null,
                'short-desc-font-size'   => null,
                'short-desc-font-weight' => null,
                'short-desc-alignment'   => null,
                'class'                  => null
            ), $atts, 'tlpportfolio');

            $atts['image'] = 'true' === $atts['image'];
            $limit = $atts['letter-limit'] ? absint($atts['letter-limit']) : 0;
            if (!in_array($atts['col'], array_keys($TLPportfolio->scColumns()))) {
                $atts['col'] = 3;
            }
            if (!in_array($atts['layout'], array_keys($TLPportfolio->scLayouts()))) {
                $atts['layout'] = 1;
            }
            $grid = $atts['col'] == 5 ? '24' : 12 / $atts['col'];
            if ($atts['col'] == 2) {
                $image_area = "tlp-col-lg-5 tlp-col-md-5 tlp-col-sm-6 tlp-col-xs-12 ";
                $content_area = "tlp-col-lg-7 tlp-col-md-7 tlp-col-sm-6 tlp-col-xs-12 ";
            } else {
                $image_area = "tlp-col-lg-3 tlp-col-md-3 tlp-col-sm-6 tlp-col-xs-12 ";
                $content_area = "tlp-col-lg-9 tlp-col-md-9 tlp-col-sm-6 tlp-col-xs-12 ";
            }

            $html = null;
            $rand = rand(1, 10);
            $args = array(
                'post_type'      => $TLPportfolio->post_type,
                'post_status'    => 'publish',
                'posts_per_page' => $atts['number'],
                'orderby'        => $atts['orderby'],
                'order'          => $atts['order']
            );
            if (is_user_logged_in() && is_super_admin()) {
                $args['post_status'] = array('publish', 'private');
            }
            $cat_ids = array();
            if (!empty($atts['cat'])) {
                $cat_ids = explode(",", $atts['cat']);
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $TLPportfolio->taxonomies['category'],
                        'field'    => 'term_id',
                        'terms'    => $cat_ids,
                        'operator' => 'IN'
                    ),
                );
            }
            $settings = get_option($TLPportfolio->options['settings']);
            $fImgSize = !empty($settings['feature_img_size']) ? $settings['feature_img_size'] : $TLPportfolio->options['tlp-portfolio-thumb'];
            $customImgSize = !empty($settings['rt_custom_img_size']) ? $settings['rt_custom_img_size'] : array();

            $teamQuery = new WP_Query($args);
            $layoutID = "tlp-portfolio-container-" . mt_rand();
            $class = array(
                'rt-container-fluid',
                'tlp-portfolio'
            );
            if (!empty($atts['class'])) {
                $class[] = $atts['class'];
            }
            $class = implode(' ', $class);
            $html .= "<div class='" . esc_attr($class) . "'  id='{$layoutID}'>";
            $html .= $this->customStyle($layoutID, $atts);
            $html .= '<div class="row tlp-layout-' . $atts['layout'] . '">';
            if ($teamQuery->have_posts()) {
                if ($atts['layout'] == 'isotope') {
                    $terms = get_terms($TLPportfolio->taxonomies['category'], array(
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'hide_empty' => false,
                    ));
                    $html .= '<div id="tlp-portfolio-isotope-button" class="button-group filter-button-group option-set">
											<button data-filter="*" class="selected">' . __("Show all",
                            "tlp-portfolio") . '</button>';
                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            if (!empty($cat_ids)) {
                                if (in_array($term->term_id, $cat_ids)) {
                                    $html .= "<button data-filter='.{$term->slug}'>" . $term->name . "</button>";
                                }
                            } else {
                                $html .= "<button data-filter='.{$term->slug}'>" . $term->name . "</button>";
                            }
                        }
                    }
                    $html .= '</div>';
                    $html .= '<div class="tlp-portfolio-isotope">';
                }

                while ($teamQuery->have_posts()) : $teamQuery->the_post();

                    $title = get_the_title();
                    $iID = get_the_ID();
                    $plink = get_permalink();
                    $short_d = get_post_meta($iID, 'short_description', true);
                    $project_url = get_post_meta($iID, 'project_url', true);
                    $tools = get_post_meta($iID, 'tools', true);
                    $categories = get_the_term_list($iID, $TLPportfolio->taxonomies['category'], 'Category : ', ',');
                    $tags = get_the_term_list($iID, $TLPportfolio->taxonomies['tag'], 'Tags : ', ',');

                    $catClass = null;
                    $catAs = wp_get_post_terms($iID, $TLPportfolio->taxonomies['category'],
                        array("fields" => "all"));
                    $deptClass = null;
                    if (!empty($catAs)) {
                        foreach ($catAs as $cat) {
                            $catClass .= " " . $cat->slug;
                        }
                    }
                    $img = null;
                    $imgFull = null;
                    if (has_post_thumbnail()) {
                        $img = $TLPportfolio->getFeatureImageSrc($iID, $fImgSize, $customImgSize);;
                        $imageFull = wp_get_attachment_image_src(get_post_thumbnail_id($iID), 'full');
                        $imgFull = $imageFull[0];
                    } else {
                        $img = $TLPportfolio->assetsUrl . 'images/demo.jpg';
                    }
                    if (!$imgFull) {
                        $imgFull = $img;
                    }
                    if (!$atts['image']) {
                        $content_area = "tlp-col-md-12";
                        $img = null;
                    }
                    $itemArg = array();
                    $itemArg['title'] = $title;
                    $itemArg['plink'] = $project_url ? $project_url : $plink;
                    $itemArg['img'] = $img;
                    $itemArg['imgFull'] = $imgFull;
                    $itemArg['short_d'] = $short_d;
                    $itemArg['grid'] = $grid;
                    $itemArg['rand'] = $rand;
                    $itemArg['catClass'] = $catClass;
                    $itemArg['limit'] = $limit;
                    $itemArg['image_area'] = $image_area;
                    $itemArg['content_area'] = $content_area;
                    if ($atts['layout']) {
                        switch ($atts['layout']) {
                            case 1:
                                $html .= $this->templateOne($itemArg);
                                break;

                            case 2:
                                $html .= $this->templateTwo($itemArg);
                                break;

                            case 3:
                                $html .= $this->templateThree($itemArg);
                                break;

                            case 'isotope':
                                $html .= $this->layoutIsotope($itemArg);
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                endwhile;
                wp_reset_postdata();
                if ($atts['layout'] == 'isotope') {
                    $html .= '</div>'; // end tlp-team-isotope
                }
            } else {
                $html .= "<p>No portfolio found</p>";
            }
            $html .= '</div>'; // end row
            $html .= '</div>'; // end container

            return $html;
        }


        function templateOne($itemArg) {
            extract($itemArg);
            $html = null;
            $html .= "<div class='tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 tlp-equal-height'>";
            $html .= '<div class="tlp-portfolio-item">';
            if ($img) {
                $html .= '<div class="tlp-portfolio-thum tlp-item">';
                $html .= '<img class="img-responsive" src="' . $img . '" alt="' . $title . '"/>';
                $html .= '<div class="tlp-overlay">';
                $html .= '<p class="link-icon">';
                $html .= '<a class="tlp-zoom" href="' . $imgFull . '"><i class="fa fa-search-plus"></i></a>';
                $html .= '<a target="_blank" href="' . $plink . '"><i class="fa fa-external-link"></i></a>';
                $html .= '</p>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= sprintf('<div class="tlp-content"><div class="tlp-content-holder"><h3><a href="%s">%s</a></h3><div class="tlp-portfolio-sd">%s</div></div></div>',
                $plink, $title,
                $limit > 0 ? substr($short_d, 0, $limit) : wp_kses_post(html_entity_decode($short_d)) //
            );
            $html .= '</div>';
            $html .= '</div>';

            return $html;
        }

        function templateTwo($itemArg) {
            extract($itemArg);
            $html = null;
            $html .= "<div class='tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 tlp-equal-height'>";
            $html .= '<div class="tlp-portfolio-item">';
            if ($img) {
                $html .= '<div class="tlp-portfolio-thum tlp-item ' . $image_area . '">';
                $html .= '<figure>';
                $html .= '<img class="img-responsive" src="' . $img . '" alt="' . $title . '"/>';
                $html .= '</figure>';
                $html .= '<div class="tlp-overlay">';
                $html .= '<ul class="link-icon">';
                $html .= '<a class="tlp-zoom" href="' . $imgFull . '"><i class="fa fa-search-plus"></i></a>';
                $html .= '<a target="_blank" href="' . $plink . '"><i class="fa fa-external-link"></i></a>';
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= sprintf('<div class="tlp-content2 %s"><div class="tlp-content-holder"><h3><a href="%s">%s</a></h3><div class="tlp-portfolio-sd">%s</div></div></div>',
                $content_area,
                $plink,
                $title,
                $limit > 0 ? substr($short_d, 0, $limit) : html_entity_decode($short_d)
            );
            $html .= '</div>';
            $html .= '</div>';

            return $html;
        }

        function templateThree($itemArg) {
            extract($itemArg);
            $html = null;
            $html .= "<div class='tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 tlp-equal-height'>";

            $html .= '<div class="tlp-portfolio-item">';
            if ($img) {
                $html .= '<div class="tlp-portfolio-thum tlp-item">';
                $html .= '<figure>';
                $html .= '<img class="img-responsive" src="' . $img . '" alt="' . $title . '"/>';
                $html .= '</figure>';
                $html .= '<div class="tlp-overlay">';
                $html .= '<p class="link-icon">';
                $html .= '<a class="tlp-zoom" href="' . $imgFull . '"><i class="fa fa-search-plus"></i></a>';
                $html .= '<a target="_blank" href="' . $plink . '"><i class="fa fa-external-link"></i></a>';
                $html .= '</p>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= sprintf('<div class="tlp-content"><div class="tlp-content-holder"><h3><a href="%s">%s</a></h3></div></div>',
                $plink,
                $title
            );
            $html .= '</div>';

            $html .= '</div>';

            return $html;
        }

        function layoutIsotope($itemArg) {
            extract($itemArg);
            $html = null;
            $html .= "<div class='tlp-item tlp-single-item tlp-equal-height tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 {$catClass}'>";
            $html .= '<div class="tlp-portfolio-item">';
            if ($img) {
                $html .= '<div class="tlp-portfolio-thum tlp-item">';
                $html .= '<img class="img-responsive" src="' . $img . '" alt="' . $title . '"/>';
                $html .= '<div class="tlp-overlay">';
                $html .= '<p class="link-icon">';
                $html .= '<a class="tlp-zoom" href="' . $imgFull . '"><i class="fa fa-search-plus"></i></a>';
                $html .= '<a target="_blank" href="' . $plink . '"><i class="fa fa-external-link"></i></a>';
                $html .= '</p>';
                $html .= '</div>';
                $html .= '</div>';
            }

            $html .= sprintf('<div class="tlp-content"><div class="tlp-content-holder"><h3><a href="%s">%s</a></h3><div class="tlp-portfolio-sd">%s</div></div></div>',
                $plink,
                $title,
                $limit > 0 ? substr($short_d, 0, $limit) : html_entity_decode($short_d)
            );
            $html .= '</div>';
            $html .= '</div>';

            return $html;
        }

        private function customStyle($layoutID, $atts) {
            $style = null;
            $title_color = !empty($atts['title-color']) ? $atts['title-color'] : null;
            $title_size = !empty($atts['title-font-size']) ? $atts['title-font-size'] : null;
            $title_weight = !empty($atts['title-font-weight']) ? $atts['title-font-weight'] : null;
            $title_alignment = !empty($atts['title-alignment']) ? $atts['title-alignment'] : null;

            $short_desc_color = !empty($atts['short-desc-color']) ? $atts['short-desc-color'] : null;
            $short_desc_size = !empty($atts['short-desc-font-size']) ? $atts['short-desc-font-size'] : null;
            $short_desc_weight = !empty($atts['short-desc-font-weight']) ? $atts['short-desc-font-weight'] : null;
            $short_desc_alignment = !empty($atts['short-desc-alignment']) ? $atts['short-desc-alignment'] : null;
            if ($title_color) {
                $style .= "#{$layoutID}.tlp-portfolio h3,
							#{$layoutID}.tlp-portfolio h3 a{ color: {$title_color};}";
            }
            if ($title_size) {
                $style .= "#{$layoutID}.tlp-portfolio h3,
							#{$layoutID}.tlp-portfolio h3 a{ font-size: {$title_size}px;}";
            }
            if ($title_weight) {
                $style .= "#{$layoutID}.tlp-portfolio h3,
							#{$layoutID}.tlp-portfolio h3 a{ font-weight: {$title_weight};}";
            }
            if ($title_alignment) {
                $style .= "#{$layoutID}.tlp-portfolio h3{ text-align: {$title_alignment};}";
            }
            // Short description
            if ($short_desc_color || $short_desc_size || $short_desc_weight || $short_desc_alignment) {
                $style .= "#{$layoutID}.tlp-portfolio .tlp-content-holder p{";
                if ($short_desc_color) $style .= "color: {$short_desc_color};";
                if ($short_desc_size) $style .= "font-size: {$short_desc_size}px;";
                if ($short_desc_weight) $style .= "font-weight: {$short_desc_weight};";
                if ($short_desc_alignment) $style .= "text-align: {$short_desc_alignment};";
                $style .= "}";
            }

            if (!empty($style)) {
                $style = "<style>{$style}</style>";
            }

            return $style;

        }
    }


endif;
