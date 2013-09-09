
    <form method="get" action="<?php bloginfo('url'); ?>/">
        <input type="hidden" class="search-type" name="post_type" value="product" />
        <input type="hidden" name="search-option" value="product" />
        <table style="width: 100%">
            <tr>
                <td style="width: 100%"><input style="width: 100%" maxlength="400" type="text" name="s" id="s"  placeholder="<?php _e('Например: розовый для девочки', 'woocommerce'); ?>" value="<?php echo htmlspecialchars( get_search_query() ); ?>" /></td>
                <td><input type="submit" value="<?php _e('Search','woocommerce');?>" /></td>
            </tr>
        </table>
    </form>
