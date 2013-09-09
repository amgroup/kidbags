<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!
 */
 
global $post; 
?>
  <li itemprop="reviews" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
			
      <div id="comment-<?php comment_ID(); ?>" class="comment_container">
      
      <p class="comment-author">
        <?php echo get_avatar( $GLOBALS['comment'], $size='38' ); ?> <cite><?php comment_author(); ?></cite>
        <br />
				<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo esc_attr( get_comment_meta( $GLOBALS['comment']->comment_ID, 'rating', true ) ); ?>">
  				<span style="width:<?php echo get_comment_meta( $GLOBALS['comment']->comment_ID, 'rating', true )*16; ?>px;">
            <span itemprop="ratingValue"><?php echo get_comment_meta( $GLOBALS['comment']->comment_ID, 'rating', true ); ?></span> <?php _e('out of 5', 'themify'); ?>
          </span>
  			</div>
        
        <br />
				<small class="comment-time"><?php echo get_comment_date('M j, Y'); ?> @ <?php comment_time('H:i A'); ?></small>
      </p>

			<div class="commententry">
				<?php comment_text(); ?>
			</div>
			
      <p class="reply">
        <?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </p>
      
      </div>