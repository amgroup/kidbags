<?php
/***************************************************************************
 *
 * 	----------------------------------------------------------------------
 * 						DO NOT EDIT THIS FILE
 *	----------------------------------------------------------------------
 * 
 *  			Built by Elio Rivero. http://themify.me
 *  				Copyright (C) 2010 Themify
 *
 ***************************************************************************/

$theme_includes = apply_filters( 'themify_theme_includes',
	array(	'themify/themify-database.php',
			'themify/themify-utils.php',
			'themify/themify-config.php',
			'themify/themify-modules.php',
			'theme-options.php',
			'theme-modules.php',
			'theme-functions.php',
			'custom-modules.php',
			'custom-functions.php',
			'themify/themify-widgets.php' ));
			
foreach ( $theme_includes as $include ) { locate_template( $include, true ); }

/**********************************************************************************************************
 * 
 * Do not edit this file.
 * To add custom PHP functions to the theme, create a new 'custom-functions.php' file in the theme folder.
 * 
***********************************************************************************************************/
?>