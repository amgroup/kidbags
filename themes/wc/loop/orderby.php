<?php
/**
 * Show options for ordering
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $wp_query, $wp;

if ( 1 == $wp_query->found_posts || ! woocommerce_products_will_display() )
  return;
?>
<?php themify_sorting_before(); //hook ?>
  <div class="orderby-wrap">
    <h4 class="sort-by"><?php _e('Sort by', 'woocommerce') ?></h4>
    <ul class="orderby">
      <?php
        $catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
          'menu_order' => __( 'Default', 'woocommerce' ),
          'popularity' => __( 'Popularity', 'woocommerce' ),
//          'rating'     => __( 'Average Rating', 'woocommerce' ),
          'date'       => __( 'Newness', 'woocommerce' ),
          'price'      => __( 'Price: Low to High', 'woocommerce' ),
          'price-desc' => __( 'Price: High to Low', 'woocommerce' )
        ) );

        if ( get_option( 'woocommerce_enable_review_rating' ) == 'no' )
          unset( $catalog_orderby['rating'] );

        foreach ( $catalog_orderby as $id => $name ) {
          $selected = ($_GET['orderby'] == $id) ? 'class="selected"': '';
          echo '<li ' . $selected . '><a href="'.home_url($wp->request).'?orderby='.$id.'">' . esc_attr( $name ) . '</a></li>';
        }
      ?>
    </ul>
  </div>
  <!-- /orderby-wrap -->
  <div class="sorting-gap"></div>

  <?php themify_sorting_after(); //hook ?>
  <?php
    // Keep query string vars intact
    foreach ( $_GET as $key => $val ) {
      if ( 'orderby' == $key )
        continue;
      echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
    }
  ?>