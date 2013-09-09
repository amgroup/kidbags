<?php

/**
 * WooCommerce Template Override
 * woocommerce-template.php
 */

/**
 * WooCommerce Product Thumbnail
 **/
if (!function_exists('woocommerce_get_product_thumbnail')) {
	function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0 ) {
		global $post, $woocommerce;

		if (!$placeholder_width) $placeholder_width = $woocommerce->get_image_size('shop_catalog_image_width');
		if (!$placeholder_height) $placeholder_height = $woocommerce->get_image_size('shop_catalog_image_height');
    
    $html = '<figure class="product-image">';
    
		if ( has_post_thumbnail() ) {
     $html .= get_the_post_thumbnail($post->ID, $size);
    } 
     else {
      $html .= '<img src="http://placehold.it/'.$placeholder_width.'x'.$placeholder_height.'" alt="Placeholder" />'; 
    }
    
    $html .= '<span class="loading-product"></span>';
    $html .= '</figure>';
    
    return $html;
  }
}

/**
 * WooCommerce Single Product Content
 **/
if (!function_exists('woocommerce_single_product_content')) {
	function woocommerce_single_product_content( $wc_query = false ) {
		
		// Let developers override the query used, in case they want to use this function for their own loop/wp_query
		if (!$wc_query) {
			global $wp_query;
			$wc_query = $wp_query;
		}

		if ( $wc_query->have_posts() ) while ( $wc_query->have_posts() ) {
            $wc_query->the_post();
			
			do_action('woocommerce_before_single_product');
			get_template_part('includes/loop-product', 'single');
            woocommerce_get_template('single-product/tabs_content.php');

            do_action('themify_after_single_product_summary');
			do_action('woocommerce_after_single_product');
			
		}
	}
}

/**
 * WooCommerce Single Product Content with AJAX
 **/
if (!function_exists('woocommerce_single_product_content_ajax')) {
	function woocommerce_single_product_content_ajax( $wc_query = false ) {
		
		// Let developers override the query used, in case they want to use this function for their own loop/wp_query
		if (!$wc_query) {
			global $wp_query;
			
			$wc_query = $wp_query;
		}
		
		if ( $wc_query->have_posts() ) while ( $wc_query->have_posts() ) : $wc_query->the_post(); ?>
			
			<?php //do_action('woocommerce_before_single_product'); ?>
		  

				<div id="product_single_wrapper" class="product product-<?php the_ID(); ?> single product-single-ajax">
					<div class="product-imagewrap">
            <?php do_action('woocommerce_after_shop_loop_item'); ?>
						<?php do_action('themify_single_product_image_ajax'); ?>
					</div>
					<div class="product-content product-single-entry">
						<h3 class="product-title"><?php the_title(); ?></h3>
						<div class="product-price">
              <?php do_action('themify_single_product_price'); ?>
            </div>
						
            <?php do_action('themify_single_product_ajax_content'); ?>
						
					</div>
				</div>
				<!-- /.product -->

				
        <?php //do_action('themify_after_single_product_summary'); ?>

			<?php //do_action('woocommerce_after_single_product'); ?>
			
		<?php endwhile;
	
	}
}

/**
 * WooCommerce Single Product description
 **/
if(!function_exists('themify_product_description')){ 
  function themify_product_description(){
    the_content();
  }
}

/**
 * Shopdock review content in single page
 **/
if (!function_exists('themify_review_content')) {
	function themify_review_content() {
		woocommerce_get_template('single-product/reviews_content.php');
	}
}

?>