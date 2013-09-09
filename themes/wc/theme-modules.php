<?php

/*
To add custom modules to the theme, create a new 'custom-modules.php' file in the theme folder.
They will be added to the theme automatically.
*/

/**
 * Shop modules
 * @since 1.0.0
 ***************************************************************************/


/**
 * Choose pagination or infinite scroll
 * @param array $data
 * @return string
 */
function themify_pagination_infinite($data=array()){
	$data = themify_get_data();
	if($data['setting-autoinfinite']) $auto_checked = "checked='checked'";
	$html = '<p>';

	//Infinite Scroll
	$html .= '<input ' . checked(isset($data['setting-more_posts'])? $data['setting-more_posts'] : 'infinite', 'infinite', false) . ' type="radio" name="setting-more_posts" value="infinite" /> ';
	$html .= __('Infinite Scroll (posts are loaded on the same page)', 'themify');
	$html .= '<br/>';
	$html .= '<input style="margin-left:15px" type="checkbox" name="setting-autoinfinite" '.checked(isset($data['setting-autoinfinite'])? $data['setting-autoinfinite'] : '', 'on', false).'/> ' . __('Disable automatic infinite scroll', 'themify');
	$html .= '<br/><br/>';

	//Numbered pagination
	$html .= '<input ' . checked( $data['setting-more_posts'], 'pagination', false) . ' type="radio" name="setting-more_posts" value="pagination" /> ';
	$html .= __('Numbered Page Navigation (page 1, 2, 3, etc.)', 'themify');
	$html .= '</p>';

	return $html;
}

/**
 * Creates module with settings for product slider
 * @param array
 * @return string
 * @since 1.0.0
 */
function themify_product_slider($data=array()){
	$data = themify_get_data();
	
	/** Slider values @var array */
	$slider_ops = array( __('On', 'themify') => 'on', __('Off', 'themify') => 'off' );
	/** Slider status */
	if('' == $data['setting-product_slider_enabled'] || 'on' == $data['setting-product_slider_enabled']){
		$enabled_checked = "selected='selected'";
	} else {
		$enabled_display = "style='display:none;'";	
	}
	if($data['setting-product_slider_visible'] == "" ||!isset($data['setting-product_slider_visible'])){
		$data['setting-product_slider_visible'] = "4";	
	}
	
	$show_options = array('' => '',__('Yes', 'themify') => 'yes', __('No', 'themify') => 'no');
	$auto_options = array(0,1,2,3,4,5,6,7);
	$scroll_options = array(1,2,3,4,5,6,7);
	$speed_options = array( __('Fast', 'themify')=>300, __('Normal', 'themify')=>1000, __('Slow', 'themify')=>2000);
	$wrap_options = array('' => '',__('Yes', 'themify') => 'yes', __('No', 'themify') => 'no');
	$image_options = array("one","two","three","four","five","six","seven","eight","nine","ten");
	
	$output .= '<p><span class="label">' . __('Enable Slider', 'themify') . '</span> <select name="setting-product_slider_enabled" class="feature_box_enabled_check">';
	/** Iterate through slider options */
	foreach ($slider_ops as $key => $val) {
		$output .= '<option value="'.$val.'" ' . selected($data['setting-product_slider_enabled'], $val, false) . '>' . $key . '</option>';
	}
	$output .= '</select>' . '</p>

				<div class="feature_box_enabled_display" '.$enabled_display.'>
				<p class="pushlabel feature_box_posts">';
					$output .= wp_dropdown_categories(
						array("show_option_all"=> __('Featured Products', 'themify'),
						"show_option_all"=> __('Featured Products', 'themify'),
						"hide_empty"=>0,
						"echo"=>0,
						"name"=>"setting-product_slider_posts_category",
						'selected' => $data['setting-product_slider_posts_category'],
						'taxonomy' => 'product_cat'
					));
	$output .=	'<br/><input type="text" name="setting-product_slider_posts_slides" value="'.$data['setting-product_slider_posts_slides'].'" class="width2" /> ' . __('number of products to be queried', 'themify') . ' 
				</p>';
				
	$output .= '<p class="feature_box_posts">
					<span class="label">' . __('Hide Title', 'themify') . '</span>
					<select name="setting-product_slider_hide_title">';
					foreach($show_options as $name => $option){
							if($option == $data['setting-product_slider_hide_title']){
								$output .= '<option value="'.$option.'" selected="selected">'.$name.'</option>';
							} else {
								$output .= '<option value="'.$option.'">'.$name.'</option>';
							}
						}
	$output .= '	</select>
				</p>';

	$output .= '<p class="feature_box_posts">
					<span class="label">' . __('Hide Price', 'themify') . '</span>
					<select name="setting-product_slider_hide_price">';
					foreach($show_options as $name => $option){
							if($option == $data['setting-product_slider_hide_price']){
								$output .= '<option value="'.$option.'" selected="selected">'.$name.'</option>';
							} else {
								$output .= '<option value="'.$option.'">'.$name.'</option>';
							}
						}
	$output .= '	</select>
				</p>';	
				
		
	$output .= '<p>
					<span class="label">' . __('Visible', 'themify') . '</span> 
					<select name="setting-product_slider_visible">';
					for($x = 1; $x <= apply_filters('themify_product_slider_visible', 7); $x++){
						if($data['setting-product_slider_visible'] == $x){
							$output .= '<option value="'.$x.'" selected="selected">'.$x.'</option>';	
						} else {
							$output .= '<option value="'.$x.'">'.$x.'</option>';	
						}
					}
		$output .=	'</select> <small>' . __('(# of slides visible at the same time)', 'themify') . '</small>
				</p>
				<p>
				<span class="label">' . __('Auto Play', 'themify') . '</span>
							<select name="setting-product_slider_auto">
							';
						foreach($auto_options as $option){
							if($option == $data['setting-product_slider_auto']){
								$output .= '<option value="'.$option.'" selected="selected">'.$option.'</option>';
							} else {
								$output .= '<option value="'.$option.'">'.$option.'</option>';
							}
						}		
		$output .= '
					</select> <small>' . __('(auto advance slider, 0 = off)', 'themify') . '</small>
				</p>
				<p>
				<span class="label">' . __('Scroll', 'themify') . '</span>
							<select name="setting-product_slider_scroll">
							';
						foreach($scroll_options as $option){
							if($option == $data['setting-product_slider_scroll']){
								$output .= '<option value="'.$option.'" selected="selected">'.$option.'</option>';
							} else {
								$output .= '<option value="'.$option.'">'.$option.'</option>';
							}
						}		
		$output .= '
					</select>
				</p>
				<p>
					<span class="label">' . __('Speed', 'themify') . '</span> 
					<select name="setting-product_slider_speed">';
					foreach($speed_options as $name => $val){
						if($data['setting-product_slider_speed'] == $val){
							$output .= '<option value="'.$val.'" selected="selected">'.$name.'</option>';	
						} else {
							$output .= '<option value="'.$val.'">'.$name.'</option>';	
						}	
					}
		$output .= '</select>
				</p>
				<p>
					<span class="label">' . __('Wrap Slides', 'themify') . '</span> 
					<select name="setting-product_slider_wrap">';
					foreach($wrap_options as $name => $value){
							if($data['setting-product_slider_wrap'] == $value){
								$output .= '<option value="'.$value.'" selected="selected">'.$name.'</option>';	
							} else {
								$output .= '<option value="'.$value.'">'.$name.'</option>';	
							}
						}
		$output .=	'</select>
				</p>				
				</div>';		
	return $output;
}

/**
 * Creates module for general shop layout and settings
 * @param array
 * @return string
 * @since 1.0.0
 */
function themify_shop_layout($data=array()){
	$data = themify_get_data();
	
	$options = array(
		array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'selected' => true),
		array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png'),
		array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png')
	);
	$products_layout_options = array(
		 array('value' => 'grid4', 'img' => 'images/layout-icons/grid4.png', 'selected' => true),
		 array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png'),
		 array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png')
	);
	$default_options = array(
		'' => '',
		__('Yes', 'themify') => 'yes',
		__('No', 'themify') => 'no'
	);
	$content_options = array(
		__('None', 'themify') => '',
		__('Short Description', 'themify') => 'excerpt',
		__('Full Content', 'themify') => 'content'
	);
						 
	$val = $data['setting-shop_layout'];
	
	/**
	 * Modules output
	 * @var String
	 * @since 1.0.0
	 */
	$output = '';
	
	/**
	 * Sidebar option
	 */
	$output .= '<p><span class="label">' . __('Page Sidebar Option', 'themify') . '</span>';
	foreach($options as $option){
		if(($val == "" || !$val || !isset($val)) && $option['selected']){ 
			$val = $option['value'];
		}
		if($val == $option['value']){ 
			$class = "selected";
		} else {
			$class = "";	
		}
		$output .= '<a href="#" class="preview-icon '.$class.'"><img src="'.get_bloginfo('template_directory').'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
	}
	$output .= '<input type="hidden" name="setting-shop_layout" class="val" value="'.$val.'" /></p>';
	$output .= '<p>
					<span class="label">' . __('Products per page', 'themify') . '</span>
					<input type="text" name="setting-shop_products_per_page" value="'.$data['setting-shop_products_per_page'].'" class="width2" />
				</p>';
	
	/**
	 * Products Catalog Layout
	 */
	$output .= '<p>
					<span class="label">' . __('Product Layout', 'themify') . '</span>';
	$val = $data['setting-products_layout'];
	foreach($products_layout_options as $option){
		if(($val == "" || !$val || !isset($val)) && $option['selected']){ 
			$val = $option['value'];
		}
		if($val == $option['value']){ 
			$class = "selected";
		} else {
			$class = "";	
		}
		$output .= '<a href="#" class="preview-icon '.$class.'"><img src="'.get_bloginfo('template_directory').'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
	}
	$output .= '	<input type="hidden" name="setting-products_layout" class="val" value="'.$val.'" />
				</p>';

	/**
	 * Hide Title Options
	 * @var String
	 * @since 1.1.2
	 */
	$hide_title = '';
	foreach($default_options as $name => $option){
		if($option == $data['setting-product_archive_hide_title']){
			$hide_title .= '<option value="'.$option.'" selected="selected">'.$name.'</option>';
		} else {
			$hide_title .= '<option value="'.$option.'">'.$name.'</option>';
		}
	}
	$output .= '<p class="feature_box_posts">
					<span class="label">' . __('Hide Product Title', 'themify') . '</span>
					<select name="setting-product_archive_hide_title">
						'.$hide_title.'
					</select>
				</p>';
	
	/**
	 * Hide Price Options
	 * @var String
	 * @since 1.1.2
	 */
	$hide_price = '';
	foreach($default_options as $name => $option){
		if($option == $data['setting-product_archive_hide_price']){
			$hide_price .= '<option value="'.$option.'" selected="selected">'.$name.'</option>';
		} else {
			$hide_price .= '<option value="'.$option.'">'.$name.'</option>';
		}
	}
	$output .= '<p class="feature_box_posts">
					<span class="label">' . __('Hide Product Price', 'themify') . '</span>
					<select name="setting-product_archive_hide_price">
						'.$hide_price.'
					</select>
				</p>';
	
	/**
	 * Show Short Description Options
	 * @var String
	 * @since 1.1.2
	 */
	$show_short = '';
	foreach($content_options as $name => $option){
		if($option == $data['setting-product_archive_show_short']){
			$show_short .= '<option value="'.$option.'" selected="selected">'.$name.'</option>';
		} else {
			$show_short .= '<option value="'.$option.'">'.$name.'</option>';
		}
	}
	$output .= '<p class="feature_box_posts">
					<span class="label">' . __('Product Description', 'themify') . '</span>
					<select name="setting-product_archive_show_short">
						'.$show_short.'
					</select>
				</p>';
	
	return $output;
}

/**
 * Creates module for single product settings
 * @param array
 * @return string
 * @since 1.0.0
 */
function themify_single_product($data=array()){
	$data = themify_get_data();
	
	$options = array(
		array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'selected' => true),
		array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png'),
		array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png')
	);
	
	$default_options = array(
		array('name' => '', 'value' => ''),
		array('name' => __('Yes', 'themify'), 'value' => 'yes'),
		array('name' => __('No', 'themify'), 'value' => 'no')
	);
						 
	$val = $data['setting-single_product_layout'];
	
	$output .= '<p><span class="label">' . __('Index Sidebar option', 'themify') . '</span>';
	foreach($options as $option){
		if(($val == "" || !$val || !isset($val)) && $option['selected']){ 
			$val = $option['value'];
		}
		if($val == $option['value']){ 
			$class = "selected";
		} else {
			$class = "";	
		}
		$output .= '<a href="#" class="preview-icon '.$class.'"><img src="'.get_bloginfo('template_directory').'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
	}
	$output .= '<input type="hidden" name="setting-single_product_layout" class="val" value="'.$val.'" /></p>';	
	
	if($data['setting-product_reviews']){
		$product_reviews_checked = "checked='checked'";
	}
	$output .= '<p><span class="label">' . __('Product reviews', 'themify') . '</span>
				<input type="checkbox" name="setting-product_reviews" '.$product_reviews_checked.' /> ' . __('Disable product reviews', 'themify') . '</p>';	
	
	if($data['setting-related_products']){
		$related_products_checked = "checked='checked'";
	}
	$output .= '<p><span class="label">' . __('Related products', 'themify') . '</span>
				<input type="checkbox" name="setting-related_products" '.$related_products_checked.' /> ' . __('Do not display related products', 'themify') . '</p>';
	
	return $output;
}

/**
 * General Custom Modules
 ***************************************************************************/

	///////////////////////////////////////////
	// Footer Text Left Function
	///////////////////////////////////////////
	function themify_footer_text_left($data=array()){
		$data = themify_get_data();
		return '<p><textarea class="widthfull" rows="4" name="setting-footer_text_left">'.$data['setting-footer_text_left'].'</textarea></p>';	
	}
		
	///////////////////////////////////////////
	// Footer Text Right Function
	///////////////////////////////////////////
	function themify_footer_text_right($data=array()){
		$data = themify_get_data();
		return '<p><textarea class="widthfull" rows="4" name="setting-footer_text_right">'.$data['setting-footer_text_right'].'</textarea></p>';	
	}

	///////////////////////////////////////////
	// Footer Sidebars Function
	///////////////////////////////////////////
	function themify_footer_widgets($data=array()){
		$data = themify_get_data();
		$options = array(
			 array("value" => "footerwidget-4col", 	"img" => "images/layout-icons/footerwidget-4col.png"),
			 array("value" => "footerwidget-3col", 	"img" => "images/layout-icons/footerwidget-3col.png", "selected" => true),
			 array("value" => "footerwidget-2col", 	"img" => "images/layout-icons/footerwidget-2col.png"),
			 array("value" => "footerwidget-1col",	"img" => "images/layout-icons/footerwidget-1col.png"),
			 array("value" => "none",				"img" => "images/layout-icons/none.png")
		);
		$val = $data['setting-footer_widgets'];
		$output = "";
		foreach($options as $option){
			if(($val == "" || !$val || !isset($val)) && $option['selected']){ 
				$val = $option['value'];
			}
			if($val == $option['value']){ 
				$class = "selected";
			} else {
				$class = "";	
			}
			$output .= '<a href="#" class="preview-icon '.$class.'"><img src="'.get_bloginfo('template_directory').'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
		}
		
		$output .= '<input type="hidden" name="setting-footer_widgets" class="val" value="'.$val.'" />';
		
		return $output;
	}

	///////////////////////////////////////////
	// Exclude RSS
	///////////////////////////////////////////
	function themify_exclude_rss($data=array()){
		$data = themify_get_data();
		if($data['setting-exclude_rss']){
			$pages_checked = "checked='checked'";	
		}
		return '<p><input type="checkbox" name="setting-exclude_rss" '.$pages_checked.'/> ' . __('Check here to exclude RSS icon/button', 'themify') . '</p>';	
	}

	///////////////////////////////////////////
	// Exclude Search Form
	///////////////////////////////////////////
	function themify_exclude_search_form($data=array()){
		$data = themify_get_data();
		if($data['setting-exclude_search_form']){
			$pages_checked = "checked='checked'";	
		}
		return '<p><input type="checkbox" name="setting-exclude_search_form" '.$pages_checked.'/> ' . __('Check here to exclude search form', 'themify') . '</p>';	
	}
	
	///////////////////////////////////////////
	// Default Page Layout Module - Action
	///////////////////////////////////////////
	function themify_default_page_layout($data=array()){
		$data = themify_get_data();
		
		$options = array(
			array("value" => "sidebar1", 	"img" => "images/layout-icons/sidebar1.png", "selected" => true),
			array("value" => "sidebar1 sidebar-left", 	"img" => "images/layout-icons/sidebar1-left.png"),
			array("value" => "sidebar-none",	 	"img" => "images/layout-icons/sidebar-none.png")
		 );
		
		$default_options = array(
			array('name'=>'','value'=>''),
			array('name'=> __('Yes', 'themify'),'value'=>'yes'),
			array('name'=> __('No', 'themify'),'value'=>'no')
		);
							 
		$val = $data['setting-default_page_layout'];
		
		$output .= '<p>
						<span class="label">' . __('Page Sidebar Option', 'themify') . '</span>';
		foreach($options as $option){
			if(($val == "" || !$val || !isset($val)) && $option['selected']){ 
				$val = $option['value'];
			}
			if($val == $option['value']){ 
				$class = "selected";
			} else {
				$class = "";	
			}
			$output .= '<a href="#" class="preview-icon '.$class.'"><img src="'.get_bloginfo('template_directory').'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
		}
		$output .= '<input type="hidden" name="setting-default_page_layout" class="val" value="'.$val.'" /></p>';
		$output .= '<p>
						<span class="label">' . __('Hide Title in All Pages', 'themify') . '</span>
						
						<select name="setting-hide_page_title">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-hide_page_title']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
						
						
		$output .=	'</select>
					</p>';
		if($data['setting-comments_pages']){
			$pages_checked = "checked='checked'";	
		}
		$output .= '<p><span class="label">' . __('Page Comments', 'themify') . '</span><input type="checkbox" name="setting-comments_pages" '.$pages_checked.' /> ' . __('Disable comments in all Pages', 'themify') . '</p>';	
		
		return $output;													 
	}
	
	///////////////////////////////////////////
	// Default Index Layout Module - Action
	///////////////////////////////////////////
	function themify_default_layout($data=array()){
		$data = themify_get_data();
		
		if($data['setting-default_more_text'] == ""){
			$more_text = "More";
		} else {
			$more_text = $data['setting-default_more_text'];
		}
		
		$default_options = array(
								array('name'=>'','value'=>''),
								array('name'=> __('Yes', 'themify'),'value'=>'yes'),
								array('name'=> __('No', 'themify'),'value'=>'no')
							);
		$default_layout_options = array(
								array('name'=> __('Full Content', 'themify'),'value'=>'content'),
								array('name'=> __('Excerpt', 'themify'),'value'=>'excerpt'),
								array('name'=> __('None', 'themify'),'value'=>'none')
							);	
		$default_post_layout_options = array(
			 array("value" => "list-post", "img" => "images/layout-icons/list-post.png", "selected" => true),
			 array("value" => "grid4", "img" => "images/layout-icons/grid4.png"),
			 array("value" => "grid3", "img" => "images/layout-icons/grid3.png"),
			 array("value" => "grid2", "img" => "images/layout-icons/grid2.png"),
			 array("value" => "list-large-image", "img" => "images/layout-icons/list-large-image.png"),
			 array("value" => "list-thumb-image", "img" => "images/layout-icons/list-thumb-image.png")
		);
																 	 
		$options = array(
			array("value" => "sidebar1", "img" => "images/layout-icons/sidebar1.png", "selected" => true),
			array("value" => "sidebar1 sidebar-left", "img" => "images/layout-icons/sidebar1-left.png"),
			array("value" => "sidebar-none", "img" => "images/layout-icons/sidebar-none.png")
		);
						 
		$val = $data['setting-default_layout'];
		
		$output = "";
		
		$output .= '<p>
						<span class="label">' . __('Index Sidebar Option', 'themify') . '</span>';
		foreach($options as $option){
			if(($val == "" || !$val || !isset($val)) && $option['selected']){ 
				$val = $option['value'];
			}
			if($val == $option['value']){ 
				$class = "selected";
			} else {
				$class = "";	
			}
			$output .= '<a href="#" class="preview-icon '.$class.'"><img src="'.get_bloginfo('template_directory').'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
		}
		
		$output .= '<input type="hidden" name="setting-default_layout" class="val" value="'.$val.'" />';
		$output .= '</p>';
		$output .= '<p>
						<span class="label">' . __('Post Layout', 'themify') . '</span>';
						
		$val = $data['setting-default_post_layout'];
		
		foreach($default_post_layout_options as $option){
			if(($val == "" || !$val || !isset($val)) && $option['selected']){ 
				$val = $option['value'];
			}
			if($val == $option['value']){ 
				$class = "selected";
			} else {
				$class = "";	
			}
			$output .= '<a href="#" class="preview-icon '.$class.'"><img src="'.get_bloginfo('template_directory').'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
		}
		
		$output .= '<input type="hidden" name="setting-default_post_layout" class="val" value="'.$val.'" />
					</p>
					<p>
						<span class="label">' . __('Display Content', 'themify') . '</span>  
						<select name="setting-default_layout_display">';
						foreach($default_layout_options as $layout_option){
							if($layout_option['value'] == $data['setting-default_layout_display']){
								$output .= '<option selected="selected" value="'.$layout_option['value'].'">'.$layout_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$layout_option['value'].'">'.$layout_option['name'].'</option>';
							}
						}
		$output .=	'	</select>
					</p>
					<p>
						<span class="label">' . __('Query Categories', 'themify') . '</span>  
						<input type="text" name="setting-default_query_cats" value="'.$data['setting-default_query_cats'].'"><br />
						<span class="pushlabel"><small>' . __('Use minus sign (-) to exclude categories.', 'themify') . '</small></span><br />
						<span class="pushlabel"><small>' . __('Example: "1,4,-7" = only include Category 1, 4, and exclude Category 7.', 'themify') . '</small></span>
					</p>
					<p>
						<span class="label">' . __('More Text', 'themify') . '</span>
						<input type="text" name="setting-default_more_text" value="'.$more_text.'">
					</p>
					<p>
						<span class="label">' . __('Hide Post Title', 'themify') . '</span>
						
						<select name="setting-default_post_title">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_post_title']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Unlink Post Title', 'themify') . '</span>
						
						<select name="setting-default_unlink_post_title">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_unlink_post_title']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Hide Post Meta', 'themify') . '</span>
						
						<select name="setting-default_post_meta">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_post_meta']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Hide Post Date', 'themify') . '</span>
						
						<select name="setting-default_post_date">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_post_date']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Auto Featured Image', 'themify') . '</span>

						<input type="checkbox" value="1" name="setting-auto_featured_image" '.checked($data['setting-auto_featured_image'], 1, false).'/> ' . __('If no featured image is specified, display first image in content.', 'themify') . '
					</p>

					<p>
						<span class="label">' . __('Hide Featured Image', 'themify') . '</span>

						<select name="setting-default_post_image">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_post_image']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Unlink Featured Image', 'themify') . '</span>
						
						<select name="setting-default_unlink_post_image">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_unlink_post_image']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>';
		
		$output .= themify_feature_image_sizes_select('image_post_feature_size');
		
		$data = themify_get_data();
		$options = array("left","right");
		
		if($data['setting-post_image_single_disabled']){
			$checked = 'checked="checked"';
		}
		
		$output .= '<p>
						<span class="label">' . __('Image size', 'themify') . '</span>  
						<input type="text" class="width2" name="setting-image_post_width" value="'.$data['setting-image_post_width'].'" /> ' . __('width', 'themify') . ' <small>(px)</small>  
						<input type="text" class="width2" name="setting-image_post_height" value="'.$data['setting-image_post_height'].'" /> ' . __('height', 'themify') . ' <small>(px)</small>
						<br /><span class="pushlabel"><small>' . __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify') . '</small></span>
					</p>
					<p>
						<span class="label">' . __('Post Image Alignment', 'themify') . '</span>
						<select name="setting-image_post_align">
							<option></option>';
		foreach($options as $option){
			if($option == $data['setting-image_post_align']){
				$output .= '<option value="'.$option.'" selected="selected">'.$option.'</option>';
			} else {
				$output .= '<option value="'.$option.'">'.$option.'</option>';
			}
		}
		$output .=	'</select>
					</p>
					';
		return $output;
	}
	
	///////////////////////////////////////////
	// Default Single Post Layout
	///////////////////////////////////////////
	function themify_default_post_layout($data=array()){
		
		$data = themify_get_data();
		
		$default_options = array(
			array('name'=>'','value'=>''),
			array('name'=> __('Yes', 'themify'),'value'=>'yes'),
			array('name'=> __('No', 'themify'),'value'=>'no')
		);
		
		$val = $data['setting-default_page_post_layout'];

		$output .= '<p><span class="label">' . __('Post Sidebar Option', 'themify') . '</span>';
						
		$options = array(
			array("value" => "sidebar1", 	"img" => "images/layout-icons/sidebar1.png", "selected" => true),
			array("value" => "sidebar1 sidebar-left", 	"img" => "images/layout-icons/sidebar1-left.png"),
			array("value" => "sidebar-none",	 	"img" => "images/layout-icons/sidebar-none.png")
		);
										
		foreach($options as $option){
			if(($val == "" || !$val || !isset($val)) && $option['selected']){ 
				$val = $option['value'];
			}
			if($val == $option['value']){ 
				$class = "selected";
			} else {
				$class = "";	
			}
			$output .= '<a href="#" class="preview-icon '.$class.'"><img src="'.get_bloginfo('template_directory').'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
		}
		
		$output .= '<input type="hidden" name="setting-default_page_post_layout" class="val" value="'.$val.'" />';
		$output .= '</p>
					<p>
						<span class="label">' . __('Hide Post Title', 'themify') . '</span>
						
						<select name="setting-default_page_post_title">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_page_post_title']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Unlink Post Title', 'themify') . '</span>
						
						<select name="setting-default_page_unlink_post_title">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_page_unlink_post_title']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Hide Post Meta', 'themify') . '</span>
						
						<select name="setting-default_page_post_meta">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_page_post_meta']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Hide Post Date', 'themify') . '</span>
						
						<select name="setting-default_page_post_date">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_page_post_date']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Hide Featured Image', 'themify') . '</span>
						
						<select name="setting-default_page_post_image">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_page_post_image']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p><p>
						<span class="label">' . __('Unlink Featured Image', 'themify') . '</span>
						
						<select name="setting-default_page_unlink_post_image">';
						foreach($default_options as $title_option){
							if($title_option['value'] == $data['setting-default_page_unlink_post_image']){
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .= '</select></p>';
	    $output .= themify_feature_image_sizes_select('image_post_single_feature_size');
	    $output .= '<p>
						<span class="label">' . __('Image size', 'themify') . '</span>  
						<input type="text" class="width2" name="setting-image_post_single_width" value="'.$data['setting-image_post_single_width'].'" /> width <small>(px)</small>  
						<input type="text" class="width2" name="setting-image_post_single_height" value="'.$data['setting-image_post_single_height'].'" /> height <small>(px)</small>
						<br /><span class="pushlabel"><small>' . __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify') . '</small></span>
					</p>
					<p>
						<span class="label">' . __('Post Image Alignment', 'themify') . '</span>
						<select name="setting-image_post_single_align">
							<option></option>';
		$options = array("left","right");
		foreach($options as $option){
			if($option == $data['setting-image_post_single_align']){
				$output .= '<option value="'.$option.'" selected="selected">'.$option.'</option>';
			} else {
				$output .= '<option value="'.$option.'">'.$option.'</option>';
			}
		}
		$output .=	'</select>
					</p>';
		if($data['setting-comments_posts']){
			$pages_checked = "checked='checked'";	
		}
		$output .= '<p><span class="label">' . __('Post Comments', 'themify') . '</span><input type="checkbox" name="setting-comments_posts" '.$pages_checked.' /> ' . __('Disable comments in all Posts', 'themify') . '</p>';	
		
		if($data['setting-post_author_box']){
			$author_box_checked = "checked='checked'";	
		}
		$output .= '<p><span class="label">' . __('Show Author Box', 'themify') . '</span><input type="checkbox" name="setting-post_author_box" '.$author_box_checked.' /> ' . __('Show author box in all Posts', 'themify') . '</p>';	
			
		return $output;
	}
?>