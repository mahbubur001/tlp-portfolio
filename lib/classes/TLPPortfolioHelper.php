<?php
if ( ! class_exists( 'TLPPortfolioHelper' ) ) :

	class TLPPortfolioHelper {

		function verifyNonce() {
			global $TLPportfolio;
			$nonce     = ! empty( $_REQUEST['tlp_nonce'] ) ? $_REQUEST['tlp_nonce'] : null;
			$nonceText = $TLPportfolio->nonceText();
			if ( ! wp_verify_nonce( $nonce, $nonceText ) ) {
				die( 'Security check' );
			}

			return true;
		}

		function nonceText() {
			return "tlp_portfolio_nonce";
		}

		function get_image_sizes() {
			global $_wp_additional_image_sizes;

			$sizes      = array();
			$interSizes = get_intermediate_image_sizes();
			if ( ! empty( $interSizes ) ) {
				foreach ( get_intermediate_image_sizes() as $_size ) {
					if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
						$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
						$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
						$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
					} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
						$sizes[ $_size ] = array(
							'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
							'height' => $_wp_additional_image_sizes[ $_size ]['height'],
							'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
						);
					}
				}
			}

			$imgSize = array();
			if ( ! empty( $sizes ) ) {
				foreach ( $sizes as $key => $img ) {
					$imgSize[ $key ] = ucfirst( $key ) . " ({$img['width']}*{$img['height']})";
				}
			}
			$imgSize['rt_custom'] = "Custom image size";

			return $imgSize;
		}

		function getFeatureImageSrc( $post_id = null, $fImgSize = 'team-thumb', $customImgSize = array() ) {
			$imgSrc = null;
			$cSize  = false;
			if ( $fImgSize == 'rt_custom' ) {
				$fImgSize = 'full';
				$cSize    = true;
			}

			if ( $aID = get_post_thumbnail_id( $post_id ) ) {
				$image  = wp_get_attachment_image_src( $aID, $fImgSize );
				$imgSrc = $image[0];
			}

			if ( $imgSrc && $cSize ) {
				global $TLPportfolio;
				$w = ( ! empty( $customImgSize['width'] ) ? absint( $customImgSize['width'] ) : null );
				$h = ( ! empty( $customImgSize['height'] ) ? absint( $customImgSize['height'] ) : null );
				$c = ( ! empty( $customImgSize['crop'] ) && $customImgSize['crop'] == 'soft' ? false : true );
				if ( $w && $h ) {
					$imgSrc = $TLPportfolio->rtImageReSize( $imgSrc, $w, $h, $c );
				}
			}

			return $imgSrc;
		}

		function rtImageReSize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
			$rtResize = new TLPPortfolioReSizer();

			return $rtResize->process( $url, $width, $height, $crop, $single, $upscale );
		}

		function getAllPortFolioCategoryList() {
			global $TLPportfolio;
			$terms    = array();
			$termList = get_terms( array( $TLPportfolio->taxonomies['category'] ), array( 'hide_empty' => 0 ) );
			if ( is_array( $termList ) && ! empty( $termList ) && empty( $termList['errors'] ) ) {
				foreach ( $termList as $term ) {
					$terms[ $term->term_id ] = $term->name;
				}
			}

			return $terms;
		}

		function TLPhex2rgba( $color, $opacity = false ) {
			$default = 'rgb(0,0,0)';

			//Return default if no color provided
			if ( empty( $color ) ) {
				return $default;
			}

			//Sanitize $color if "#" is provided
			if ( $color[0] == '#' ) {
				$color = substr( $color, 1 );
			}

			//Check if color has 6 or 3 characters and get values
			if ( strlen( $color ) == 6 ) {
				$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
			} elseif ( strlen( $color ) == 3 ) {
				$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
			} else {
				return $default;
			}

			//Convert hexadec to rgb
			$rgb = array_map( 'hexdec', $hex );

			//Check if opacity is set(rgba or rgb)
			if ( $opacity ) {
				if ( abs( $opacity ) > 1 ) {
					$opacity = 1.0;
				}
				$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
			} else {
				$output = 'rgb(' . implode( ",", $rgb ) . ')';
			}

			//Return rgb(a) color string
			return $output;
		}

		function singlePortfolioMeta( $id = null ) {
			global $TLPportfolio;
			$id = ( ! $id ? get_the_ID() : $id );
			if ( ! $id ) {
				return;
			}

			$project_url = get_post_meta( $id, 'project_url', true );
			$tools       = get_post_meta( get_the_ID(), 'tools', true );
			$categories  = strip_tags( get_the_term_list( $id, $TLPportfolio->taxonomies['category'],
				__( 'Categories: ', 'tlp-portfolio' ), ', ' ) );
			$tags        = strip_tags( get_the_term_list( $id, $TLPportfolio->taxonomies['tag'], 'Tags: ', ', ' ) );

			$html = null;
			$html .= '<ul class="single-item-meta">';
			if ( $project_url ) {
				$html .= '<li>' . __( 'Project URL',
						'tlp-portfolio' ) . ': <a  href="' . $project_url . '" target="_blank">' . $project_url . '</a></li>';
			}
			if ( $categories ) {
				$html .= '<li class="categories">' . $categories . '</li>';
			}
			if ( $tools ) {
				$html .= '<li class="tools">' . __( 'Tools', 'tlp-portfolio' ) . ': ' . $tools . '</li>';
			}
			if ( $tags ) {
				$html .= '<li class="tags">' . $tags . '</li>';
			}
			$html .= "</ul>";
			if ( $html ) {
				$html = "<ul class='single-item-meta'>{$html}</ul>";
			}

			return $html;
		}

		function socialShare( $pLink ) {
			$html = null;
			$html .= "<div class='single-portfolio-share'>
                        <div class='fb-share'>
                            <div class='fb-share-button' data-href='{$pLink}' data-layout='button_count'></div>
                        </div>
                        <div class='twitter-share'>
                            <a href='{$pLink}' class='twitter-share-button'{count} data-url='https://about.twitter.com/resources/buttons#tweet'>Tweet</a>
                        </div>
                        
                        <div class='linkedin-share'>
                            <script type='IN/Share' data-counter='right'></script>
                        </div>
                        <div class='googleplus-share'>
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
			$html = '<ol>
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

			return $html;
		}

	}
endif;
