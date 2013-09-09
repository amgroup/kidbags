<?php

/*
To add custom PHP functions to the theme, create a new 'custom-functions.php' file in the theme folder. 
They will be added to the theme automatically.
*/

function themify_theme_default_layout_condition($condition) {
	return $condition || is_shop();
}
function themify_theme_default_layout($class) {
	if(is_shop()){
		
	}
	return $class;
}
//add_filter('themify_default_layout_condition', 'themify_theme_default_layout_condition');
//add_filter('themify_default_layout', 'themify_theme_default_layout');

function themify_theme_default_post_layout_condition($condition) {
	return $condition || is_shop();
};
function themify_theme_default_post_layout($class) {
	if(is_shop()){
		$class = '' != themify_get('setting-products_layout')? themify_get('setting-products_layout') : 'grid4';
	}
	return $class;
};
add_filter('themify_default_post_layout_condition', 'themify_theme_default_post_layout_condition');
add_filter('themify_default_post_layout', 'themify_theme_default_post_layout');

/* 	Enqueue (and dequeue) Stylesheets and Scripts
/***************************************************************************/

function themify_theme_enqueue_scripts(){
	
	///////////////////
	//Enqueue styles
	///////////////////
	
	//PrettyPhoto stylesheet
	wp_enqueue_style( 'pretty-photo', get_template_directory_uri() . '/prettyPhoto.css');
	
	//jScrollPane stylesheet
	wp_enqueue_style( 'jscrollpane', get_template_directory_uri() . '/jquery.jscrollpane.css');
	
	//Google Web Fonts
	wp_enqueue_style( 'google-fonts', get_template_directory_uri() . '/fonts/oswald.css');
	
	//Themify base stylesheet
	wp_enqueue_style( 'theme-style', get_bloginfo('stylesheet_url'), array(), wp_get_theme()->display('Version'));

	//Themify shop stylesheet
	wp_enqueue_style( 'themify-shop', get_template_directory_uri() . '/shop.css');

	//Themify Media Queries stylesheet
	wp_enqueue_style( 'themify-media-queries', get_template_directory_uri() . '/media-queries.css');
	
	//Custom stylesheet
	if(is_file(TEMPLATEPATH . "/custom_style.css"))
		wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/custom_style.css');


	//jQuery UI accordion stylesheet
	wp_enqueue_style( 'jquery-ui-accordion', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
    
	///////////////////
	//Enqueue scripts
	///////////////////

	//isotope, used to re-arrange blocks
	wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/jquery.isotope.js', array('jquery'), false, true );

	//creates infinite scroll
	wp_enqueue_script( 'infinitescroll', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js', array('jquery'), false, true );

	//prettyPhoto script
	wp_enqueue_script( 'pretty-photo', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array('jquery'), false, true );

	//Slider script
	wp_enqueue_script( 'jquery-slider', get_template_directory_uri() . '/js/jquery.slider.js', array('jquery'), false, true );

    //jQuery UI accordion script
    wp_enqueue_script( 'jquery-ui-accordion', get_template_directory_uri() . '/js/jquery/ui/jquery.ui.accordion.min.js', array('jquery'), false, true );
	
	//Carousel script
	wp_enqueue_script( 'carousel', get_template_directory_uri() . '/js/carousel.min.js', array('jquery'), false, true );
	
	//Mouse wheel support for jScrollPane
	wp_enqueue_script( 'mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel.min.js', array('jquery'), false, true );

	//jScrollPane
	wp_enqueue_script( 'jscrollpane', get_template_directory_uri() . '/js/jquery.jscrollpane.min.js', array('jquery'), false, true );
	
	//Themify internal script
	wp_enqueue_script( 'theme-script',	get_template_directory_uri() . '/js/themify.script.js', array('jquery', 'jquery-effects-core'), false, true );

	$overlay_args = array(
		'selector' => "a[rel^='prettyPhoto']",
		'theme' =>'pp_default',
		'social_tools' => false,
		'allow_resize' => true,
		'show_title' => false,
		'overlay_gallery' => false,
		'deeplinking' => false
	);
	global $wp_query;
	if( !themify_get('setting-autoinfinite') ){
		$autoinfinite = 'auto';
	}
	//Inject variable values in gallery script
	wp_localize_script( 'theme-script', 'themifyScript', array(
		'lightbox' => apply_filters('themify_lightbox_args', $overlay_args),
		'loadingImg'   => get_template_directory_uri() . '/images/loading.gif',
		'maxPages'	   => $wp_query->max_num_pages,
		'autoInfinite' => $autoinfinite
	));

	//Themify shop script
	wp_enqueue_script( 'theme-shop',	get_template_directory_uri() . '/js/themify.shop.js', array('jquery'), false, true );
	
	// Get carousel variables
	$carou_visible = themify_get('setting-product_slider_visible');
	$carou_autoplay = themify_get('setting-product_slider_auto');
	$carou_speed = themify_get('setting-product_slider_speed');
	$carou_scroll = themify_get('setting-product_slider_scroll');
	$carou_wrap = themify_get('setting-product_slider_wrap');
	//Inject variable values in themify.shop.js
	wp_localize_script( 'theme-shop', 'themifyShop', array(
			'visible'	=> $carou_visible? $carou_visible : '4',
			'autoplay'	=> $carou_autoplay? $carou_autoplay : 0,
			'speed'	=> $carou_speed? $carou_speed : 300,
			'scroll'	=> $carou_scroll? $carou_scroll : 1,
			'wrap'	=> ('' == $carou_wrap || 'yes' == $carou_wrap)? 'circular' : null
		)
	);
	
	//WordPress thread comment reply script
	if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' );
}
add_action('wp_enqueue_scripts', 'themify_theme_enqueue_scripts');

// Declare Woocommerce support
add_theme_support( 'woocommerce' );

/**
 * Add woocommerce_enable_ajax_add_to_cart option to JS
 * @param Array
 * @return Array
 */
function themify_woocommerce_params($params){
	return array_merge($params, array(
		'option_ajax_add_to_cart' => ( 'yes' == get_option('woocommerce_enable_ajax_add_to_cart') )? 'yes' : 'no' 
		)
	);
}
add_filter('woocommerce_params', 'themify_woocommerce_params');

/**
 * Add viewport tag for responsive layouts
 * @package themify
 */
function themify_viewport_tag(){
	echo "\n".'<meta name="viewport" content="width=100%, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">'."\n";
}
add_action( 'wp_head', 'themify_viewport_tag' );

/**
 * Add JavaScript files if IE version is lower than 9
 * @package themify 
 */
function themify_ie_enhancements(){
	echo '
<!-- media-queries.js -->
<!--[if lt IE 9]>
	<script src="' . get_template_directory_uri() . '/js/respond.js"></script>
<![endif]-->

<!-- html5.js -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
';
}
add_action( 'wp_head', 'themify_ie_enhancements' );

/**
 * Make IE behave like a standards-compliant browser
 */
function themify_ie_standards_compliant() {
	echo "\n".'<!--[if lt IE 9]>
		<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	<![endif]-->'."\n";
}
add_action('wp_head', 'themify_ie_standards_compliant');

/* Custom Write Panels
 ***************************************************************************/

	///////////////////////////////////////
	// Setup Write Panel Options
	///////////////////////////////////////
	
	// Post Meta Box Options
	$post_meta_box_options = array(
	// Layout
	array(
		  "name" 		=> "layout",	
		  "title" 		=> __('Sidebar Option', 'themify'), 	
		  "description" => "", 				
		  "type" 		=> "layout",			
		  "meta"		=> array(
				array("value" => "default", "img" => "images/layout-icons/default.png", "selected" => true),
				array("value" => "sidebar1", 	"img" => "images/layout-icons/sidebar1.png"),
				array("value" => "sidebar1 sidebar-left", 	"img" => "images/layout-icons/sidebar1-left.png"),
				array("value" => "sidebar-none",	 	"img" => "images/layout-icons/sidebar-none.png")
			)			
		),
	// Post Image
	array(
		  "name" 		=> "post_image",
		  "title" 		=> __('Featured Image', 'themify'),
		  "description" => '',
		  "type" 		=> "image",
		  "meta"		=> array()
		),
   	// Featured Image Size
	array(
		'name'	=>	'feature_size',
		'title'	=>	__('Image Size', 'themify'),
		'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="admin.php?page=regenerate-thumbnails">Regenerated</a>', 'themify'),
		'type'		 =>	'featimgdropdown'
		),
	// Image Width
	array(
		  "name" 		=> "image_width",	
		  "title" 		=> __('Image Width', 'themify'), 
		  "description" => "", 				
		  "type" 		=> "textbox",			
		  "meta"		=> array("size"=>"small")			
		),
	// Image Height
	array(
		  "name" 		=> "image_height",	
		  "title" 		=> __('Image Height', 'themify'), 
		  "description" => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'),
		  "type" 		=> "textbox",			
		  "meta"		=> array("size"=>"small")			
		),
	// Hide Post Title
	array(
		  "name" 		=> "hide_post_title",	
		  "title" 		=> __('Hide Post Title', 'themify'),
		  "description" => "", 				
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
								array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)
		),
	// Unlink Post Title
	array(
		  "name" 		=> "unlink_post_title",	
		  "title" 		=> __('Unlink Post Title', 'themify'), 	
		  "description" => __('Unlink post title (it will display the post title without link)', 'themify'), 				
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)			
		),
	// Hide Post Meta
	array(
		  "name" 		=> "hide_post_meta",	
		  "title" 		=> __('Hide Post Meta', 'themify'), 	
		  "description" => "", 				
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)			
		),
	// Hide Post Date
	array(
		  "name" 		=> "hide_post_date",	
		  "title" 		=> __('Hide Post Date', 'themify'), 	
		  "description" => "", 				
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)			
		),
	// Hide Post Image
	array(
		  "name" 		=> "hide_post_image",	
		  "title" 		=> __('Hide Featured Image', 'themify'), 	
		  "description" => "", 				
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)			
		),
	// Unlink Post Image
	array(
		  "name" 		=> "unlink_post_image",	
		  "title" 		=> __('Unlink Featured Image', 'themify'), 	
		  "description" => __('Display the Featured Image without link)', 'themify'), 				
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)			
		),
	// External Link
	array(
		  "name" 		=> "external_link",	
		  "title" 		=> __('External Link', 'themify'), 	
		  "description" => __('Link Featured Image to external URL', 'themify'), 				
		  "type" 		=> "textbox",			
		  "meta"		=> array()			
		),
	// Lightbox Link
	array(
		  "name" 		=> "lightbox_link",	
		  "title" 		=> __('Lightbox Link', 'themify'), 	
		  "description" => __('Link Featured Image to lightbox image, video or external iframe (<a href="http://themify.me/docs/lightbox">learn more</a>)', 'themify'),
		  "type" 		=> "textbox",			
		  "meta"		=> array()			
		)
	);


	// Page Meta Box Options
	$page_meta_box_options = array(
  	// Page Layout
	array(
		  "name" 		=> "page_layout",
		  "title"		=> __('Sidebar Option', 'themify'),
		  "description"	=> "",
		  "type"		=> "layout",
		  "meta"		=> array(
				array("value" => "default", "img" => "images/layout-icons/default.png", "selected" => true),
				array("value" => "sidebar1", 	"img" => "images/layout-icons/sidebar1.png"),
				array("value" => "sidebar1 sidebar-left", 	"img" => "images/layout-icons/sidebar1-left.png"),
				array("value" => "sidebar-none",	 	"img" => "images/layout-icons/sidebar-none.png")
			)
		),
	// Hide page title
	array(
		  "name" 		=> "hide_page_title",
		  "title"		=> __('Hide Page Title', 'themify'),
		  "description"	=> "",
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)	
		),
   // Query Category
	array(
		  "name" 		=> "query_category",
		  "title"		=> __('Query Category', 'themify'),
		  "description"	=> __('Select a category or enter multiple category IDs (eg. 2,5,6). Enter 0 to display all category.', 'themify'),
		  "type"		=> "query_category",
		  "meta"		=> array()
		),
	// Section Categories
	array(
		  "name" 		=> "section_categories",	
		  "title" 		=> __('Section Categories', 'themify'), 	
		  "description" => __('Display multiple query categories separately', 'themify'), 				
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)			
		),
	// Post Layout
	array(
		  "name" 		=> "layout",
		  "title"		=> __('Query Post Layout', 'themify'),
		  "description"	=> "",
		  "type"		=> "layout",
		  "meta"		=> array(
				array("value" => "list-post", "img" => "images/layout-icons/list-post.png", "selected" => true),
				array("value" => "grid4", "img" => "images/layout-icons/grid4.png"),
				array("value" => "grid3", "img" => "images/layout-icons/grid3.png"),
				array("value" => "grid2", "img" => "images/layout-icons/grid2.png"),
				array("value" => "list-large-image", "img" => "images/layout-icons/list-large-image.png"),
				array("value" => "list-thumb-image", "img" => "images/layout-icons/list-thumb-image.png")
			)
		),
	// Posts Per Page
	array(
		  "name" 		=> "posts_per_page",
		  "title"		=> __('Posts per page', 'themify'),
		  "description"	=> "",
		  "type"		=> "textbox",
		  "meta"		=> array("size" => "small")
		),
	
	// Display Content
	array(
		  "name" 		=> "display_content",
		  "title"		=> __('Display Content', 'themify'),
		  "description"	=> "",
		  "type"		=> "dropdown",
		  "meta"		=> array(
								array("name"=>"Full Content","value"=>"content","selected"=>true),
		  						array("name"=>"Excerpt","value"=>"excerpt"),
								array("name"=>"None","value"=>"none")
							)
		),
	// Featured Image Size
	array(
		'name'	=>	'feature_size_page',
		'title'	=>	__('Image Size', 'themify'),
		'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="admin.php?page=regenerate-thumbnails">Regenerated</a>', 'themify'),
		'type'		 =>	'featimgdropdown'
		),
	// Image Width
	array(
		  "name" 		=> "image_width",	
		  "title" 		=> __('Image Width', 'themify'), 
		  "description" => "", 				
		  "type" 		=> "textbox",			
		  "meta"		=> array("size"=>"small")			
		),
	// Image Height
	array(
		  "name" 		=> "image_height",	
		  "title" 		=> __('Image Height', 'themify'), 
		  "description" => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'),
		  "type" 		=> "textbox",			
		  "meta"		=> array("size"=>"small")			
		),
	// Hide Title
	array(
		  "name" 		=> "hide_title",
		  "title"		=> __('Hide Post Title', 'themify'),
		  "description"	=> "",
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)
		),
	// Unlink Post Title
	array(
		  "name" 		=> "unlink_title",	
		  "title" 		=> __('Unlink Post Title', 'themify'), 	
		  "description" => __('Unlink post title (it will display the post title without link)', 'themify'), 				
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)			
		),
	// Hide Post Date
	array(
		  "name" 		=> "hide_date",
		  "title"		=> __('Hide Post Date', 'themify'),
		  "description"	=> "",
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)
		),
	// Hide Post Meta
	array(
		  "name" 		=> "hide_meta",
		  "title"		=> __('Hide Post Meta', 'themify'),
		  "description"	=> "",
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)
		),
	// Hide Post Image
	array(
		  "name" 		=> "hide_image",	
		  "title" 		=> __('Hide Featured Image', 'themify'), 	
		  "description" => "", 				
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)			
		),
	// Unlink Post Image
	array(
		  "name" 		=> "unlink_image",	
		  "title" 		=> __('Unlink Featured Image', 'themify'), 	
		  "description" => __('Display the Featured Image without link)', 'themify'), 				
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)			
		),
	// Page Navigation Visibility
	array(
		  "name" 		=> "hide_navigation",
		  "title"		=> __('Hide Page Navigation', 'themify'),
		  "description"	=> "",
		  "type" 		=> "dropdown",			
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", "name" => "Yes"),
								array("value" => "no",	"name" => "No")
							)
		)	
	);
		
	// Slider Meta Box Options
	$slider_meta_box_options = array(
		// Post Layout
		array(
			  "name" 		=> "layout",
			  "title"		=> __('Slide Layout', 'themify'),
			  "description"	=> "",
			  "type"		=> "layout",
			  "meta"		=> array(
									 array("value" => "slider-default", 	 "img" => "images/layout-icons/slider-default.png", "selected" => true),
									 array("value" => "slider-image-only", 	 "img" => "images/layout-icons/slider-image-only.png"),
									 array("value" => "slider-content-only", "img" => "images/layout-icons/slider-content-only.png"),
									 array("value" => "slider-image-caption","img" => "images/layout-icons/slider-image-caption.png")
									 )
			),
		// Feature Image
		array(
			  "name" 		=> "feature_image",	
			  "title" 		=> __('Featured Image', 'themify'), //slider image 
			  "description" => "", 				
			  "type" 		=> "image",			
			  "meta"		=> array()			
		),
		// Featured Image Size
		array(
			'name'	=>	'feature_size',
			'title'	=>	__('Image Size', 'themify'),
			'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="admin.php?page=regenerate-thumbnails">Regenerated</a>', 'themify'),
			'type'		 =>	'featimgdropdown'
		),
		// Image Width
		array(
			  "name" 		=> "image_width",	
			  "title" 		=> __('Image Width', 'themify'), 
			  "description" => "", 				
			  "type" 		=> "textbox",			
			  "meta"		=> array("size"=>"small")			
			),
		// Image Height
		array(
			  "name" 		=> "image_height",	
			  "title" 		=> __('Image Height', 'themify'), 
			  "description" => "", 				
			  "type" 		=> "textbox",			
			  "meta"		=> array("size"=>"small")			
			),
		// Image Link
		array(
			  "name" 		=> "image_link",	
			  "title" 		=> __('Image Link', 'themify'), 
			  "description" => "", 				
			  "type" 		=> "textbox",			
			  "meta"		=> array()			
			),
		// External Link
		array(
			  "name" 		=> "external_link",	
			  "title" 		=> __('External Link', 'themify'), 	
			  "description" => __('Link Featured Image to external URL', 'themify'), 				
			  "type" 		=> "textbox",			
			  "meta"		=> array()			
			),
		// Lightbox Link
		array(
			  "name" 		=> "lightbox_link",	
			  "title" 		=> __('Lightbox Link', 'themify'), 
			  "description" => __('Link Featured Image to lightbox image, video or external iframe', 'themify'), 				
			  "type" 		=> "textbox",			
			  "meta"		=> array()			
			)
		);


	///////////////////////////////////////
	// Build Write Panels
	///////////////////////////////////////
	themify_build_write_panels(array(
				array(
					"name"		=> __('Post Options', 'themify'),			// Name displayed in box
					"options"	=> $post_meta_box_options, 	// Field options
					"pages"	=> "post"					// Pages to show write panel
				),
				array(
					"name"		=> __('Page Options', 'themify'),	
					"options"	=> $page_meta_box_options, 		
					"pages"	=> "page"
				),
		   		array(
					"name"		=> __('Homepage Slider Options', 'themify'),	
					"options"	=> $slider_meta_box_options, 		
					"pages"	=> "slider"
				)
		  	)
		);	
	
	
	
/* 	Custom Functions
/***************************************************************************/	

	///////////////////////////////////////
	// Enable WordPress feature image
	///////////////////////////////////////
	add_theme_support( 'post-thumbnails' );
	remove_post_type_support( 'page', 'thumbnail' );

	///////////////////////////////////////
	// Add wmode transparent and post-video container for responsive purpose
	///////////////////////////////////////	
	function themify_add_video_wmode_transparent($html, $url, $attr) {
		$services = array(
			'youtube.com',
			'youtu.be',
			'blip.tv',
			'vimeo.com',
			'dailymotion.com',
			'hulu.com',
			'viddler.com',
			'qik.com',
			'revision3.com',
			'wordpress.tv',
			'wordpress.com',
			'funnyordie.com'
		);
		$video_embed = false;
		foreach( $services as $service ){
			if(stripos($html, $service)){
				$video_embed = true;
				break;
			}
		}
		if( $video_embed ){
			$html = '<div class="post-video">' . $html . '</div>';
			if (strpos($html, "<embed src=" ) !== false) {
				$html = str_replace('</param><embed', '</param><param name="wmode" value="transparent"></param><embed wmode="transparent" ', $html);
				return $html;
			}
			else {
				if(strpos($html, "wmode=transparent") == false){
					if(strpos($html, "?fs=" ) !== false){
						$search = array('?fs=1', '?fs=0');
						$replace = array('?fs=1&wmode=transparent', '?fs=0&wmode=transparent');
						$html = str_replace($search, $replace, $html);
						return $html;
					}
					else{
						$youtube_embed_code = $html;
						$patterns[] = '/youtube.com\/embed\/([a-zA-Z0-9._-]+)/';
						$replacements[] = 'youtube.com/embed/$1?wmode=transparent';
						return preg_replace($patterns, $replacements, $html);
					}
				}
				else{
					return $html;
				}
			}
		} else {
			return '<div class="post-embed">' . $html . '</div>';
		}
	}
	add_filter('embed_oembed_html', 'themify_add_video_wmode_transparent');
	
	///////////////////////////////////////
	// Register Custom Menu Function
	///////////////////////////////////////
	function themify_register_custom_nav() {
		if (function_exists('register_nav_menus')) {
			register_nav_menus( array(
				'main-nav' => __( 'Main Navigation', 'themify' )
			) );
		}
	}
	
	// Register Custom Menu Function - Action
	add_action('init', 'themify_register_custom_nav');
	
	///////////////////////////////////////
	// Default Main Nav Function
	///////////////////////////////////////
	function themify_default_main_nav() {
		echo '<ul id="main-nav" class="main-nav clearfix">';
		wp_list_pages('title_li=');
		echo '</ul>';
	}

	///////////////////////////////////////
	// Register Sidebars
	///////////////////////////////////////
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'name' => __('Sidebar', 'themify'),
			'id' => 'sidebar-main',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'name' => __('Social Widget', 'themify'),
			'id' => 'social-widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<strong>',
			'after_title' => '</strong>',
		));
	}

	///////////////////////////////////////
	// Footer Sidebars
	///////////////////////////////////////
	if ( function_exists('register_sidebar') ) {
		$data = themify_get_data();
		$columns = array('footerwidget-4col' 			=> 4,
						'footerwidget-3col'			=> 3,
						'footerwidget-2col' 		=> 2,
						'footerwidget-1col' 		=> 1,
						'none'			 		=> 0, );
		$option = ($data['setting-footer_widgets'] == "" || !isset($data['setting-footer_widgets'])) ?  "footerwidget-3col" : $data['setting-footer_widgets'];
		for($x=1;$x<=$columns[$option];$x++){
			register_sidebar(array(
				'name' => __('Footer Widget', 'themify') . ' ' .$x,
				'id' => 'footer-widget-'.$x,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			));			
		}
	}

	///////////////////////////////////////
	// Custom Theme Comment
	///////////////////////////////////////
	function themify_theme_comment($comment, $args, $depth) {
	   $GLOBALS['comment'] = $comment; 
	   ?>

<li id="comment-<?php comment_ID() ?>" <?php comment_class(); ?>>
	<p class="comment-author"> <?php echo get_avatar($comment,$size='48'); ?> <?php printf( '<cite>%s</cite>', get_comment_author_link()) ?><br />
		<small class="comment-time"><strong>
		<?php comment_date('M d, Y'); ?>
		</strong> @
		<?php comment_time('H:i:s'); ?>
		<?php edit_comment_link(__('Edit', 'themify'),' [',']') ?>
		</small> </p>
	<div class="commententry">
		<?php if ($comment->comment_approved == '0') : ?>
		<p><em>
			<?php _e('Your comment is awaiting moderation.', 'themify') ?>
			</em></p>
		<?php endif; ?>
		<?php comment_text() ?>
	</div>
	<p class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'depth' => $depth, 'reply_text' => __( 'Reply', 'themify' ), 'max_depth' => $args['max_depth']))) ?>
	</p>
	<?php
	}
	
	///////////////////////////////////////
	// Themify Theme Key
	///////////////////////////////////////
	add_filter('themify_theme_key', create_function('$k', "return 'df6cgtpvpsffsypqkonj94dyahc7c9wxs';"));
	
	/**
	 * Returns default shop layout
	 * @param String $class
	 * @return @String
	 */
	function themify_add_body_classes($class) {
		if(is_shop()){
			return themify_get('setting-shop_layout')? themify_get('setting-shop_layout') : 'sidebar1';
		}
		if(is_product()){
			return themify_get('setting-single_product_layout')? themify_get('setting-single_product_layout') : 'sidebar1';
		}
	}
	add_filter('themify_default_layout', 'themify_add_body_classes');
	
	function theme_default_layout_condition($bool){
		global $themify;
		return '' == $themify->layout || (is_shop() || is_product());
	}
	add_filter('themify_default_layout_condition', 'theme_default_layout_condition');

	/**
	 * Displays a link to edit the entry
	 */
	function themify_edit_link() {
		edit_post_link(__('Edit', 'themify'), '[', ']');
	}
	
	/**
	 * Put product variations in page
	 */
	function themify_available_variations() {
		global $product;
		if(isset($product->product_type) && 'variable' == $product->product_type){
			$product_vars = $product->get_available_variations();
		} else {
			$product_vars = array();
		}
		echo '<div style="display:none;" id="themify_product_vars">' . json_encode($product_vars) . '</div>';
	};
  
	///////////////////////////////////////
	// Start Woocommerce script
	///////////////////////////////////////
	require_once(TEMPLATEPATH . '/woocommerce/functions/themify-breadcrumbs.php'); // Themify breadcrumbs
	require_once(TEMPLATEPATH . '/woocommerce/theme-woocommerce.php'); // WooCommerce overrides
	require_once(TEMPLATEPATH . '/woocommerce/woocommerce-hooks.php'); // WooCommerce hook overrides
	require_once(TEMPLATEPATH . '/woocommerce/woocommerce-template.php'); // WooCommerce template overrides
  
	///////////////////////////////////////
	// Themify Theme Key
	///////////////////////////////////////
	add_filter('themify_theme_key', create_function('$k', "return '1hh7p1hhdtrin0zi47uye9txy4aiafmn2';"));
  
?>