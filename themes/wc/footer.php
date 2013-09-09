	<?php themify_layout_after(); //hook ?>
	</div>
	<!-- /body -->
		
	<div id="footerwrap-top-area">
	<div id="footerwrap-bottom-area">
	<div id="footerwrap">

		<?php themify_footer_before(); //hook ?>
		<footer id="footer" class="pagewidth clearfix">
			<?php themify_footer_start(); //hook ?>

			<?php get_template_part( 'includes/footer-widgets'); ?>

			<p class="back-top"><a href="#header">&uarr;</a></p>
			
			<?php if (function_exists('wp_nav_menu')) {
				wp_nav_menu(array('theme_location' => 'footer-nav' , 'fallback_cb' => '' , 'container'  => '' , 'menu_id' => 'footer-nav' , 'menu_class' => 'footer-nav')); 
			} ?>
			
			<!-- footer-text -->
			<div class="footer-text clearfix">
				<div class="one"><?php if(themify_get('setting-footer_text_left') != ""){ echo themify_get('setting-footer_text_left'); } else { echo apply_filters('themify_footer_text_one', '&copy; <a href="'.home_url().'">'.get_bloginfo('name').'</a> '.date('Y')); } ?></div>
			</div>
			<!-- /footer-text -->

			<?php themify_footer_end(); //hook ?>
		</footer>
		<!-- /#footer -->
		<?php themify_footer_after(); //hook ?>
	</div>
	<!-- /#footerwrap -->
	
</div>
</div>
</div>
<!-- /#pagewrap -->

<?php
/**
 *  Stylesheets and Javascript files are enqueued in theme-functions.php
 */
?>

<!-- wp_footer -->
<?php wp_footer(); ?>
<?php themify_body_end(); //hook ?>
        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter21302689 = new Ya.Metrika({id:21302689,
                            webvisor:true,
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true,
                            trackHash:true});
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
        </script>
        <noscript><div><img src="//mc.yandex.ru/watch/21302689" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->
</body>
</html>