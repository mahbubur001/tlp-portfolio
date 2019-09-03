<?php
if ( ! class_exists( 'PortfolioPostMeta' ) ):

	/**
	 *
	 */
	class PortfolioPostMeta {

		function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'portfolio_met_boxs' ) );
			add_action( 'save_post', array( $this, 'save_profile_meta_data' ), 10, 3 );
			add_action( 'admin_print_scripts-post-new.php', array( $this, 'tpl_portfolio_script' ), 11 );
			add_action( 'admin_print_scripts-post.php', array( $this, 'tpl_portfolio_script' ), 11 );
			add_action( 'edit_form_after_title', array( $this, 'portfolio_after_title' ) );
		}

		function portfolio_after_title( $post ) {
			global $TLPportfolio;
			if ( $TLPportfolio->post_type !== $post->post_type ) {
				return;
			}
			$html = null;
			$html .= '<div class="postbox" style="margin-bottom: 0;"><div class="inside">';
			$html .= '<p style="text-align: center;"><a style="color: red; text-decoration: none; font-size: 14px;" href="https://radiustheme.com/tlp-portfolio-pro-for-wordpress/" target="_blank">Please check the pro features</a></p>';
			$html .= '</div></div>';

			echo $html;
		}

		function portfolio_met_boxs() {
			add_meta_box( 'tlp_portfolio_meta', __( 'Portfolio Details', 'tlp-portfolio' ),
				array( $this, 'tlp_portfolio_meta' ), 'portfolio', 'normal', 'high' );
		}

		function tlp_portfolio_meta( $post ) {
			global $TLPportfolio;
			wp_nonce_field( $TLPportfolio->nonceText(), 'tlp_nonce' );
			$meta              = get_post_meta( $post->ID );
			$short_description = ! isset( $meta['short_description'][0] ) ? '' : $meta['short_description'][0];
			$project_url       = ! isset( $meta['project_url'][0] ) ? '' : $meta['project_url'][0];
			$tools             = ! isset( $meta['tools'][0] ) ? '' : $meta['tools'][0];
			?>

			<table class="form-table">

				<tr>
					<td class="team_meta_box_td" colspan="2">
						<label for="short_description"><?php _e( 'Short Description', 'tlp-portfolio' ); ?>
						</label>
					</td>
					<td colspan="4">
						<textarea name="short_description" cols="40" rows="5" class="tlpfield" value=""><?php
							echo $short_description; ?></textarea>
						<p class="description"><?php
							_e( 'Add some short description for hint view', 'tlp-portfolio' ); ?></p>
					</td>
				</tr>

				<tr>
					<td class="team_meta_box_td" colspan="2">
						<label for="project_url"><?php
							_e( 'Project Url', 'tlp-portfolio' ); ?>
						</label>
					</td>
					<td colspan="4">
						<input type="url" name="project_url" class="regular-text" value="<?php
						echo $project_url; ?>">
					</td>
				</tr>

				<tr>
					<td class="team_meta_box_td" colspan="2">
						<label for="tools"><?php
							_e( 'Tools Used', 'tlp-portfolio' ); ?>
						</label>
					</td>
					<td colspan="4">
						<input type="text" name="tools" class="regular-text" value="<?php
						echo $tools; ?>">
						<p class="description"><?php
							_e( 'Add the tools which are used in this project', 'tlp-portfolio' ) ?></p>
					</td>
				</tr>
			</table>
			<?php
		}

		function save_profile_meta_data( $post_id, $post, $update ) {

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			global $TLPportfolio;

			if ( ! wp_verify_nonce( @$_REQUEST['tlp_nonce'], $TLPportfolio->nonceText() ) ) {
				return;
			}


			if ( $TLPportfolio->post_type != $post->post_type ) {
				return;
			}

			$meta['short_description'] = ( isset( $_POST['short_description'] ) ? wp_kses_post( $_POST['short_description'] ) : '' );
			$meta['project_url']       = ( isset( $_POST['project_url'] ) ? esc_attr( $_POST['project_url'] ) : '' );
			$meta['tools']             = ( isset( $_POST['tools'] ) ? esc_attr( $_POST['tools'] ) : '' );

			foreach ( $meta as $key => $value ) {
				update_post_meta( $post->ID, $key, $value );
			}
		}

		function tpl_portfolio_script() {
			global $post_type, $TLPportfolio;
			if ( $post_type == 'portfolio' ) {
				$TLPportfolio->tlp_style();
				$TLPportfolio->tlp_script();
			}
		}
	}
endif;
