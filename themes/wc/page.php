<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify;

$no_sidebar = 0;
if( preg_match('/(\[no.?sidebar\])/i', $post->post_content, $matches) ) {
	$post->post_content = preg_replace( '/(\[no.?sidebar\])/i', '', $post->post_content);
	$no_sidebar = 1;
}
?>

<?php if(is_front_page() && !is_paged()){ get_template_part( 'includes/product-slider'); } ?>

<!-- layout-container -->
<div id="layout" class="pagewidth clearfix">	

	<?php themify_content_before(); //hook ?>
	<!-- content -->
	<div id="content" class="clearfix<?php if($no_sidebar) echo ' no-sidebar';?>">
    	<?php themify_content_start(); //hook ?>
	
		<?php 
		/////////////////////////////////////////////
		// 404							
		/////////////////////////////////////////////
		?>
		<?php if(is_404()): ?>
			<p><?php _e( 'Page not found.', 'woocommerce' ); ?></p>
			<h1 class="page-title"><?php _e( 'Please try to search.', 'woocommerce' ); ?></h1>
			<?php get_template_part( 'wide-search-form'); ?>
		<?php endif; ?>

		<?php 
		/////////////////////////////////////////////
		// PAGE							
		/////////////////////////////////////////////
		?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
			<!-- page-title -->
			<?php if($themify->page_title != "yes"): ?> 
				<h1 class="page-title"><?php _e( $post->post_title, 'woocommerce' ); ?></h1>

			<?php endif; ?>	
			<!-- /page-title -->

			<div class="page-content">
			
				<?php
                    $content = get_the_content($more_link_text, $stripteaser);
                    $content = apply_filters('the_content', $content);
                    $content = str_replace(']]>', ']]&gt;', $content);

                    echo $content;
                ?>
				
				<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:','themify').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				
				<?php edit_post_link(__('Edit','themify'), '[', ']'); ?>
				
				<!-- comments -->
				<?php if(!themify_check('setting-comments_pages') && '' == $themify->query_category): ?>
					<?php comments_template(); ?>
				<?php endif; ?>
				<!-- /comments -->
			
			</div>
			<!-- /.post-content -->
		
		<?php endwhile; endif; ?>
		
		<?php 
		/////////////////////////////////////////////
		// Query Category							
		/////////////////////////////////////////////
		
		if($themify->query_category != ""): ?>
		
			<?php if(themify_get('section_categories') != 'yes'): ?>
			
				<?php query_posts(apply_filters('themify_query_posts_page_args', 'cat='.$themify->query_category.'&posts_per_page='.$themify->posts_per_page.'&paged='.$themify->paged)); ?>
				
					<?php if(have_posts()): ?>
						
						<!-- loops-wrapper -->
						<div class="loops-wrapper">

							<?php while(have_posts()) : the_post(); ?>
								
								<?php get_template_part('includes/loop', 'query'); ?>
						
							<?php endwhile; ?>
												
						</div>
						<!-- /loops-wrapper -->

						<?php if ($page_navigation != "yes"): ?>
							<?php get_template_part( 'includes/pagination'); ?>
						<?php endif; ?>
								
					<?php else : ?>	
					
					<?php endif; ?>

			<?php else: ?>
				
				<?php $categories = explode(",",str_replace(" ","",$themify->query_category)); ?>
				
				<?php foreach($categories as $category): ?>
					
					<?php $cats = get_categories(array('include'=>$category, 'orderby' => 'id')); ?>
					
					<?php foreach($cats as $cat): ?>
						
						<?php query_posts(apply_filters('themify_query_posts_page_args', 'cat='.$cat->cat_ID.'&posts_per_page='.$themify->posts_per_page.'&paged='.$themify->paged)); ?>
				
						<?php if(have_posts()): ?>
							
							<!-- category-section -->
							<div class="category-section clearfix <?php echo $cat->cat_name; ?>-category">
	
								<h3 class="category-section-title"><a href="<?php echo esc_url( get_category_link($cat->cat_ID) ); ?>" title="<?php _e('View more posts', 'themify'); ?>"><?php echo $cat->cat_name; ?></a></h3>
	
								<!-- loops-wrapper -->
								<div class="loops-wrapper <?php echo $post_layout; ?>">
									<?php while(have_posts()) : the_post(); ?>
										
										<?php get_template_part('includes/loop', 'query'); ?>
								
									<?php endwhile; ?>
								</div>
								<!-- /loops-wrapper -->
	
								<?php if ($themify->page_navigation != "yes"): ?>
									<?php get_template_part( 'includes/pagination'); ?>
								<?php endif; ?>
	
							</div>
							<!-- /category-section -->
									
						<?php else : ?>	
						
						<?php endif; ?>
					
					<?php endforeach; ?>
				
				<?php endforeach; ?>
			
			<?php endif; ?>
			
		<?php endif; ?>
		<?php wp_reset_query(); ?>
        
        <?php themify_content_end(); //hook ?>
	</div>
	<!-- /content -->
    <?php themify_content_after() //hook; ?>

	<?php 
	/////////////////////////////////////////////
	// Sidebar							
	/////////////////////////////////////////////
	if ($themify->layout != "sidebar-none" and ! $no_sidebar): get_sidebar(); endif; ?>

</div>
<!-- /layout-container -->
	
<?php get_footer(); ?>