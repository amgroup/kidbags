<?php
/**
 * Single Product Image
 */

global $post, $woocommerce;

$placeholder_width = $woocommerce->get_image_size('shop_single_image_width');
$placeholder_height = $woocommerce->get_image_size('shop_single_image_height');
    
?>
<figure class="product-image">

	<?php if (has_post_thumbnail()) : $thumb_id = get_post_thumbnail_id(); $large_thumbnail_size = apply_filters('single_product_large_thumbnail_size', 'shop_single'); ?>

		<a itemprop="image" href="<?php echo wp_get_attachment_url($thumb_id); ?>" rel="prettyPhoto[product]" title="<?php echo get_the_title( $thumb_id ); ?>"><?php echo get_the_post_thumbnail($post->ID, $large_thumbnail_size) ?></a>

	<?php else : ?>
	
		<img src="http://placehold.it/<?php echo $placeholder_width; ?>x<?php echo $placeholder_height?>" alt="Placeholder">
	
	<?php endif; ?>
	<span class="loading-product"></span>
</figure>