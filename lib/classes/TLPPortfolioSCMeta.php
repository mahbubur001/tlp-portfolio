<?php

if (!class_exists('TLPPortfolioSCMeta')):
    /**
     *
     */
    class TLPPortfolioSCMeta
    {

        function __construct() {
            add_action('add_meta_boxes', array($this, 'tlp_portfolio_sc_meta_boxes'));
            add_action('save_post', array($this, 'save_tlp_portfolio_sc_meta_data'), 10, 2);
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts_sc'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts_settings'));
            add_action('edit_form_after_title', array($this, 'portfolio_sc_after_title'));

            add_action('admin_init', array($this, 'remove_all_meta_box'));
        }

        function remove_all_meta_box() {
            if (is_admin()) {
                add_filter("get_user_option_meta-box-order_{TLPPortfolio()->shortCodePT}",
                    array($this, 'remove_all_meta_boxes_portfolio_sc'));
            }
        }

        function remove_all_meta_boxes_portfolio_sc() {
            global $wp_meta_boxes;
            $publishBox = $wp_meta_boxes[TLPPortfolio()->getScPostType()]['side']['core']['submitdiv'];
            $scBox = $wp_meta_boxes[TLPPortfolio()->getScPostType()]['normal']['high']['pfp_sc_settings_meta'];
            $scPreviewBox = $wp_meta_boxes[TLPPortfolio()->getScPostType()]['normal']['high']['pfp_sc_preview_meta'];
            $wp_meta_boxes[TLPPortfolio()->getScPostType()] = array(
                'side'   => array('core' => array('submitdiv' => $publishBox)),
                'normal' => array(
                    'high' => array(
                        'pfp_sc_settings_meta' => $scBox,
                        'pfp_sc_preview_meta'  => $scPreviewBox
                    )
                )
            );

            return array();
        }

        function portfolio_sc_after_title($post) {
            if (TLPPortfolio()->getScPostType() !== $post->post_type) {
                return;
            }
            $html = null;
            $html .= '<div class="postbox" style="margin-bottom: 0;"><div class="inside">';
            $html .= '<p><input type="text" onfocus="this.select();" readonly="readonly" value="[tlpportfolio id=&quot;' . $post->ID . '&quot; title=&quot;' . $post->post_title . '&quot;]" class="large-text code tlp-code-sc">
            <input type="text" onfocus="this.select();" readonly="readonly" value="&#60;&#63;php echo do_shortcode( &#39;[tlpportfolio id=&quot;' . $post->ID . '&quot; title=&quot;' . $post->post_title . '&quot;]&#39; ) &#63;&#62;" class="large-text code tlp-code-sc">
            </p>';
            $html .= '</div></div>';

            echo $html;
        }

        function tlp_portfolio_sc_meta_boxes() {
            add_meta_box(
                'tlp_portfolio_sc_settings_meta',
                __('Short Code Generator', 'tlp-portfolio'),
                array($this, 'tlp_portfolio_sc_settings_selection'),
                TLPPortfolio()->getScPostType(),
                'normal',
                'high');

            add_meta_box(
                'pfp_sc_preview_meta',
                __('Layout Preview', 'tlp-portfolio'),
                array($this, 'pfp_sc_preview_selection'),
                TLPPortfolio()->getScPostType(),
                'normal',
                'high');
            add_meta_box(
                'rt_plugin_portfolio_sc_pro_information',
                __('Pro Documentation', 'tlp-portfolio'),
                array($this, 'rt_plugin_portfolio_sc_pro_information'),
                TLPPortfolio()->getScPostType(),
                'side');
        }

        function rt_plugin_portfolio_sc_pro_information($post) {

            $html = '<div class="rt-document-box">
							<div class="rt-box-icon"><i class="dashicons dashicons-media-document"></i></div>
							<div class="rt-box-content">
                    			<h3 class="rt-box-title">Documentation</h3>
                    				<p>Get started by spending some time with the documentation we included step by step process with screenshots with video.</p>
                        			<a href="https://radiustheme.com/how-to-setup-and-configure-tlp-portfolio-free-version-for-wordpress/" target="_blank" class="rt-admin-btn">Documentation</a>
                			</div>
						</div>';

            $html .= '<div class="rt-document-box">
							<div class="rt-box-icon"><i class="dashicons dashicons-sos"></i></div>
							<div class="rt-box-content">
                    			<h3 class="rt-box-title">Need Help?</h3>
                    				<p>Stuck with something? Please create a 
                        <a href="https://www.radiustheme.com/contact/">ticket here</a> or post on <a href="https://www.facebook.com/groups/234799147426640/">facebook group</a>. For emergency case join our <a href="https://www.radiustheme.com/">live chat</a>.</p>
                        			<a href="https://www.radiustheme.com/contact/" target="_blank" class="rt-admin-btn">Get Support</a>
                			</div>
						</div>';
            $btn_html = '<div class="rt-document-box rt-update-pro-btn-wrap">
                <a href="https://radiustheme.com/tlp-portfolio-pro-for-wordpress/" target="_blank" class="rt-update-pro-btn">Update Pro To Get More Features</a>
            </div>';
            if ($post === 'settings') {
                $html = $btn_html . $html;
            } else {
                $html .= $btn_html;
            }

            echo $html;
        }

        function tlp_portfolio_sc_settings_selection() {
            wp_nonce_field(TLPPortfolio()->nonceText(), TLPPortfolio()->nonceId());
            $html = null;
            $html .= '<div id="sc-tabs" class="rt-tabs rt-tab-container">';
            $html .= '<ul class="tab-nav rt-tab-nav">
	                            <li class="active"><a href="#sc-layout-settings"><i class="dashicons dashicons-layout"></i>' . __('Layout', 'tlp-portfolio') . '</a></li>
	                            <li><a href="#sc-filtering"><i class="dashicons dashicons-filter"></i>' . __('Filtering', 'tlp-portfolio') . '</a></li>
	                            <li><a href="#sc-style"><i class="dashicons dashicons-admin-customizer"></i>' . __('Styling', 'tlp-portfolio') . '</a></li>
	                          </ul>';

            $html .= '<div id="sc-layout-settings" class="rt-tab-content" style="display: block">';
            $html .= '<div class="tab-content">';
            $html .= TLPPortfolio()->rtFieldGenerator(TLPPortfolio()->scLayoutMetaFields());
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div id="sc-filtering" class="rt-tab-content">';
            $html .= '<div class="tab-content">';
            $html .= TLPPortfolio()->rtFieldGenerator(TLPPortfolio()->scFilterMetaFields());
            $html .= '</div>';
            $html .= '</div>';


            $html .= '<div id="sc-style" class="rt-tab-content">';
            $html .= '<div class="tab-content">';
            $html .= TLPPortfolio()->rtFieldGenerator(TLPPortfolio()->scStyleFields());
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            echo $html;
        }

        function pfp_sc_preview_selection() {
            $html = null;
            $html .= "<div id='pfp-response'><span class='spinner'></span></div>";
            $html .= "<div id='pfp-preview-container'>";
            $html .= "</div>";

            echo $html;
        }

        function save_tlp_portfolio_sc_meta_data($post_id, $post) {

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if (!TLPPortfolio()->verifyNonce()) {
                return $post_id;
            }

            if (TLPPortfolio()->getScPostType() != $post->post_type) {
                return $post_id;
            }

            $mates = TLPPortfolio()->pfpScMetaFields();
            foreach ($mates as $metaKey => $field) {
                $rValue = !empty($_REQUEST[$metaKey]) ? $_REQUEST[$metaKey] : null;
                $value = TLPPortfolio()->sanitize($field, $rValue);
                if (empty($field['multiple'])) {
                    update_post_meta($post_id, $metaKey, $value);
                } else {
                    delete_post_meta($post_id, $metaKey);
                    if (is_array($value) && !empty($value)) {
                        foreach ($value as $item) {
                            add_post_meta($post_id, $metaKey, $item);
                        }
                    } else {
                        update_post_meta($post_id, $metaKey, "");
                    }
                }
            }

        }

        function admin_enqueue_scripts_settings() {
            global $typenow;

            if ($typenow != TLPPortfolio()->post_type || !isset($_GET['page']) || $_GET['page'] !== 'tlp_portfolio_settings') {
                return;
            }

            wp_enqueue_script(['wp-color-picker', 'tlp-portfolio-admin']);
            wp_enqueue_style(['tlp-portfolio-admin', 'wp-color-picker']);
        }

        function admin_enqueue_scripts_sc() {
            global $pagenow, $typenow;
            // validate page
            if (!in_array($pagenow, array('post.php', 'post-new.php', 'edit.php'))) {
                return;
            }

            if ($typenow != TLPPortfolio()->getScPostType()) {
                return;
            }

            // scripts
            wp_enqueue_script(array(
                'jquery',
                'wp-color-picker-alpha',
                'tlp-magnific',
                TLPPortfolio()->getSelect2JsId(),
                'tlp-owl-carousel',
                'tlp-isotope',
                'tlp-portfolio',
                'tlp-portfolio-admin',
            ));

            // styles
            wp_enqueue_style(array(
                'wp-color-picker',
                'tlp-fontawsome',
                'tlp-select2',
                'tlp-owl-carousel',
                'tlp-owl-carousel-theme',
                'tlp-portfolio',
                'tlp-portfolio-admin',
            ));

        }
    }
endif;