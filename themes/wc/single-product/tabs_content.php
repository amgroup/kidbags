<?php
global $post, $product;


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    $tabs = apply_filters( 'woocommerce_product_tabs', array() );
    if ( ! empty( $tabs ) ) : ?>
    <!-- .product.tabs -->
    <div class="woocommerce-tabs product tabs">

        <h2><?php _e( 'Additional Information', 'woocommerce' ); ?></h2>
        <div><?php echo do_shortcode( $post->post_excerpt ); ?></div>

        <h2><?php _e( 'About Product', 'woocommerce' ); ?></h2>
        <?php $product->list_attributes(); ?>

    <?php /*/ ?>
        <h2><?php echo esc_html( apply_filters('woocommerce_product_description_heading', __( 'Product Description', 'woocommerce' ) ) ); ?></h2>
        <div><?php the_content(); ?></div>
    <?php /*/ ?>
    <?php /*/ ?>
        <?php foreach ( $tabs as $key => $tab ) : ?>
            <?php //echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?>
            <?php call_user_func( $tab['callback'], $key, $tab ); echo $tab['callback']; ?>
        <?php endforeach; ?>
        <ul class="tabs">
            <?php foreach ( $tabs as $key => $tab ) : ?>
                <li class="tab <?php echo $key ?>_tab">
                    <a href="#tab-<?php echo $key ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php foreach ( $tabs as $key => $tab ) : ?>
            <div class="panel entry-content" id="tab-<?php echo $key ?>">
                <?php call_user_func( $tab['callback'], $key, $tab ) ?>
            </div>
        <?php endforeach; ?>
    <?php /*/ ?>
    </div>
    <script>jQuery(function(){jQuery( ".woocommerce-tabs.product.tabs" ).accordion();});</script>
    <!-- /.product.tabs -->
<?php endif; ?>
<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"><?php echo product_page_title( $product->ID ); ?></a><br/>
