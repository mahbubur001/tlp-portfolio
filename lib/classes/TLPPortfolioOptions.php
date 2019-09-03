<?php
if ( ! class_exists( 'TLPPortfolioOptions' ) ) :

	class TLPPortfolioOptions {

		function socialLink() {
			return array(
				'facebook' => 'Facebook',
				'twitter'  => 'Twitter',
				'linkedin' => 'LinkedIn'
			);
		}

		function scColumns() {
			return array(
				1 => "1 Column",
				2 => "2 Column",
				3 => "3 Column",
				4 => "4 Column",
				5 => "5 Column",
				6 => "6 Column",
			);
		}

		function scLayouts() {
			return array(
				1         => "Layout 1",
				2         => "Layout 2",
				3         => "Layout 3",
				'isotope' => "Isotope Layout",
			);
		}

		function scOrderBy() {
			return array(
				'menu_order' => "Menu Order",
				'title'      => "Name",
				'ID'         => "ID",
				'date'       => "Date"
			);
		}

		function scOrder() {
			return array(
				'ASC'  => "Ascending",
				'DESC' => "Descending"
			);
		}

		function owl_property() {
			return array(
				'auto_play'   => __( 'Auto Play', 'tlp-portfolio' ),
				'navigation'  => __( 'Navigation', 'tlp-portfolio' ),
				'pagination'  => __( 'Pagination', 'tlp-portfolio' ),
				'stop_hover'  => __( 'Stop Hover', 'tlp-portfolio' ),
				'responsive'  => __( 'Responsive', 'tlp-portfolio' ),
				'auto_height' => __( 'Auto Height', 'tlp-portfolio' ),
				'lazy_load'   => __( 'Lazy Load', 'tlp-portfolio' )
			);
		}

		function scFontSize() {
			$num = array();
			for ( $i = 10; $i <= 50; $i ++ ) {
				$num[ $i ] = $i . "px";
			}

			return $num;
		}

		function scAlignment() {
			return array(
				'left'    => "Left",
				'right'   => "Right",
				'center'  => "Center",
				'justify' => "Justify"
			);
		}

		function scTextWeight() {
			return array(
				'normal'  => "Normal",
				'bold'    => "Bold",
				'bolder'  => "Bolder",
				'lighter' => "Lighter",
				'inherit' => "Inherit",
				'initial' => "Initial",
				'unset'   => "Unset",
				100       => '100',
				200       => '200',
				300       => '300',
				400       => '400',
				500       => '500',
				600       => '600',
				700       => '700',
				800       => '800',
				900       => '900',
			);
		}

	}
endif;
