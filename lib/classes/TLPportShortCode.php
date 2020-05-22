<?php

if ( ! class_exists( 'TLPportShortCode' ) ):

	/**
	 *
	 */
	class TLPportShortCode {

		function __construct() {
			add_shortcode( 'tlpportfolio', array( $this, 'portfolio_shortcode' ) );
			add_action( 'wp_ajax_tlp_portfolio_preview_ajax_call', array( $this, 'portfolio_shortcode' ) );
		}

		public function load_scripts() {
			wp_enqueue_style( array(
				'tlp-fontawsome'
			) );
			wp_enqueue_script( array(
				'tlp-magnific',
				'tlp-isotope',
				'tlp-owl-carousel',
				'tlp-portfolio'
			) );
		}

		function portfolio_shortcode( $atts, $content = "" ) {

			$error = true;
			$html  = $msg = null;


			$preview = isset( $_REQUEST['sc_id'] ) ? absint( $_REQUEST['sc_id'] ) : 0;
			$scID    = isset( $atts['id'] ) ? absint( $atts['id'] ) : 0;
			if ( $scID || $preview ) {
				$post = get_post( $scID );
				if ( ( ! $preview && ! is_null( $post ) && $post->post_type === TLPPortfolio()->getScPostType() ) || ( $preview && TLPPortfolio()->verifyNonce() ) ) {
					$rand       = mt_rand();
					$layoutID   = "tlp-portfolio-container-" . $rand;
					$arg        = array();
					$query_args = array(
						'post_type'   => TLPPortfolio()->post_type,
						'post_status' => 'publish',
					);

					if ( $preview ) {
						$error         = false;
						$scMeta        = $_REQUEST;
						$layout        = isset( $scMeta['pfp_layout'] ) && ! empty( $scMeta['pfp_layout'] ) ? $scMeta['pfp_layout'] : 'layout1';
						$dCol          = isset( $scMeta['pfp_desktop_column'] ) && ! empty( $scMeta['pfp_desktop_column'] ) ? absint( $scMeta['pfp_desktop_column'] ) : 3;
						$tCol          = isset( $scMeta['pfp_tab_column'] ) && ! empty( $scMeta['pfp_tab_column'] ) ? absint( $scMeta['pfp_tab_column'] ) : 2;
						$mCol          = isset( $scMeta['pfp_mobile_column'] ) && ! empty( $scMeta['pfp_mobile_column'] ) ? absint( $scMeta['pfp_mobile_column'] ) : 1;
						$imgSize       = isset( $scMeta['pfp_image_size'] ) && ! empty( $scMeta['pfp_image_size'] ) ? $scMeta['pfp_image_size'] : "medium";
						$customImgSize = isset( $scMeta['pfp_custom_image_size'] ) && ! empty( $scMeta['pfp_custom_image_size'] ) ? $scMeta['pfp_custom_image_size'] : array();
						$excerpt_limit = isset( $scMeta['pfp_excerpt_limit'] ) && ! empty( $scMeta['pfp_excerpt_limit'] ) ? absint( $scMeta['pfp_excerpt_limit'] ) : 0;
						$disable_image = isset( $scMeta['pfp_disable_image'] ) && ! empty( $scMeta['pfp_disable_image'] ) ? true : false;

						$post__in     = isset( $scMeta['pfp_post__in'] ) && ! empty( $scMeta['pfp_post__in'] ) ? trim( $scMeta['pfp_post__in'] ) : null;
						$post__not_in = isset( $scMeta['pfp_post__not_in'] ) && ! empty( $scMeta['pfp_post__not_in'] ) ? trim( $scMeta['pfp_post__not_in'] ) : null;
						$limit        = $query_args['posts_per_page'] = ! isset( $scMeta['pfp_limit'] ) || ( isset( $scMeta['pfp_limit'] ) && ( empty( $scMeta['pfp_limit'] ) || $scMeta['pfp_limit'] === '-1' ) ) ? 10000000 : (int) $scMeta['pfp_limit'];
						$pagination   = isset( $scMeta['pfp_pagination'] ) && ! empty( $scMeta['pfp_pagination'] ) ? true : false;

						$cats        = isset( $scMeta['pfp_categories'] ) && ! empty( $scMeta['pfp_categories'] ) ? array_filter( $scMeta['pfp_categories'] ) : array();
						$tags        = isset( $scMeta['pfp_tags'] ) && ! empty( $scMeta['pfp_tags'] ) ? array_filter( $scMeta['pfp_tags'] ) : array();
						$relation    = isset( $scFMeta['pfp_taxonomy_relation'] ) && ! empty( $scFMeta['pfp_taxonomy_relation'] ) ? $scFMeta['pfp_taxonomy_relation'] : "AND";
						$order_by    = isset( $scMeta['pfp_order_by'] ) && ! empty( $scMeta['pfp_order_by'] ) ? $scMeta['pfp_order_by'] : null;
						$order       = isset( $scMeta['pfp_order'] ) && ! empty( $scMeta['pfp_order'] ) ? $scMeta['pfp_order'] : null;
						$parentClass = isset( $scMeta['pfp_parent_class'] ) && ! empty( $scMeta['pfp_parent_class'] ) ? trim( $scMeta['pfp_parent_class'] ) : null;

						$arg['link']        = isset( $scMeta['pfp_detail_page_link'] ) && ! empty( $scMeta['pfp_detail_page_link'] );
						$arg['link_type']   = isset( $scMeta['pfp_detail_page_link_type'] ) && ! empty( $scMeta['pfp_detail_page_link_type'] ) ? $scMeta['pfp_detail_page_link_type'] : 'inner_link';
						$arg['link_target'] = $arg['link_type'] == 'external_link' && isset( $scMeta['pfp_link_target'] ) && $scMeta['pfp_link_target'] == '_blank' ? '_blank' : null;

						$disable_equal_height = isset( $scMeta['pfp_disable_equal_height'] ) && ! empty( $scMeta['pfp_disable_equal_height'] );

					} else {
						$scMeta        = get_post_meta( $scID );
						$layout        = isset( $scMeta['pfp_layout'][0] ) && ! empty( $scMeta['pfp_layout'][0] ) ? $scMeta['pfp_layout'][0] : 'layout1';
						$dCol          = isset( $scMeta['pfp_desktop_column'][0] ) && ! empty( $scMeta['pfp_desktop_column'][0] ) ? absint( $scMeta['pfp_desktop_column'][0] ) : 3;
						$tCol          = isset( $scMeta['pfp_tab_column'][0] ) && ! empty( $scMeta['pfp_tab_column'][0] ) ? absint( $scMeta['pfp_tab_column'][0] ) : 2;
						$mCol          = isset( $scMeta['pfp_mobile_column'][0] ) && ! empty( $scMeta['pfp_mobile_column'][0] ) ? absint( $scMeta['pfp_mobile_column'][0] ) : 1;
						$imgSize       = isset( $scMeta['pfp_image_size'][0] ) && ! empty( $scMeta['pfp_image_size'][0] ) ? $scMeta['pfp_image_size'][0] : "medium";
						$customImgSize = isset( $scMeta['pfp_custom_image_size'][0] ) && ! empty( $scMeta['pfp_custom_image_size'][0] ) ? $scMeta['pfp_custom_image_size'][0] : array();
						$excerpt_limit = isset( $scMeta['pfp_excerpt_limit'][0] ) && ! empty( $scMeta['pfp_excerpt_limit'][0] ) ? absint( $scMeta['pfp_excerpt_limit'][0] ) : 0;
						$disable_image = isset( $scMeta['pfp_disable_image'][0] ) && ! empty( $scMeta['pfp_disable_image'][0] ) ? true : false;

						$post__in     = isset( $scMeta['pfp_post__in'][0] ) && ! empty( $scMeta['pfp_post__in'][0] ) ? trim( $scMeta['pfp_post__in'][0] ) : null;
						$post__not_in = isset( $scMeta['pfp_post__not_in'][0] ) && ! empty( $scMeta['pfp_post__not_in'][0] ) ? trim( $scMeta['pfp_post__not_in'][0] ) : null;
						$limit        = $query_args['posts_per_page'] = ! isset( $scMeta['pfp_limit'][0] ) || ( isset( $scMeta['pfp_limit'][0] ) && ( empty( $scMeta['pfp_limit'][0] ) || $scMeta['pfp_limit'][0] === '-1' ) ) ? 10000000 : (int) $scMeta['pfp_limit'][0];
						$pagination   = isset( $scMeta['pfp_pagination'][0] ) && ! empty( $scMeta['pfp_pagination'][0] ) ? true : false;

						$cats        = isset( $scMeta['pfp_categories'] ) && ! empty( $scMeta['pfp_categories'] ) ? array_filter( $scMeta['pfp_categories'] ) : array();
						$tags        = isset( $scMeta['pfp_tags'] ) && ! empty( $scMeta['pfp_tags'] ) ? array_filter( $scMeta['pfp_tags'] ) : array();
						$relation    = isset( $scFMeta['pfp_taxonomy_relation'][0] ) && ! empty( $scFMeta['pfp_taxonomy_relation'][0] ) ? $scFMeta['pfp_taxonomy_relation'][0] : "AND";
						$order_by    = isset( $scMeta['pfp_order_by'][0] ) && ! empty( $scMeta['pfp_order_by'][0] ) ? $scMeta['pfp_order_by'][0] : null;
						$order       = isset( $scMeta['pfp_order'][0] ) && ! empty( $scMeta['pfp_order'][0] ) ? $scMeta['pfp_order'][0] : null;
						$parentClass = isset( $scMeta['pfp_parent_class'][0] ) && ! empty( $scMeta['pfp_parent_class'][0] ) ? trim( $scMeta['pfp_parent_class'][0] ) : null;

						$arg['link']          = isset( $scMeta['pfp_detail_page_link'][0] ) && ! empty( $scMeta['pfp_detail_page_link'][0] );
						$arg['link_type']     = isset( $scMeta['pfp_detail_page_link_type'][0] ) && ! empty( $scMeta['pfp_detail_page_link_type'][0] ) ? $scMeta['pfp_detail_page_link_type'][0] : 'inner_link';
						$arg['link_target']   = $arg['link_type'] == 'external_link' && isset( $scMeta['pfp_link_target'][0] ) && $scMeta['pfp_link_target'][0] == '_blank' ? '_blank' : null;
						$disable_equal_height = isset( $scMeta['pfp_disable_equal_height'][0] ) && ! empty( $scMeta['pfp_disable_equal_height'][0] );
					}

					if ( ! in_array( $layout, array_keys( TLPPortfolio()->scLayouts() ) ) ) {
						$layout = 'layout1';
					}

					if ( ! in_array( $dCol, array_keys( TLPPortfolio()->scColumns() ) ) ) {
						$dCol = 3;
					}
					if ( ! in_array( $tCol, array_keys( TLPPortfolio()->scColumns() ) ) ) {
						$tCol = 2;
					}
					if ( ! in_array( $mCol, array_keys( TLPPortfolio()->scColumns() ) ) ) {
						$mCol = 1;
					}

					$isIsotope  = preg_match( '/isotope/', $layout );
					$isCarousel = preg_match( '/carousel/', $layout );
					$isLayout   = preg_match( '/layout/', $layout );

					/* post__in */
					if ( $post__in ) {
						$query_args['post__in'] = explode( ',', $post__in );
					}
					/* post__not_in */
					if ( $post__not_in ) {
						$query_args['post__not_in'] = explode( ',', $post__not_in );
					}

					/* LIMIT */
					if ( $pagination ) {
						$posts_per_page = ( isset( $scMeta['pfp_posts_per_page'][0] ) ? intval( $scMeta['pfp_posts_per_page'][0] ) : $limit );
						if ( $posts_per_page > $limit ) {
							$posts_per_page = $limit;
						}
						// Set 'posts_per_page' parameter
						$query_args['posts_per_page'] = $posts_per_page;

						$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

						$offset              = $posts_per_page * ( (int) $paged - 1 );
						$query_args['paged'] = $paged;

						// Update posts_per_page
						if ( intval( $query_args['posts_per_page'] ) > $limit - $offset ) {
							$query_args['posts_per_page'] = $limit - $offset;
						}

					}
					if ( $isCarousel ) {
						$args['posts_per_page'] = $limit;
					}

					$taxQ = array();
					if ( is_array( $cats ) && ! empty( $cats ) ) {
						$taxQ[] = array(
							'taxonomy' => TLPPortfolio()->taxonomies['category'],
							'field'    => 'term_id',
							'terms'    => $cats,
						);
					}
					if ( is_array( $tags ) && ! empty( $tags ) ) {
						$taxQ[] = array(
							'taxonomy' => TLPPortfolio()->taxonomies['tag'],
							'field'    => 'term_id',
							'terms'    => $tags,
						);
					}
					if ( ! empty( $taxQ ) ) {
						if ( count( $taxQ ) > 1 ) {
							$taxQ['relation'] = $relation;
						}
						$query_args['tax_query'] = $taxQ;
					}

					if ( $order ) {
						$query_args['order'] = $order;
					}
					if ( $order_by ) {
						$query_args['orderby'] = $order_by;
					}

					// Validation
					$containerDataAttr = " data-layout='{$layout}' data-desktop-col='{$dCol}'  data-tab-col='{$tCol}'  data-mobile-col='{$mCol}'";
					$old_dCol          = $dCol;
					$dCol              = round( 12 / $dCol );
					$tCol              = round( 12 / $tCol );
					$mCol              = round( 12 / $mCol );
					if ( $isCarousel ) {
						$dCol = $tCol = $mCol = 12;
					}

					$arg['grid'] = sprintf( 'tlp-col-lg-%d tlp-col-md-%d tlp-col-sm-%d tlp-col-xs-12 tlp-single-item%s%s%s%s', $dCol, $tCol, $mCol,
						$isIsotope ? ' tlp-isotope-item' : null,
						$isCarousel ? ' tlp-carousel-item' : null,
						$isLayout ? ' tlp-grid-item' : null,
						! $isIsotope && ! $disable_equal_height ? ' tlp-equal-height' : null
					);


					if ( $old_dCol == 2 ) {
						$arg['image_area']   = "tlp-col-lg-5 tlp-col-md-5 tlp-col-sm-6 tlp-col-xs-12 ";
						$arg['content_area'] = "tlp-col-lg-7 tlp-col-md-7 tlp-col-sm-6 tlp-col-xs-12 ";
					} else {
						$arg['image_area']   = "tlp-col-lg-3 tlp-col-md-3 tlp-col-sm-6 tlp-col-xs-12 ";
						$arg['content_area'] = "tlp-col-lg-9 tlp-col-md-9 tlp-col-sm-6 tlp-col-xs-12 ";
					}

					$portfolioQuery = new WP_Query( apply_filters( 'tlp_portfolio_sc_query_args', $query_args ) );
					$class          = array(
						'rt-container-fluid',
						'tlp-portfolio',
						'tlp-portfolio-container'
					);
					if ( $parentClass ) {
						$class[] = $parentClass;
					}
					if ( $isIsotope ) {
						$class[] = 'is-isotope';
					}
					if ( $isCarousel ) {
						$class[] = 'is-carousel';
					}
					$html .= $this->customStyle( $layoutID, $scMeta, true, $preview );
					if ( $portfolioQuery->have_posts() ) {
						$html .= sprintf( '<div class="%s" id="%s"><div class="rt-row %s">', implode( ' ', $class ), $layoutID, $layout );
						if ( $isIsotope ) {
							$terms = get_terms( apply_filters( 'tlp_portfolio_sc_isotope_button_args', array(
								'taxonomy'   => TLPPortfolio()->taxonomies['category'],
								'orderby'    => 'name',
								'order'      => 'ASC',
								'hide_empty' => false,
							) ) );

							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
								$html .= sprintf( '<div class="tlp-portfolio-isotope-button button-group filter-button-group option-set"><button data-filter="*" class="selected">%s</button>', __( "Show all", "tlp-portfolio" ) );
								foreach ( $terms as $term ) {
									if ( ! empty( $cat_ids ) ) {
										if ( in_array( $term->term_id, $cat_ids ) ) {
											$html .= "<button data-filter='.{$term->slug}'>" . $term->name . "</button>";
										}
									} else {
										$html .= "<button data-filter='.{$term->slug}'>" . $term->name . "</button>";
									}
								}
								$html .= '</div>';
							}
							$html .= '<div class="tlp-portfolio-isotope">';
						}

						while ( $portfolioQuery->have_posts() ) :
							$portfolioQuery->the_post();

							$arg['title']       = get_the_title();
							$arg['iID']         = $iID = get_the_ID();
							$arg['item_link']   = get_permalink();
							$arg['project_url'] = get_post_meta( $iID, 'project_url', true );
							$arg['pLink']       = get_permalink();
							if ( $arg['link_type'] == "external_link" && $arg['project_url'] ) {
								$arg['item_link'] = $arg['project_url'];
							}
							$short_d        = get_post_meta( $iID, 'short_description', true );
							$arg['short_d'] = TLPPortfolio()->get_short_description( $short_d, $excerpt_limit );
                            if ( $isIsotope ) {
                                $termAs     = wp_get_post_terms( $iID, TLPPortfolio()->taxonomies['category'], array( "fields" => "all" ) );
                                $isoFilter = null;
                                if ( ! empty( $termAs ) ) {
                                    foreach ( $termAs as $term ) {
                                        $isoFilter .= " " . "iso_" . $term->term_id;
                                        $isoFilter .= " " . $term->slug;
                                    }
                                }
                                $arg['isoFilter'] = $isoFilter;
                            }

							if ( $disable_image ) {
								$arg['content_area'] = "tlp-col-md-12";
								$arg['imgFull']      = $arg['img'] = null;
							} else {
								if ( has_post_thumbnail() ) {
									$arg['img']     = TLPPortfolio()->getFeatureImageSrc( $iID, $imgSize, $customImgSize );
									$imageFull      = wp_get_attachment_image_src( get_post_thumbnail_id( $iID ), 'full' );
									$arg['imgFull'] = $imageFull[0];
								} else {
									$arg['img'] = $arg['imgFull'] = null;
								}
								$arg['imgFull'] = ! $arg['imgFull'] && $arg['img'] ? $arg['img'] : $arg['imgFull'];
							}
							$html .= TLPPortfolio()->render( 'layouts/' . $layout, $arg, true );
						endwhile;
						if ( $isIsotope ) {
							$html .= ' </div>'; // end tlp-team-isotope
						}
						$html .= '</div>'; // end row
						if ( $pagination && ! $isCarousel ) {
							$html .= TLPPortfolio()->pagination( $portfolioQuery, $query_args, $scMeta );
						}
						$html .= '</div>'; // end container
						add_action( 'wp_footer', array( $this, 'load_scripts' ) );
						wp_reset_postdata();

					} else {
						$html .= sprintf( '<p>%s</p>', __( "No portfolio found", 'tlp-portfolio' ) );
					}
				} else {
					if ( $preview ) {
						$msg = __( 'Session Error !!', 'tlp-portfolio' );
					} else {
						$html .= "<p>" . __( "No shortCode found", 'tlp-portfolio' ) . "</p>";
					}
				}
				if ( $preview ) {
					wp_send_json( array(
						'error' => $error,
						'msg'   => $msg,
						'data'  => $html
					) );
				} else {
					return $html;
				}
			} else {
				return $this->get_team_old_layout( $atts );
			}

		}


		function templateOne( $itemArg ) {
			extract( $itemArg );
			$html = null;
			$html .= "<div class='tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 tlp-single-item tlp-grid-item tlp-equal-height'>";
			$html .= '<div class="tlp-portfolio-item">';
			if ( $img ) {
				$html .= '<div class="tlp-portfolio-thum tlp-item">';
				$html .= '<img class="img-responsive" src="' . $img . '" alt="' . $title . '" />';
				$html .= '<div class="tlp-overlay">';
				$html .= '<p class="link-icon">';
				$html .= '<a class="tlp-zoom" href="' . $imgFull . '"><i class="fa fa-search-plus"></i></a>';
				$html .= '<a target="_blank" href="' . $plink . '"><i class="fa fa-external-link"></i></a>';
				$html .= '</p>';
				$html .= '</div>';
				$html .= '</div>';
			}
			$html .= sprintf( '<div class="tlp-content"><di class="tlp-content-holder"><h3><a href="%s">%s </a></h3><div class="tlp-portfolio-sd">%s </div></di ></div > ',
				$plink, $title,
				$limit > 0 ? substr( $short_d, 0, $limit ) : wp_kses_post( html_entity_decode( $short_d ) ) //
			);
			$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

		function templateTwo( $itemArg ) {
			extract( $itemArg );
			$html = null;
			$html .= "<div class='tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 tlp-single-item tlp-grid-item tlp-equal-height'>";
			$html .= '<div class="tlp-portfolio-item rt-row">';
			if ( $img ) {
				$html .= '<div class="tlp-portfolio-thum tlp-item ' . $image_area . '">';
				$html .= '<figure>';
				$html .= '<img class="img-responsive" src="' . $img . '" alt="' . $title . '" />';
				$html .= '</figure>';
				$html .= '<div class="tlp-overlay">';
				$html .= '<ul class="link-icon">';
				$html .= '<a class="tlp-zoom" href="' . $imgFull . '"><i class="fa fa-search-plus" ></i></a>';
				$html .= '<a target="_blank" href="' . $plink . '"><i class="fa fa-external-link" ></i></a>';
				$html .= '</ul>';
				$html .= '</div>';
				$html .= '</div>';
			}
			$html .= sprintf( '<div class="tlp-content2 %s" ><div class="tlp-content-holder"><h3><a href="%s">%s </a></h3><div class="tlp-portfolio-sd">%s</div></div></div>',
				$content_area,
				$plink,
				$title,
				$limit > 0 ? substr( $short_d, 0, $limit ) : html_entity_decode( $short_d )
			);
			$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

		function templateThree( $itemArg ) {
			extract( $itemArg );
			$html = null;
			$html .= "<div class='tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 tlp-single-item tlp-grid-item tlp-equal-height'>";

			$html .= '<div class="tlp-portfolio-item">';
			if ( $img ) {
				$html .= '<div class="tlp-portfolio-thum tlp-item">';
				$html .= '<figure>';
				$html .= '<img class="img-responsive" src="' . $img . '" alt="' . $title . '" />';
				$html .= '</figure>';
				$html .= '<div class="tlp-overlay">';
				$html .= '<p class="link-icon">';
				$html .= '<a class="tlp-zoom" href="' . $imgFull . '"><i class="fa fa-search-plus"></i></a>';
				$html .= '<a target="_blank" href="' . $plink . '"><i class="fa fa-external-link"></i></a>';
				$html .= '</p>';
				$html .= '</div>';
				$html .= '</div>';
			}
			$html .= sprintf( '<div class="tlp-content"><div class="tlp-content-holder"><h3><a href="%s">%s</a></h3></div></div>',
				$plink,
				$title
			);
			$html .= '</div>';

			$html .= '</div>';

			return $html;
		}

		function layoutIsotope( $itemArg ) {
			extract( $itemArg );
			$html = null;
			$html .= "<div class='tlp-item tlp-single-item tlp-isotope-item tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 {$catClass}'>";
			$html .= '<div class="tlp-portfolio-item">';
			if ( $img ) {
				$html .= '<div class="tlp-portfolio-thum tlp-item">';
				$html .= '<img class="img-responsive" src="' . $img . '" alt="' . $title . '" />';
				$html .= '<div class="tlp-overlay">';
				$html .= '<p class="link-icon">';
				$html .= '<a class="tlp-zoom" href="' . $imgFull . '"><i class="fa fa-search-plus"></i></a>';
				$html .= '<a target="_blank" href="' . $plink . '"><i class="fa fa-external-link"></i></a>';
				$html .= '</p>';
				$html .= '</div>';
				$html .= '</div>';
			}

			$html .= sprintf( '<div class="tlp-content" ><div class="tlp-content-holder"><h3><a href="%s">%s</a></h3><div class="tlp-portfolio-sd">%s</div></div></div>',
				$plink,
				$title,
				$limit > 0 ? substr( $short_d, 0, $limit ) : html_entity_decode( $short_d )
			);
			$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

		private function customStyle( $layoutID, $scMeta, $new_layout = false, $preview = false ) {
			$style = null;
			if ( $new_layout ) {
				if ( $preview ) {
					$primaryColor        = isset( $scMeta['pfp_primary_color'] ) && ! empty( $scMeta['pfp_primary_color'] ) ? $scMeta['pfp_primary_color'] : null;
					$overlayColor        = isset( $scMeta['pfp_overlay_color'] ) && ! empty( $scMeta['pfp_overlay_color'] ) ? $scMeta['pfp_overlay_color'] : null;
					$buttonBgColor       = isset( $scMeta['pfp_button_bg_color'] ) && ! empty( $scMeta['pfp_button_bg_color'] ) ? $scMeta['pfp_button_bg_color'] : null;
					$buttonTxtColor      = isset( $scMeta['pfp_button_text_color'] ) && ! empty( $scMeta['pfp_button_text_color'] ) ? $scMeta['pfp_button_text_color'] : null;
					$buttonHoverBgColor  = isset( $scMeta['pfp_button_hover_bg_color'] ) && ! empty( $scMeta['pfp_button_hover_bg_color'] ) ? $scMeta['pfp_button_hover_bg_color'] : null;
					$buttonActiveBgColor = isset( $scMeta['pfp_button_active_bg_color'] ) && ! empty( $scMeta['pfp_button_active_bg_color'] ) ? $scMeta['pfp_button_active_bg_color'] : null;
					$name                = isset( $scMeta['pfp_name_style'] ) && ! empty( $scMeta['pfp_name_style'] ) ? $scMeta['pfp_name_style'] : array();
					$short_desc          = isset( $scMeta['pfp_short_description_style'] ) && ! empty( $scMeta['pfp_short_description_style'] ) ? $scMeta['pfp_short_description_style'] : array();

				} else {
					$primaryColor        = isset( $scMeta['pfp_primary_color'][0] ) && ! empty( $scMeta['pfp_primary_color'][0] ) ? $scMeta['pfp_primary_color'][0] : null;
					$overlayColor        = isset( $scMeta['pfp_overlay_color'][0] ) && ! empty( $scMeta['pfp_overlay_color'][0] ) ? $scMeta['pfp_overlay_color'][0] : null;
					$buttonBgColor       = isset( $scMeta['pfp_button_bg_color'][0] ) && ! empty( $scMeta['pfp_button_bg_color'][0] ) ? $scMeta['pfp_button_bg_color'][0] : null;
					$buttonTxtColor      = isset( $scMeta['pfp_button_text_color'][0] ) && ! empty( $scMeta['pfp_button_text_color'][0] ) ? $scMeta['pfp_button_text_color'][0] : null;
					$buttonHoverBgColor  = isset( $scMeta['pfp_button_hover_bg_color'][0] ) && ! empty( $scMeta['pfp_button_hover_bg_color'][0] ) ? $scMeta['pfp_button_hover_bg_color'][0] : null;
					$buttonActiveBgColor = isset( $scMeta['pfp_button_active_bg_color'][0] ) && ! empty( $scMeta['pfp_button_active_bg_color'][0] ) ? $scMeta['pfp_button_active_bg_color'][0] : null;
					$name                = isset( $scMeta['pfp_name_style'][0] ) && ! empty( $scMeta['pfp_name_style'][0] ) ? @unserialize( $scMeta['pfp_name_style'][0] ) : array();
					$short_desc          = isset( $scMeta['pfp_short_description_style'][0] ) && ! empty( $scMeta['pfp_short_description_style'][0] ) ? @unserialize( $scMeta['pfp_short_description_style'][0] ) : array();
				}

				if ( $primaryColor ) {
					$style .= "#{$layoutID} .tlp-pagination ul.page-numbers li .page-numbers {";
					$style .= "background:" . $primaryColor . ";";
					$style .= "}";
					$style .= "#{$layoutID} .tlp-portfolio-item .tlp-content{";
					$style .= "background:" . $primaryColor . ";";
					$style .= "}";
					$style .= "#{$layoutID} .tlp-portfolio-item .tlp-content .tlp-content-holder{padding:15px}";
				}
				if ( $overlayColor ) {
					$style .= "#{$layoutID} .tlp-overlay {";
					$style .= "background:" . $overlayColor . ";";
					$style .= "}";
				}

				/* Button background color */
				if ( $buttonBgColor ) {
					$style .= "#{$layoutID} .tlp-portfolio-isotope-button button, 
                                #{$layoutID} .owl-theme .owl-nav [class*=owl-], 
			                    #{$layoutID} .owl-theme .owl-dots .owl-dot span,
			                    #{$layoutID} .tlp-pagination li span, 
			                    #{$layoutID} .tlp-pagination li a {";
					$style .= "background: {$buttonBgColor};";
					$style .= ( $buttonTxtColor ? "color: {$buttonTxtColor}" : null );
					$style .= "}";
				}

				/* Button hover background color */
				if ( $buttonHoverBgColor ) {
					$style .= "#{$layoutID} .tlp-portfolio-isotope-button button:hover, 
                                #{$layoutID} .owl-theme .owl-nav [class*=owl-]:hover, 
                                #{$layoutID} .tlp-pagination li span:hover, 
                                #{$layoutID} .tlp-pagination li a:hover {";
					$style .= "background: {$buttonHoverBgColor};";
					$style .= "}";
				}

				/* Button Active background color */
				if ( $buttonActiveBgColor ) {
					$style .= "#{$layoutID} .tlp-portfolio-isotope-button button.selected, 
                                #{$layoutID} .owl-theme .owl-dots .owl-dot.active span, 
                                #{$layoutID} .tlp-pagination li.active span {";
					$style .= "background: {$buttonActiveBgColor};";
					$style .= "}";
				}

				// Name
				if ( is_array( $name ) && ! empty( $name ) ) {
					$style .= "#{$layoutID} .tlp-portfolio-item h3 a, #{$layoutID} .tlp-portfolio-item h3 {";
					$style .= isset( $name['color'] ) && ! empty( $name['color'] ) ? "color:" . $name['color'] . ";" : null;
					$style .= isset( $name['align'] ) && ! empty( $name['align'] ) ? "text-align:" . $name['align'] . " !important;" : null;
					$style .= isset( $name['weight'] ) && ! empty( $name['weight'] ) ? "font-weight:" . $name['weight'] . ";" : null;
					$style .= isset( $name['size'] ) && ! empty( $name['size'] ) ? "font-size:" . $name['size'] . "px;" : null;
					$style .= "}";
				}

				// Short Description
				if ( is_array( $short_desc ) && ! empty( $short_desc ) ) {
					$style .= "#{$layoutID} .tlp-portfolio-item .tlp-portfolio-sd {";
					$style .= isset( $name['color'] ) && ! empty( $short_desc['color'] ) ? "color:" . $short_desc['color'] . ";" : null;
					$style .= isset( $name['size'] ) && ! empty( $short_desc['size'] ) ? "font-size:" . $short_desc['size'] . "px;" : null;
					$style .= isset( $name['weight'] ) && ! empty( $short_desc['weight'] ) ? "font-weight:" . $short_desc['weight'] . ";" : null;
					$style .= isset( $name['align'] ) && ! empty( $short_desc['align'] ) ? "text-align:" . $short_desc['align'] . ";" : null;
					$style .= "}";
				}
			} else {

				$title_color     = ! empty( $scMeta['title-color'] ) ? $scMeta['title-color'] : null;
				$title_size      = ! empty( $scMeta['title-font-size'] ) ? $scMeta['title-font-size'] : null;
				$title_weight    = ! empty( $scMeta['title-font-weight'] ) ? $scMeta['title-font-weight'] : null;
				$title_alignment = ! empty( $scMeta['title-alignment'] ) ? $scMeta['title-alignment'] : null;

				$short_desc_color     = ! empty( $scMeta['short-desc-color'] ) ? $scMeta['short-desc-color'] : null;
				$short_desc_size      = ! empty( $scMeta['short-desc-font-size'] ) ? $scMeta['short-desc-font-size'] : null;
				$short_desc_weight    = ! empty( $scMeta['short-desc-font-weight'] ) ? $scMeta['short-desc-font-weight'] : null;
				$short_desc_alignment = ! empty( $scMeta['short-desc-alignment'] ) ? $scMeta['short-desc-alignment'] : null;
				if ( $title_color ) {
					$style .= "#{$layoutID}.tlp-portfolio h3,
							#{$layoutID}.tlp-portfolio h3 a{ color: {$title_color};}";
				}
				if ( $title_size ) {
					$style .= "#{$layoutID}.tlp-portfolio h3,
							#{$layoutID}.tlp-portfolio h3 a{ font-size: {$title_size}px;}";
				}
				if ( $title_weight ) {
					$style .= "#{$layoutID}.tlp-portfolio h3,
							#{$layoutID}.tlp-portfolio h3 a{ font-weight: {$title_weight};}";
				}
				if ( $title_alignment ) {
					$style .= "#{$layoutID}.tlp-portfolio h3{ text-align: {$title_alignment};}";
				}
				// Short description
				if ( $short_desc_color || $short_desc_size || $short_desc_weight || $short_desc_alignment ) {
					$style .= "#{$layoutID}.tlp-portfolio .tlp-content-holder p{";
					if ( $short_desc_color ) {
						$style .= "color: {$short_desc_color};";
					}
					if ( $short_desc_size ) {
						$style .= "font-size: {$short_desc_size}px;";
					}
					if ( $short_desc_weight ) {
						$style .= "font-weight: {$short_desc_weight};";
					}
					if ( $short_desc_alignment ) {
						$style .= "text-align: {$short_desc_alignment};";
					}
					$style .= "}";
				}
			}
			if ( ! empty( $style ) ) {
				$style = "<style>{$style}</style>";
			}

			return $style;

		}

		private function get_team_old_layout( $atts ) {

			$atts          = shortcode_atts( array(
				'orderby'                => 'date',
				'order'                  => 'DESC',
				'image'                  => 'true',
				'number'                 => - 1,
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
			), $atts, 'tlpportfolio' );
			$atts['image'] = 'true' === $atts['image'];
			$limit         = $atts['letter-limit'] ? absint( $atts['letter-limit'] ) : 0;
			if ( ! in_array( $atts['col'], array_keys( TLPPortfolio()->scColumns() ) ) ) {
				$atts['col'] = 3;
			}
			if ( ! in_array( $atts['layout'], array_keys( TLPPortfolio()->oldScLayouts() ) ) ) {
				$atts['layout'] = 1;
			}
			$layout    = $atts['layout'] == 'isotope' ? 'isotope1' : 'layout' . $atts['layout'];
			$isIsotope = preg_match( '/isotope/', $layout );
			$isLayout  = preg_match( '/layout/', $layout );
			$grid      = $atts['col'] == 5 ? '24' : 12 / $atts['col'];
			if ( $atts['col'] == 2 ) {
				$image_area   = "tlp-col-lg-5 tlp-col-md-5 tlp-col-sm-6 tlp-col-xs-12 ";
				$content_area = "tlp-col-lg-7 tlp-col-md-7 tlp-col-sm-6 tlp-col-xs-12 ";
			} else {
				$image_area   = "tlp-col-lg-3 tlp-col-md-3 tlp-col-sm-6 tlp-col-xs-12 ";
				$content_area = "tlp-col-lg-9 tlp-col-md-9 tlp-col-sm-6 tlp-col-xs-12 ";
			}

			$html = null;
			$rand = rand( 1, 10 );
			$args = array(
				'post_type'      => TLPPortfolio()->post_type,
				'post_status'    => 'publish',
				'posts_per_page' => $atts['number'],
				'orderby'        => $atts['orderby'],
				'order'          => $atts['order']
			);
			if ( is_user_logged_in() && is_super_admin() ) {
				$args['post_status'] = array( 'publish', 'private' );
			}
			$cat_ids = array();
			if ( ! empty( $atts['cat'] ) ) {
				$cat_ids           = explode( ",", $atts['cat'] );
				$args['tax_query'] = array(
					array(
						'taxonomy' => TLPPortfolio()->taxonomies['category'],
						'field'    => 'term_id',
						'terms'    => $cat_ids,
						'operator' => 'IN'
					),
				);
			}
			$settings      = get_option( TLPPortfolio()->options['settings'] );
			$fImgSize      = ! empty( $settings['feature_img_size'] ) ? $settings['feature_img_size'] : TLPPortfolio()->options['tlp-portfolio-thumb'];
			$customImgSize = ! empty( $settings['rt_custom_img_size'] ) ? $settings['rt_custom_img_size'] : array();

			$portfolioQuery = new WP_Query( $args );
			$layoutID       = "tlp-portfolio-container-" . mt_rand();
			$class          = array(
				'rt-container-fluid',
				'tlp-portfolio',
				'tlp-portfolio-container'
			);
			if ( ! empty( $atts['class'] ) ) {
				$class[] = $atts['class'];
			}
			if ( $isIsotope ) {
				$class[] = 'is-isotope';
			}
			$class = implode( ' ', $class );
			$html  .= "<div class='" . esc_attr( $class ) . "' id='{$layoutID}'>";
			$html  .= $this->customStyle( $layoutID, $atts );
			$html  .= '<div class="rt-row ' . $layout . '">';
			if ( $portfolioQuery->have_posts() ) {
				if ( $isIsotope ) {
					$terms = get_terms( TLPPortfolio()->taxonomies['category'], array(
						'orderby'    => 'name',
						'order'      => 'ASC',
						'hide_empty' => false,
					) );
					$html  .= '<div class="tlp-portfolio-isotope-button button-group filter-button-group option-set">
											<button data-filter="*" class="selected">' . __( "Show all",
							"tlp-portfolio" ) . '</button>';
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						foreach ( $terms as $term ) {
							if ( ! empty( $cat_ids ) ) {
								if ( in_array( $term->term_id, $cat_ids ) ) {
									$html .= "<button data-filter=' .{
                            $term->slug}'>" . $term->name . "</button>";
								}
							} else {
								$html .= "<button data-filter=' .{$term->slug}'>" . $term->name . "</button>";
							}
						}
					}
					$html .= ' </div>';
					$html .= '<div class="tlp-portfolio-isotope">';
				}

				while ( $portfolioQuery->have_posts() ) : $portfolioQuery->the_post();

					$title       = get_the_title();
					$iID         = get_the_ID();
					$plink       = get_permalink();
					$short_d     = get_post_meta( $iID, 'short_description', true );
					$project_url = get_post_meta( $iID, 'project_url', true );
					$tools       = get_post_meta( $iID, 'tools', true );
					$categories  = get_the_term_list( $iID, TLPPortfolio()->taxonomies['category'], 'Category : ', ',' );
					$tags        = get_the_term_list( $iID, TLPPortfolio()->taxonomies['tag'], 'Tags : ', ',' );

					$catClass  = null;
					$catAs     = wp_get_post_terms( $iID, TLPPortfolio()->taxonomies['category'],
						array( "fields" => "all" ) );
					$deptClass = null;
					if ( ! empty( $catAs ) ) {
						foreach ( $catAs as $cat ) {
							$catClass .= " " . $cat->slug;
						}
					}
					$img     = null;
					$imgFull = null;
					if ( has_post_thumbnail() ) {
						$img = TLPPortfolio()->getFeatureImageSrc( $iID, $fImgSize, $customImgSize );;
						$imageFull = wp_get_attachment_image_src( get_post_thumbnail_id( $iID ), 'full' );
						$imgFull   = $imageFull[0];
					} else {
						$img = TLPPortfolio()->assetsUrl . 'images/demo.jpg';
					}
					if ( ! $imgFull ) {
						$imgFull = $img;
					}
					if ( ! $atts['image'] ) {
						$content_area = "tlp-col-md-12";
						$img          = null;
					}
					$itemArg                 = array();
					$itemArg['title']        = $title;
					$itemArg['plink']        = $project_url ? $project_url : $plink;
					$itemArg['img']          = $img;
					$itemArg['imgFull']      = $imgFull;
					$itemArg['short_d']      = $short_d;
					$itemArg['grid']         = $grid;
					$itemArg['rand']         = $rand;
					$itemArg['catClass']     = $catClass;
					$itemArg['limit']        = $limit;
					$itemArg['image_area']   = $image_area;
					$itemArg['content_area'] = $content_area;
					if ( $atts['layout'] ) {
						switch ( $layout ) {
							case 'layout1':
								$html .= $this->templateOne( $itemArg );
								break;

							case 'layout2':
								$html .= $this->templateTwo( $itemArg );
								break;

							case 'layout3':
								$html .= $this->templateThree( $itemArg );
								break;

							case 'isotope1':
								$html .= $this->layoutIsotope( $itemArg );
								break;

							default:
								# code...
								break;
						}
					}
				endwhile;
				wp_reset_postdata();
				if ( $isIsotope ) {
					$html .= ' </div>'; // end tlp-team-isotope
				}
				add_action( 'wp_footer', array( $this, 'load_scripts' ) );

			} else {
				$html .= "<p>No portfolio found</p>";
			}
			$html .= '</div>'; // end row
			$html .= '</div>'; // end container

			return $html;
		}
	}


endif;
