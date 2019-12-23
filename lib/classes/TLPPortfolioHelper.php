<?php
if (!class_exists('TLPPortfolioHelper')) :

    class TLPPortfolioHelper
    {

        function verifyNonce() {
            $nonce = isset($_REQUEST['tlp_nonce']) && !empty($_REQUEST['tlp_nonce']) ? $_REQUEST['tlp_nonce'] : null;
            $nonceText = $this->nonceText();
            if (wp_verify_nonce($nonce, $nonceText)) {
                return true;
            }

            return false;
        }

        function nonceId() {
            return "tlp_nonce";
        }

        function nonceText() {
            return "tlp_portfolio_nonce";
        }


        function meta_exist($post_id, $meta_key, $type = "post") {
            if (!$post_id) {
                return false;
            }

            return metadata_exists($type, $post_id, $meta_key);
        }

        function get_short_description($content, $limit = 0, $tags = '', $invert = false) {
            $filter_content = wp_kses_post(html_entity_decode($content));
            if ($limit) {
                $filter_content = wp_filter_nohtml_kses($filter_content);
                if ($limit > 0 && strlen($filter_content) > $limit) {
                    $filter_content = mb_substr($filter_content, 0, $limit, "utf-8");
                    $filter_content = preg_replace('/\W\w+\s*(\W*)$/', '$1', $filter_content);
                }
            }

            return apply_filters('tlp_portfolio_get_short_description', $filter_content, $content, $limit);
        }

        /**
         * @return string
         * Remove select2Js confection
         */
        function getSelect2JsId() {
            $select2Id = 'tlp-select2';
            if (class_exists('WPSEO_Admin_Asset_Manager') && class_exists('Avada')) {
                $select2Id = 'yoast-seo-select2';
            } elseif (class_exists('WPSEO_Admin_Asset_Manager')) {
                $select2Id = 'yoast-seo-select2';
            } elseif (class_exists('Avada')) {
                $select2Id = 'select2-avada-js';
            }

            return $select2Id;
        }

        function pfpScMetaFields() {
            return array_merge(
                TLPPortfolio()->scLayoutMetaFields(),
                TLPPortfolio()->scFilterMetaFields(),
                TLPPortfolio()->scStyleFields()
            );
        }

        function rtFieldGenerator($fields = array()) {
            $html = null;
            if (is_array($fields) && !empty($fields)) {
                $PFProField = new TlpPortfolioField();
                foreach ($fields as $fieldKey => $field) {
                    $html .= $PFProField->Field($fieldKey, $field);
                }
            }

            return $html;
        }

        function getAllPortFolioCategoryList() {
            $terms = array();
            $termList = get_terms(array(TLPPortfolio()->taxonomies['category']), array('hide_empty' => 0));
            if (is_array($termList) && !empty($termList) && empty($termList['errors'])) {
                foreach ($termList as $term) {
                    $terms[$term->term_id] = $term->name;
                }
            }

            return $terms;
        }


        function getAllPortFolioTagList() {
            $terms = array();
            $termList = get_terms(array(TLPPortfolio()->taxonomies['tag']), array('hide_empty' => 0));
            if (is_array($termList) && !empty($termList) && empty($termList['errors'])) {
                foreach ($termList as $term) {
                    $terms[$term->term_id] = $term->name;
                }
            }

            return $terms;
        }

        function get_image_sizes() {
            global $_wp_additional_image_sizes;

            $sizes = array();
            $interSizes = get_intermediate_image_sizes();
            if (!empty($interSizes)) {
                foreach (get_intermediate_image_sizes() as $_size) {
                    if (in_array($_size, array('thumbnail', 'medium', 'large'))) {
                        $sizes[$_size]['width'] = get_option("{$_size}_size_w");
                        $sizes[$_size]['height'] = get_option("{$_size}_size_h");
                        $sizes[$_size]['crop'] = (bool)get_option("{$_size}_crop");
                    } elseif (isset($_wp_additional_image_sizes[$_size])) {
                        $sizes[$_size] = array(
                            'width'  => $_wp_additional_image_sizes[$_size]['width'],
                            'height' => $_wp_additional_image_sizes[$_size]['height'],
                            'crop'   => $_wp_additional_image_sizes[$_size]['crop'],
                        );
                    }
                }
            }

            $imgSize = array();
            if (!empty($sizes)) {
                foreach ($sizes as $key => $img) {
                    $imgSize[$key] = ucfirst($key) . " ({$img['width']}*{$img['height']})";
                }
            }
            $imgSize['pfp_custom'] = __("Custom image size", "tlp-portfolio");

            return $imgSize;
        }

        function getFeatureImageSrc($post_id = null, $fImgSize = 'team-thumb', $customImgSize = array()) {
            $imgSrc = null;
            $cSize = false;
            if ($fImgSize == 'rt_custom') {
                $fImgSize = 'full';
                $cSize = true;
            }

            if ($aID = get_post_thumbnail_id($post_id)) {
                $image = wp_get_attachment_image_src($aID, $fImgSize);
                $imgSrc = $image[0];
            }

            if ($imgSrc && $cSize) {
                global $TLPportfolio;
                $w = (!empty($customImgSize['width']) ? absint($customImgSize['width']) : null);
                $h = (!empty($customImgSize['height']) ? absint($customImgSize['height']) : null);
                $c = (!empty($customImgSize['crop']) && $customImgSize['crop'] == 'soft' ? false : true);
                if ($w && $h) {
                    $imgSrc = $TLPportfolio->rtImageReSize($imgSrc, $w, $h, $c);
                }
            }

            return $imgSrc;
        }

        function rtImageReSize($url, $width = null, $height = null, $crop = null, $single = true, $upscale = false) {
            $rtResize = new PFProReSizer();

            return $rtResize->process($url, $width, $height, $crop, $single, $upscale);
        }


        /**
         * Sanitize field value
         *
         * @param array $field
         * @param null  $value
         *
         * @return array|null
         * @internal param $value
         */
        function sanitize($field = array(), $value = null) {
            $newValue = null;
            if (is_array($field)) {
                $type = (!empty($field['type']) ? $field['type'] : 'text');
                if (empty($field['multiple'])) {
                    if ($type == 'url') {
                        $newValue = esc_url($value);
                    } else if ($type == 'slug') {
                        $newValue = sanitize_title_with_dashes($value);
                    } else if ($type == 'textarea') {
                        $newValue = wp_kses_post($value);
                    } else if ($type == 'custom_css') {
                        $newValue = htmlentities(stripslashes($value));
                    } else if ($type == 'image_size') {
                        $newValue = array();
                        foreach ($value as $k => $v) {
                            $newValue[$k] = esc_attr($v);
                        }
                    } else if ($type == 'style') {
                        $newValue = array();
                        foreach ($value as $k => $v) {
                            if ($k == 'color') {
                                $newValue[$k] = $this->sanitize_hex_color($v);
                            } else {
                                $newValue[$k] = $this->sanitize(array('type' => 'text'), $v);
                            }
                        }
                    } else {
                        $newValue = sanitize_text_field($value);
                    }

                } else {
                    $newValue = array();
                    if (!empty($value)) {
                        if (is_array($value)) {
                            foreach ($value as $key => $val) {
                                if ($type == 'style' && $key == 0) {
                                    if (function_exists('sanitize_hex_color')) {
                                        $newValue = sanitize_hex_color($val);
                                    } else {
                                        $newValue[] = $this->sanitize_hex_color($val);
                                    }
                                } else {
                                    $newValue[] = sanitize_text_field($val);
                                }
                            }
                        } else {
                            $newValue[] = sanitize_text_field($value);
                        }
                    }
                }
            }

            return $newValue;
        }

        function sanitize_hex_color($color) {
            if (function_exists('sanitize_hex_color')) {
                return sanitize_hex_color($color);
            } else {
                if ('' === $color) {
                    return '';
                }

                // 3 or 6 hex digits, or the empty string.
                if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) {
                    return $color;
                }
            }
        }

        function TLPhex2rgba($color, $opacity = false) {
            $default = 'rgb(0,0,0)';

            //Return default if no color provided
            if (empty($color)) {
                return $default;
            }

            //Sanitize $color if "#" is provided
            if ($color[0] == '#') {
                $color = substr($color, 1);
            }

            //Check if color has 6 or 3 characters and get values
            if (strlen($color) == 6) {
                $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
            } elseif (strlen($color) == 3) {
                $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
            } else {
                return $default;
            }

            //Convert hexadec to rgb
            $rgb = array_map('hexdec', $hex);

            //Check if opacity is set(rgba or rgb)
            if ($opacity) {
                if (abs($opacity) > 1) {
                    $opacity = 1.0;
                }
                $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
            } else {
                $output = 'rgb(' . implode(",", $rgb) . ')';
            }

            //Return rgb(a) color string
            return $output;
        }

        function singlePortfolioMeta($id = null) {
            global $TLPportfolio;
            $id = (!$id ? get_the_ID() : $id);
            if (!$id) {
                return;
            }

            $project_url = get_post_meta($id, 'project_url', true);
            $tools = get_post_meta(get_the_ID(), 'tools', true);
            $categories = strip_tags(get_the_term_list($id, $TLPportfolio->taxonomies['category'],
                __('Categories: ', 'tlp-portfolio'), ', '));
            $tags = strip_tags(get_the_term_list($id, $TLPportfolio->taxonomies['tag'], 'Tags: ', ', '));

            $html = null;
            $html .= '<ul class="single-item-meta">';
            if ($project_url) {
                $html .= '<li>' . __('Project URL',
                        'tlp-portfolio') . ': <a  href="' . $project_url . '" target="_blank">' . $project_url . '</a></li>';
            }
            if ($categories) {
                $html .= '<li class="categories">' . $categories . '</li>';
            }
            if ($tools) {
                $html .= '<li class="tools">' . __('Tools', 'tlp-portfolio') . ': ' . $tools . '</li>';
            }
            if ($tags) {
                $html .= '<li class="tags">' . $tags . '</li>';
            }
            $html .= "</ul>";
            if ($html) {
                $html = "<ul class='single-item-meta'>{$html}</ul>";
            }

            return $html;
        }

        function socialShare($pLink) {
            $html = null;
            $html .= "<div class='single-portfolio-share'>
                        <div class='fb-share rt-share-item'>
                            <div class='fb-share-button' data-href='{$pLink}' data-layout='button_count'></div>
                        </div>
                        <div class='twitter-share rt-share-item'>
                            <a href='{$pLink}' class='twitter-share-button'{count} data-url='https://about.twitter.com/resources/buttons#tweet'>Tweet</a>
                        </div>
                        
                        <div class='linkedin-share rt-share-item'>
                            <script type='IN/Share' data-counter='right'></script>
                        </div>
                        <div class='googleplus-share rt-share-item'>
                            <div class='g-plusone'></div>
                        </div>
                   </div>";
            $html .= '<div id="fb-root"></div>
            <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, "script", "facebook-jssdk"));</script>';
            $html .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            <script>window.___gcfg = { lang: 'en-US', parsetags: 'onload', };</script>";
            $html .= "<script src='https://apis.google.com/js/platform.js' async defer></script>";
            $html .= '<script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>';
            $html .= '<script async defer src="//assets.pinterest.com/js/pinit.js"></script>';

            return $html;
        }

        function proFeatureList() {
            return '<ol>
                        <li>Full Responsive & Mobile Friendly</li>
                        <li>57 Layouts (Even Grid, Masonry Grid, Even Isotope, Masonry Isotope & Carousel Slider)</li>
                        <li>Unlimited Layouts Variation</li>
                        <li>Unlimited Colors</li>
                        <li>Unlimited ShortCode Generator</li>
                        <li>Drag & Drop Ordering</li>
                        <li>Drag & Drop Taxonomy ie Category Ordering</li>
                        <li>Gutter / Padding Control</li>
                        <li>Dynamic image Re-size & Custom image size</li>
                        <li>Detail page with popup next preview button and normal view</li>
                        <li>Device wise Item View Control</li>
                        <li>Visual Composer Compatibility</li>
                        <li> 4 Types of Pagination Normal number, AJAX number Pagination, AJAX Load More & Auto Load on Scroll</li>
                        <li>Layout Preview Under ShortCode Generator</li>
                        <li>Count for Isotope Button</li>
                        <li>Search for Isotope Layouts</li>
                        <li>All Fields Control show/hide</li>
                        <li>RTL Added for Carousel Slider</li>
                        <li>All text color, Size and Text align control</li>
                        <li>Related Portfolio</li>
                    </ol>
                    <p><a href="https://radiustheme.com/tlp-portfolio-pro-for-wordpress/" class="button button-primary" target="_blank">Get Pro Version</a></p>';

        }


        /**
         * @param     $query
         * @param int $args
         * @param     $scMeta
         *
         * @return string|null
         */
        function pagination($query, $args, $scMeta) {
            $html = null;
            $range = isset($args['posts_per_page']) ? $args['posts_per_page'] : 4;
            $showitems = ($range * 2) + 1;
            global $paged;
            if (empty($paged)) {
                $paged = 1;
            }
            $pages = $query->max_num_pages;
            if (!$pages) {
                global $wp_query;
                $pages = $wp_query->max_num_pages;
                $pages = $pages ? $pages : 1;
            }

            if (1 != $pages) {
                $li = null;
                if (apply_filters('tlp_portfolio_pagination_page_count', true)) {
                    $li .= sprintf('<li class="disabled hidden-xs"><span><span aria-hidden="true">%s</span></span></li>',
                        sprintf(__('Page %d of %d', "tlp-portfolio"), $paged, $pages)
                    );
                }
                if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
                    $li .= sprintf('<li><a href="%1$s" aria-label="%2$s">&laquo;<span class="hidden-xs">%2$s</span></a></li>',
                        get_pagenum_link(1),
                        __("First", "tlp-portfolio")
                    );
                }

                if ($paged > 1 && $showitems < $pages) {
                    $li .= sprintf('<li><a href="%1$s" aria-label="%2$s">&lsaquo;<span class="hidden-xs">%2$s</span></a></li>',
                        get_pagenum_link($paged - 1),
                        __("Previous", "tlp-portfolio")
                    );
                }


                for ($i = 1; $i <= $pages; $i++) {
                    if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                        $li .= $paged == $i ? sprintf('<li class="active"><span>%d</span></li>', $i)
                            : sprintf('<li><a href="%s">%d</a></li>', get_pagenum_link($i), $i);

                    }

                }


                if ($paged < $pages && $showitems < $pages) {
                    $li .= sprintf('<li><a href="%1$s" aria-label="%2$s">&lsaquo;<span class="hidden-xs">%2$s </span></a></li>',
                        get_pagenum_link($paged + 1),
                        __("Next", "tlp-portfolio")
                    );
                }

                if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
                    $li .= sprintf('<li><a href="%1$s" aria-label="%2$s">&raquo;<span class="hidden-xs">%2$s </span></a></li>',
                        get_pagenum_link($pages),
                        __("Last", "tlp-portfolio")
                    );
                }

                $html = sprintf('<div class="tlp-pagination-wrap" data-total-pages="%d" data-posts-per-page="%d">%s</div>',
                    $query->max_num_pages,
                    $args['posts_per_page'],
                    $li ? sprintf('<ul class="tlp-pagination">%s</ul>', $li) : ''
                );

            }

            return apply_filters('tlp_portfolio_pagination_html', $html, $query, $args, $scMeta);

        }

        function get_shortCode_list() {
            $scList = array();
            $scQ = get_posts(array(
                'post_type'      => TLPPortfolio()->getScPostType(),
                'order_by'       => 'title',
                'order'          => 'ASC',
                'post_status'    => 'publish',
                'posts_per_page' => -1
            ));
            if (!empty($scQ)) {
                $scList = wp_list_pluck($scQ, 'post_title', 'ID');
            }

            return $scList;
        }

    }
endif;
