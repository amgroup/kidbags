<?php
/**
 * Single Product Thumbnails
 */

global $post, $woocommerce;
?>
<div class="product-thumbs">
	<?php	
		$thumb_id = get_post_thumbnail_id();
                if ( metadata_exists( 'post', $post->ID, '_product_image_gallery' ) ) {
                        $thumb_ids = get_post_meta( $post->ID, '_product_image_gallery', true );
                } else {
			// Backwards compat
			$attachment_ids = array_filter(
		                get_posts(
		                        array(
		                                'post_parent'    => $post->ID,
		                                'numberposts'    => -1,
		                                'post_type'      => 'attachment',
		                                'orderby'        => 'menu_order',
		                                'order'          => 'ASC',
		                                'post_mime_type' =>'image',
		                                'fields'         => 'ids'
		                                
		                        )
		                )
			);
			$thumb_ids = implode( ',', $attachment_ids );
		}

                $thumb_ids = array_diff( array_filter( explode(',', $thumb_ids)) , array($thumb_id) );
                
                if( sizeof($thumb_ids) ) {

		        $small_thumbnail_size = apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail');
		        $args = array(
		                'numberposts'   => -1,
			        'post_type' 	=> 'attachment',
			        'post__in'	=> $thumb_ids,
			        'orderby'	=> 'menu_order',
			        'order'		=> 'ASC'
		        );
		        $attachments = get_posts($args);
		        if ($attachments) {
			        $loop = 0;
			        $columns = apply_filters('woocommerce_product_thumbnails_columns', 3);
			        foreach ( $attachments as $attachment ) {
				
				        if (get_post_meta($attachment->ID, '_woocommerce_exclude_image', true)==1) continue;
				
				        $loop++;

				        $_post = & get_post( $attachment->ID );
				        $url = wp_get_attachment_url($_post->ID);
				        $post_title = esc_attr($_post->post_title);
				        $image = wp_get_attachment_image($attachment->ID, $small_thumbnail_size);
                
                echo '<figure class="product-image thumb">';
				        echo '<a href="'.$url.'" title="'.$post_title.'" rel="prettyPhoto[product]" class="product-extra-thumb ';
				        if ($loop==1 || ($loop-1)%$columns==0) echo 'first';
				        if ($loop%$columns==0) echo 'last';
				        echo '">'.$image.'</a>';
                echo '</figure>';

			        }
		        }
		}
	?>
</div>
<!-- /.product-thumbs -->
