<?php

if ( ! class_exists( 'TLPPortfolioSCMeta' ) ):
	/**
	 *
	 */
	class TLPPortfolioSCMeta {

		function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'tlp_portfolio_sc_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_tlp_portfolio_sc_meta_data' ), 10, 2 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts_sc' ) );
			add_action( 'edit_form_after_title', array( $this, 'portfolio_sc_after_title' ) );

			add_action( 'admin_init', array( $this, 'remove_all_meta_box' ) );
		}

		function remove_all_meta_box() {
			if ( is_admin() ) {
				add_filter( "get_user_option_meta-box-order_{TLPPortfolio()->shortCodePT}",
					array( $this, 'remove_all_meta_boxes_portfolio_sc' ) );
			}
		}

		function remove_all_meta_boxes_portfolio_sc() {
			global $wp_meta_boxes;
			$publishBox                                       = $wp_meta_boxes[ TLPPortfolio()->getScPostType() ]['side']['core']['submitdiv'];
			$scBox                                            = $wp_meta_boxes[ TLPPortfolio()->getScPostType() ]['normal']['high']['pfp_sc_settings_meta'];
			$scPreviewBox                                     = $wp_meta_boxes[ TLPPortfolio()->getScPostType() ]['normal']['high']['pfp_sc_preview_meta'];
			$wp_meta_boxes[ TLPPortfolio()->getScPostType() ] = array(
				'side'   => array( 'core' => array( 'submitdiv' => $publishBox ) ),
				'normal' => array(
					'high' => array(
						'pfp_sc_settings_meta' => $scBox,
						'pfp_sc_preview_meta'  => $scPreviewBox
					)
				)
			);

			return array();
		}

		function portfolio_sc_after_title( $post ) {
			if ( TLPPortfolio()->getScPostType() !== $post->post_type ) {
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
				__( 'Short Code Generator', 'tlp-portfolio' ),
				array( $this, 'tlp_portfolio_sc_settings_selection' ),
				TLPPortfolio()->getScPostType(),
				'normal',
				'high' );

			add_meta_box(
				'pfp_sc_preview_meta',
				__( 'Layout Preview', 'tlp-portfolio' ),
				array( $this, 'pfp_sc_preview_selection' ),
				TLPPortfolio()->getScPostType(),
				'normal',
				'high' );
		}

		function tlp_portfolio_sc_settings_selection() {
			wp_nonce_field( TLPPortfolio()->nonceText(), TLPPortfolio()->nonceId() );
			$html = null;
			$html .= '<div id="sc-tabs" class="rt-tabs rt-tab-container">';
			$html .= '<ul class="tab-nav rt-tab-nav">
	                            <li class="active"><a href="#sc-layout-settings">' . __( 'Layout Settings', 'tlp-portfolio' ) . '</a></li>
	                            <li><a href="#sc-filtering">' . __( 'Filtering', 'tlp-portfolio' ) . '</a></li>
	                            <li><a href="#sc-style">' . __( 'Styling', 'tlp-portfolio' ) . '</a></li>
	                          </ul>';

			$html .= '<div id="sc-layout-settings" class="rt-tab-content" style="display: block">';
			$html .= '<div class="tab-content">';
			$html .= TLPPortfolio()->rtFieldGenerator( TLPPortfolio()->scLayoutMetaFields() );
			$html .= '</div>';
			$html .= '</div>';

			$html .= '<div id="sc-filtering" class="rt-tab-content">';
			$html .= '<div class="tab-content">';
			$html .= TLPPortfolio()->rtFieldGenerator( TLPPortfolio()->scFilterMetaFields() );
			$html .= '</div>';
			$html .= '</div>';


			$html .= '<div id="sc-style" class="rt-tab-content">';
			$html .= '<div class="tab-content">';
			$html .= TLPPortfolio()->rtFieldGenerator( TLPPortfolio()->scStyleFields() );
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

		function save_tlp_portfolio_sc_meta_data( $post_id, $post ) {

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! TLPPortfolio()->verifyNonce() ) {
				return $post_id;
			}

			if ( TLPPortfolio()->getScPostType() != $post->post_type ) {
				return $post_id;
			}

			$mates = TLPPortfolio()->pfpScMetaFields();
			foreach ( $mates as $metaKey => $field ) {
				$rValue = ! empty( $_REQUEST[ $metaKey ] ) ? $_REQUEST[ $metaKey ] : null;
				$value  = TLPPortfolio()->sanitize( $field, $rValue );
				if ( empty( $field['multiple'] ) ) {
					update_post_meta( $post_id, $metaKey, $value );
				} else {
					delete_post_meta( $post_id, $metaKey );
					if ( is_array( $value ) && ! empty( $value ) ) {
						foreach ( $value as $item ) {
							add_post_meta( $post_id, $metaKey, $item );
						}
					} else {
						update_post_meta( $post_id, $metaKey, "" );
					}
				}
			}

		}

		function admin_enqueue_scripts_sc() {
			global $pagenow, $typenow;
			// validate page
			if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php' ) ) ) {
				return;
			}

			if ( $typenow != TLPPortfolio()->getScPostType() ) {
				return;
			}

			// scripts
			wp_enqueue_script( array(
				'jquery',
				'wp-color-picker-alpha',
				'tlp-magnific',
				TLPPortfolio()->getSelect2JsId(),
				'tlp-owl-carousel',
				'tlp-isotope',
				'tlp-portfolio',
				'tlp-portfolio-admin',
			) );

			// styles
			wp_enqueue_style( array(
				'wp-color-picker',
				'tlp-fontawsome',
				'tlp-select2',
				'tlp-owl-carousel',
				'tlp-owl-carousel-theme',
				'tlp-portfolio',
				'tlp-portfolio-admin',
			) );

			wp_localize_script( 'tlp-portfolio-admin', 'tlp_portfolio_obj', array(
				'ajaxurl' => admin_url( 'admin-ajax.php ' ),
				'nonce'   => wp_create_nonce( TLPPortfolio()->nonceText() ),
				'nonceId' => TLPPortfolio()->nonceId()
			) );
		}
	}
endif;