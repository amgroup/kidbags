<div id="product_single_wrapper" class="product product-<?php the_ID(); ?>">

        <?php woocommerce_template_single_shipping(); ?>

	<div class="product-imagewrap">
    	<?php themify_product_single_image_before(); //hook ?>
		<?php do_action('themify_single_product_image'); ?>
        <?php themify_product_single_image_end(); //hook ?>
	</div>

	<div class="product-content product-single-entry">
		<h3 class="product-title">
			<?php themify_product_single_title_before(); //hook ?>
			<?php the_title(); ?>
			<?php themify_product_single_title_end(); //hook ?>
		</h3>
		<div class="product-price">
        	<?php themify_product_single_price_before(); //hook ?>
      		<?php do_action('themify_single_product_price'); ?>
            <?php themify_product_single_price_end(); //hook ?>
    	</div>
		
    <?php do_action('themify_single_product_addtocart'); ?>
		<?php edit_post_link( __( 'Edit', 'themify' ), '[', ']'); ?>
	</div>
    
</div>
<!-- /.product -->
