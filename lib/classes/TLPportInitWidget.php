<?php

if(!class_exists('TLPportInitWidget')):

	/**
	*
	*/
	class TLPportInitWidget
	{

		function __construct()
		{
			add_action( 'widgets_init', array($this, 'initWidget'));
		}


		function initWidget(){
			global $TLPportfolio;

			$TLPportfolio->loadWidget( $TLPportfolio->widgetsPath );
		}
	}


endif;
