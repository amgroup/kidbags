<?php
// refer to local search engine from glodal search engines

if( ! preg_match( '/(\/\?post_type\=product\&search\-option\=product\&s\=)/', current_page_url(), $matches ) ) {
        $referer = $_SERVER["HTTP_REFERER"];
        if( preg_match( '/\/yandsearch\?(.*)text\=([^&]+)/', $referer, $search ) ) {
                header( "Location: http://" . $_SERVER['SERVER_NAME'] . "/?post_type=product&search-option=product&s=" . urlencode( preg_replace( '/"/', ' ', urldecode($search[2]) ) ) );
        }
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php
	global $woocommerce, $themify;
	$title = '';
	if( is_home() || is_front_page() ) {
		$title = get_the_title( woocommerce_get_page_id( 'shop' ) );
	} else if( is_tax() && get_queried_object()->description ) {
			$title = esc_html( strip_tags( get_queried_object()->description ) );
	} else if ( $post->post_type == 'product' ) {
			$title =  product_page_title( $post->ID );
	}
?>

<meta charset="<?php bloginfo( 'charset' ); ?>">
<title><?php echo $title ?></title>

<?php
/**
 *  Stylesheets and Javascript files are enqueued in theme-functions.php
 */
?>

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php themify_body_start(); //hook ?>
<div id="pagewrap">

	<div id="headerwrap-top-area">
	<div id="headerwrap-bottom-area">
	<div id="headerwrap">

		<?php themify_header_before(); //hook ?>
        
        <style>
        </style>

		<header id="header" class="pagewidth">

        	<?php themify_header_start(); //hook ?>
            
            <hgroup>
                <table style="border: 0px; border-collapse: collapse; width: 100%" >
                    <tr style="border: 0px;">
                        <td style="border: 0px; text-align:center; vertical-align: middle;">
                            <div class="site logo">
                                <a href="<?php echo home_url(); ?>/">
                                    <div class="holder"></div>
                                </a>
                            </div>
                       </td>
<style>
.site.description {margin:0;}
.site.top.sub.description {margin-bottom: 13px; color: #4AFFF8; font-family: Arial; font-weight: normal; font-size: 14px;}
.site.bottom.sub.description {margin-bottom: 13px; color: #06FC06; font-family: Arial; font-weight: normal; font-size: 14px;}
</style>
                        <td class="hidden-phone" style="border: 0px; text-align:center; vertical-align: middle; width: 100%;">
                            <div style="margin-top: 20px;">
								<p class="site top sub description">Только лучшие</p>
                                <h1 class="site description"><a href="<?php echo home_url(); ?>/">ШКОЛЬНЫЕ РАНЦЫ</a></h1>
								<p class="site bottom sub description">от проверенных производителей</p>
                            </div>
                        </td>

                        <td style="border: 0px; text-align:center; vertical-align: middle;">
                            <div style="text-align:right; margin-top: 20px; min-width: 160px;">
                                <a class="site phone" href="tel:+79250012361">8&nbsp;925&nbsp;001-23-61</a><br/>
                                <a class="site email" href="mailto:info@kidbags.ru">info<span style="font-size:1.3em;">@</span>kidbags.ru</a><br/>
                            </div>
                        </td>

                    </tr>
                </table>
            </hgroup>
	
			<!--hgroup style="background-image: url(http://bags.kidberries.com/wp-content/themes/pinshop/images/kidbags.png);  background-repeat: no-repeat; background-position: top 0px center; height: 146px;">
				<h2 id="site-description"><?php bloginfo('description'); ?></h2>
				<?php if(themify_get('setting-site_logo') == 'image' && themify_check('setting-site_logo_image_value')){ ?>
					<?php echo $themify->logo_image(); ?>
				<?php } else { ?>
					 <h1 id="site-logo" style="padding-top: 30px;"><a href="<?php echo home_url(); ?>/"><?php /*bloginfo('name'); */?> ШКОЛЬНЫЕ РАНЦЫ</a></h1>
				<?php } ?>
                <br/>
                <a style="margin-top: 10px !important; color: white;font-weight: normal; font-family: Oswald;font-size: 24px;" href="tel:+79250012361">8&nbsp;925&nbsp;001-23-61</a><br/>
                <a style="color: white;font-weight: normal; font-family: Oswald;" href="emailto:info@kidbags.ru">info<span style="font-size:1.3em;">@</span>kidbags.ru</a><br/>
			</hgroup-->


			<nav>
				<div id="menu-icon" class="mobile-button"></div>
				<?php if (function_exists('wp_nav_menu')) {
					wp_nav_menu(array('theme_location' => 'main-nav' , 'fallback_cb' => 'themify_default_main_nav' , 'container'  => '' , 'menu_id' => 'main-nav' , 'menu_class' => 'main-nav'));
				} else {
					themify_default_main_nav();
				} ?>
				<!-- /#main-nav -->
			</nav>

			<?php if(!themify_check('setting-exclude_search_form')): ?>
				<div id="searchform-wrap">
					<div id="search-icon" class="mobile-button"></div>
					<?php get_search_form(); ?>
				</div>
				<!-- /searchform-wrap -->
			<?php endif ?>
			
			<div class="social-widget">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('social-widget') ) ?>

				<?php if(!themify_check('setting-exclude_rss')): ?>
					<div class="rss"><a href="<?php if(themify_get('setting-custom_feed_url') != ""){ echo themify_get('setting-custom_feed_url'); } else { echo bloginfo('rss2_url'); } ?>">RSS</a></div>
				<?php endif; ?>
			</div>
			<!-- /.social-wrap -->

			<?php
			/* Include shopdock */
			//get_template_part( 'includes/shopdock');
            ?>
            <div id="shopdock">
                <div id="cart-tag">
                    <span id="cart-loader loading"></span> 
                    <span class="total-item"></span>
                </div>
            </div>
            <?php
                $woocommerce->add_inline_js( "
                    jQuery(document).ready(function() {
                        jQuery('#shopdock').load( woocommerce_params.ajax_url + '?action=get_dynamic_shopdock' + ' #shopdock > *', function() {
                            // jQuery('.is_desktop #cart-list').jScrollPane();
                            jQuery('#cart-wrap').hide();
                            jQuery('#shopdock-inner,#cart-tag,#cart-wrap').css({visibility:'visible'});
                        });
                    });"
                );
                themify_header_end(); //hook
            ?>

		</header>
		<!-- /#header -->

        <?php themify_header_after(); //hook ?>
	</div>
	</div>
	</div>
	<!-- /#headerwrap -->
	
	<div id="body" class="clearfix">
	<?php themify_layout_before(); //hook ?>