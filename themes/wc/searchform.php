<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
	<input type="hidden" class="search-type" name="post_type" value="product" />
	<input type="text" name="s" id="s"  placeholder="<?php _e('Search', 'woocommerce'); ?>">
	<div class="search-option">
		<input class="search-blog" type="radio" name="search-option" value="post"> <label for="search-blog"><?php _e('Blog', 'woocommerce'); ?></label>
		<input class="search-shop" type="radio" name="search-option" value="product"> <label for="search-shop"><?php _e('Shop', 'woocommerce'); ?></label>
	</div>
</form>