<?php
/*-----------------------------------------------------------------------------------*/
/* Any WooCommerce overrides and functions can be found here
/*-----------------------------------------------------------------------------------*/

// Check WooCommerce is installed first
add_action('wp_head', 'themify_check_environment');

function themify_check_environment() {
	if (!class_exists('Woocommerce')) wp_die(__('WooCommerce must be installed', 'themify'));
}

// Disable WooCommerce styles 
define('WOOCOMMERCE_USE_CSS', false);

/**
 * Display breadcrumbs
 * @return void
 * @since 1.0.0
 */
function themify_breadcrumb() {
	if( !is_shop() ){
		themify_breadcrumb_before(); //hook
		themify_breadcrumbs(
			array(
				//nothing before the links
				'before' => '',
				//add product taxonomy
				'singular_product_taxonomy' => 'product_cat',
				//don't add archive page, avoids the Products page which duplicates Store
				'add_archive' => false
			)
		);
		themify_breadcrumb_after(); //hook
	}
}
 
/**
 * Display sorting bar only in shop and category pages
 * @since 1.0.0
 */
function themify_catalog_ordering(){
	global $wp_query;	
	if( !is_search() ){
		woocommerce_catalog_ordering();
	}
}

// Fix the layout etc
function themify_before_content() {	?>

	<?php if( is_front_page() && !is_paged() ){ get_template_part( 'includes/product-slider'); } ?>

	<!-- layout -->
    <div id="layout" class="pagewidth clearfix">

    <!-- Search form -->
	<?php get_template_part( 'wide-search-form'); ?>
    <!-- /Search form -->		
        <?php themify_content_before(); //hook ?>
        <!-- content -->
        <div id="content" class="<?php echo (is_product() || is_shop()) ? 'list-post':''; ?>">
        	
        	<?php themify_content_start(); //hook ?>
        
			<?php //themify_breadcrumb();
}

// hook to woocommerce page after content
function themify_after_content() {
	if (is_search() && is_post_type_archive() ) {
    	add_filter( 'woo_pagination_args', 'woocommerceframework_add_search_fragment', 10 );
	}
	if( 'infinite' == themify_get('setting-more_posts') || '' == themify_get('setting-more_posts') ){
		global $wp_query;
		$total_pages = (int)$wp_query->max_num_pages;
		$current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;

		if( $total_pages > $current_page ){
			//If it's a Query Category page, set the number of total pages
			echo '<p id="load-more"><a href="' . next_posts( $total_pages, false ) . '">' . __('Load More', 'themify') . '</a></p>';
			echo '<script type="text/javascript">var qp_max_pages = ' . $total_pages . '</script>';

		}
	} else {
		themify_pagenav();
	}
    ?>
    
    		<?php themify_content_end(); //hook ?>
		</div><!-- /#content -->
        <?php themify_content_after() //hook; ?>
        
    	<?php
    	if(is_shop()){
    		$layout = themify_get('setting-shop_layout');
		}
		else{
			$layout = themify_get('setting-single_product_layout');
		}
		if ($layout != "sidebar-none")
			get_sidebar();
    	?>
    </div><!-- /#layout -->
    <?php
}

/**
 * Disables price output or not following the setting applied in shop settings panel
 * @param $price String
 * @return String
 */
function themify_no_price($price){
	if( in_the_loop() && is_shop() && themify_get('setting-product_archive_hide_price') == 'yes' )
		return '';
	else
		return $price;
}
add_filter('woocommerce_get_price_html', 'themify_no_price');

/**
 * Disables title output or not following the setting applied in shop settings panel
 * @param $title String
 * @return String
 */
function themify_no_product_title($title){

	if( in_the_loop() && is_shop() && themify_get('setting-product_archive_hide_title') == 'yes' )
		return '';
	else
		return $title;
}
add_filter('the_title', 'themify_no_product_title');

/**
 * Outputs product short description or full content depending on the setting.
 */
function themify_after_shop_loop_item() {
	if ('excerpt' == themify_get('setting-product_archive_show_short')) {
		the_excerpt();
	} elseif ('content' == themify_get('setting-product_archive_show_short')) {
		the_content();
	}
};
add_action('woocommerce_after_shop_loop_item', 'themify_after_shop_loop_item');

/**
 * Include post type product in WordPress' search
 * @param array
 * @since 1.0.0 
 */
function woocommerceframework_add_search_fragment ( $settings ) {
	$settings['add_fragment'] = '?post_type=product';
	return $settings;
} // End woocommerceframework_add_search_fragment()


function themify_products_per_page($products){
	return themify_get('setting-shop_products_per_page');
}
add_filter('loop_shop_per_page', 'themify_products_per_page');

/**
 * Add custom image size
 */ 
function themify_custom_image(){
  add_image_size('cart_thumbnail', 50, 40, true);
}
add_action('after_setup_theme', 'themify_custom_image');

// update catalog images
global $pagenow;

if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' )
	add_action('init', 'themify_install_theme', 1);

function themify_install_theme() {
	update_option( 'woocommerce_catalog_image_width', '222' );
	update_option( 'woocommerce_catalog_image_height', '250' );
	
	update_option( 'woocommerce_single_image_width', '305' );
	update_option( 'woocommerce_single_image_height', '230' );
	
	update_option( 'woocommerce_thumbnail_image_width', '125' );
	update_option( 'woocommerce_thumbnail_image_height', '100' );
}
  
/** gets the url to remove an item from dock cart */
function themify_get_remove_url( $cart_item_key ) {
	global $woocommerce;
	$cart_page_id = woocommerce_get_page_id('cart');
	if ($cart_page_id)
		return apply_filters('woocommerce_get_remove_url', $woocommerce->nonce_url( 'cart', add_query_arg('update_cart', $cart_item_key, get_permalink($cart_page_id))));
}  

/**
 * Remove from cart/update
 **/
function themify_update_cart_action() {
	global $woocommerce;
	
	// Update Cart
	if (isset($_GET['update_cart']) && $_GET['update_cart']  && $woocommerce->verify_nonce('cart')) :
		
		$cart_totals = $_GET['update_cart'];
		
		if (sizeof($woocommerce->cart->get_cart())>0) : 
			foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) :
				
        $update = $values['quantity'] - 1;
        
				if ($cart_totals == $cart_item_key) 
          $woocommerce->cart->set_quantity( $cart_item_key, $update);
				
			endforeach;
		endif;
		
		echo json_encode(array('deleted' => 'deleted'));
    die();
		
	endif;
}

/**
 * Add product variation value to callback lightbox
 **/
function themify_product_variation_vars(){
  global $available_variations, $woocommerce, $product, $post;
  echo '<div class="hide" id="themify_product_vars">'.json_encode($available_variations).'</div>';
}

?>