<?php
/**
 * Loop Add to Cart
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
?>

<?php if ( ! $product->is_in_stock() ) : ?>

<?php else : ?>

	<?php
		$link = array(
			'url'   => '',
			'label' => '',
			'class' => ''
		);

		$handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->product_type, $product );

		switch ( $handler ) {
			case "variable" :
				$link['url'] 	= apply_filters( 'variable_add_to_cart_url', get_permalink( $product->id ) );
				$link['label'] 	= apply_filters( 'variable_add_to_cart_text', __( 'Select options', 'woocommerce' ) );
				$variant = true;
			break;
			case "grouped" :
				$link['url'] 	= apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->id ) );
				$link['label'] 	= apply_filters( 'grouped_add_to_cart_text', __( 'View options', 'woocommerce' ) );
				$variant = true;
			break;
			case "external" :
				$link['url'] 	= apply_filters( 'external_add_to_cart_url', get_permalink( $product->id ) );
				$link['label'] 	= apply_filters( 'external_add_to_cart_text', __( 'Read More', 'woocommerce' ) );
			break;
			default :
				if ( $product->is_purchasable() ) {
					$link['url'] 	= apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
					$link['label'] 	= apply_filters( 'add_to_cart_text', __( 'Add to cart', 'woocommerce' ) );
					$link['class']  = apply_filters( 'add_to_cart_class', 'custom_add_to_cart_button' );
				} else {
					$link['url'] 	= apply_filters( 'not_purchasable_url', get_permalink( $product->id ) );
					$link['label'] 	= apply_filters( 'not_purchasable_text', __( 'Read More', 'woocommerce' ) );
				}
			break;
		}

		$prefix_link = (get_option('permalink_structure') != '') ? '?' : '&';
	  $link['url'] = (isset($variant) && $variant == true) ? $link['url'].$prefix_link.'ajax=true&width=616&height=326' : $link['url'];
	  $rel = (isset($variant) && $variant == true) ? 'rel="prettyPhoto[ajax]"': '';
	  
	  if(isset($variant) && $variant == true && is_product() == true) { return; }
	  
	  if(isset($variant) && $variant == true){
	  	$link['class'] = 'variable-link';
	  	echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf('<p class="add-item-wrap select-options"><a href="%s" '.$rel.' data-product_id="%s" data-product_sku="%s" class="add-item variant-lightbox %s product_type_%s">%s</a></p>', esc_url( $link['url'] ), esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->product_type ), esc_html( $link['label'] ) ), $product, $link );
	  }

		echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf('<p class="add-item-wrap"><a href="%s" '.$rel.' data-product_id="%s" data-product_sku="%s" class="add-item %s product_type_%s">%s</a></p>', esc_url( $link['url'] ), esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->product_type ), esc_html( $link['label'] ) ), $product, $link );

	?>

<?php endif; ?>