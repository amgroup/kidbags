<?php
  if(isset($_GET['ajax']) && $_GET['ajax']){
    woocommerce_single_product_content_ajax();
  }
  else{
    get_header('shop');
    do_action('woocommerce_before_main_content');

    woocommerce_single_product_content(); // it is overrided theme function

    do_action('woocommerce_after_main_content');
    do_action('woocommerce_sidebar');
    get_footer('shop');
}
?>