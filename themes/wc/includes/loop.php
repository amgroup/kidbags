<?php if(!is_single()){ global $more; $more = 0; } //enable more link ?>

<?php 
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php themify_post_before(); //hook ?>

<article id="post-<?php the_ID(); ?>" <?php post_class("post clearfix " . $themify->get_categories_as_classes(get_the_ID())); ?>>
	
	<div class="post-inner">
		
		<?php themify_post_start(); //hook ?>

		<div class="post-content">
	
			<?php if($themify->hide_title != 'yes'): ?>
				<?php themify_before_post_title(); // Hook ?>
				<?php if($themify->unlink_title == 'yes'): ?>
					<h1 class="post-title"><?php the_title(); ?></h1>
				<?php else: ?>
					<h1 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
				<?php endif; //unlink post title ?>
				<?php themify_after_post_title(); // Hook ?>
			<?php endif; //post title ?>
			
			<?php if($themify->hide_date != 'yes'): ?>
				<p class="post-date-wrap">
					<time datetime="<?php the_time('o-m-d') ?>" class="post-date" pubdate>
						<span class="day"><?php the_time('j'); ?></span>
						<span class="month"><?php the_time('M'); ?></span>
						<span class="year"><?php the_time('Y'); ?></span>
					</time>
				</p>
			<?php endif; //post date ?>   
	
			<?php if($themify->hide_meta != 'yes'): ?>
				<p class="post-meta"> 
					<span class="post-author"><?php the_author_posts_link() ?> <span class="separator">/<span></span>
					<?php echo get_the_term_list( get_the_ID(), 'category', '<span class="post-category">', ', ', ' <span class="separator">/<span></span>' ); ?>
					<?php the_tags(' <span class="post-tag">', ', ', ' <span class="separator">/<span></span>'); ?>
					<?php  if( !themify_get('setting-comments_posts') && comments_open() ) : ?>
						<span class="post-comment"><?php comments_popup_link( __( '0 Comment', 'themify' ), __( '1 Comment', 'themify' ), __( '% Comments', 'themify' ) ); ?></span>
					<?php endif; //post comment ?>
				</p>
			<?php endif; //post meta ?>
			
			<?php
			if( $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height) ){
				if($themify->hide_image != 'yes'): ?>
					<?php themify_before_post_image(); // Hook ?>
					<figure class="post-image <?php echo $themify->image_align; ?>">
						<?php if( 'yes' == $themify->unlink_image): ?>
							<?php echo $post_image; ?>
						<?php else: ?>
							<a href="<?php echo $themify->get_featured_image_link(); ?>"><?php echo $post_image; ?></a>
						<?php endif; ?>
					</figure>
					<?php themify_after_post_image(); // Hook ?>
				<?php endif; //post image
			} ?> 
			
			<?php if($themify->display_content == 'excerpt'): ?>
		
				<?php the_excerpt(); ?>
		
			<?php elseif($themify->display_content == 'none'): ?>
		
			<?php else: ?>
	
				<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>
			
			<?php endif; //display content ?>
			
			<?php edit_post_link(__('Edit', 'themify'), '[', ']'); ?>
			
		</div>
		<!-- /.post-content -->
		<?php themify_post_end(); //hook ?>
		
	</div>
	
</article>
<?php themify_post_after(); //hook ?>

<!-- /.post -->