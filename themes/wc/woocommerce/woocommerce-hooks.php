<?php
/**
 * WooCommerce Custom Hook
 * woocommerce-hooks.php
 */

/* single product action */
add_action( 'themify_single_product_price', 'woocommerce_template_single_price', 10);
add_action( 'themify_single_product_addtocart', 'woocommerce_template_single_add_to_cart', 10);
add_action( 'themify_single_product_addtocart', 'themify_product_description', 10);
add_action( 'themify_single_product_addtocart', 'woocommerce_template_single_meta', 10);
add_action( 'themify_single_product_image', 'woocommerce_show_product_sale_flash', 20);
add_action( 'themify_single_product_image', 'woocommerce_show_product_images', 20);
add_action( 'themify_single_product_image', 'woocommerce_show_product_thumbnails', 20);

/* single product on lightbox action */
add_action( 'themify_single_product_image_ajax', 'woocommerce_show_product_sale_flash', 20);
add_action( 'themify_single_product_image_ajax', 'woocommerce_show_product_images', 20);
add_action( 'themify_single_product_ajax_content', 'woocommerce_template_single_add_to_cart', 10);

/* related product & reviews */
add_action( 'themify_after_single_product_summary', 'themify_review_content', 10);
if('' == themify_get('setting-related_products')){
	add_action( 'themify_after_single_product_summary', 'woocommerce_output_related_products', 10);
}

/* remove dock item hooks */
add_action( 'init', 'themify_update_cart_action');

// Remove WC sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Adjust markup on all WooCommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action( 'woocommerce_before_main_content', 'themify_before_content', 20);
add_action( 'woocommerce_after_main_content', 'themify_after_content', 20);

// Remove breadcrumb (we're using the themify breadcrumb)
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

// Remove pagination (we're using the Themify default pagination)
remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );
remove_action('shopdock_pagination', 'woocommerce_pagination', 10); // remove shopdock plugin pagination

/**
 * Show sorting bar
 * Remove sorting bar that come along shopdock plugin
 */
remove_action( 'woocommerce_pagination', 'woocommerce_catalog_ordering', 20 );
remove_action( 'woocommerce_before_shop_loop', 'shopdock_catalog_ordering', 8 );
if(class_exists('WC_Product_Factory')){
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination');
}
add_action( 'woocommerce_before_shop_loop', 'themify_catalog_ordering', 8 );

// Edit link
add_action( 'woocommerce_after_shop_loop_item', 'themify_edit_link');

// Add product variations
add_action('woocommerce_before_add_to_cart_form', 'themify_available_variations');

/**
 * Shopdock Dock bar in footer
 * Remove shopdock bar that along with plugin shopdock
 **/
remove_action('wp_footer', 'shopdock_dock_bar', 10);

/**
 * Remove shopdock plugin script
 **/
remove_action('wp_enqueue_scripts', 'shopdock_enqueue_scripts', 20);

?>