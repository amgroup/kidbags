<?php global $woocommerce; ?>
<?php if ( comments_open() && !themify_get('setting-product_reviews') ) : ?>
<div id="reviews" class="commentwrap">

<?php
if ( false == ($count = get_transient( 'count' )) ){
	$count = $wpdb->get_var("
		SELECT COUNT(meta_value) FROM $wpdb->commentmeta 
		LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.\"comment_ID\" = $wpdb->comments.\"comment_ID\"
		WHERE meta_key = 'rating'
		AND comment_post_id = $post->ID
		AND comment_approved = '1'
		AND meta_value > 0
	");
	//cache database query at least for 5 minutes
	set_transient('count', $count, 60*5);
}	
if ( false == ($rating = get_transient( 'rating' )) ){
	$rating = $wpdb->get_var("
		SELECT SUM(meta_value) FROM $wpdb->commentmeta 
		LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.\"comment_ID\" = $wpdb->comments.\"comment_ID\"
		WHERE meta_key = 'rating'
		AND comment_post_id = $post->ID
		AND comment_approved = '1'
	");
	//cache database query at least for 5 minutes
	set_transient('rating', $rating, 60*5);
}
?>

  <h4 class="comment-title"><?php comments_number(__('No Reviews','woocommerce'), __('One Review','woocommerce'), __('% Reviews','woocommerce') ); ?></h4>
  
  <?php  
    if ( have_comments() ) :
    
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="pagenav top clearfix">
        <?php paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') );?>
    	</nav> <!-- / .pagenav -->
  <?php endif; ?> 
  
    <button class="add-reply-js mbm"><?php _e('Add Review', 'woocommerce'); ?></button>
  
  <?php $title_reply = __('Add a review', 'woocommerce'); ?>
  
  <?php else: ?>
  
  <?php 
  $title_reply = __('Be the first to review', 'woocommerce').' &ldquo;'.$post->post_title.'&rdquo;';
		
  echo '<p>'.__('There are no reviews yet, would you like to <a href="#respond" class="reply-review">submit yours</a>?', 'woocommerce').'</p>';
  ?>
  
  <?php endif; ?>
  
<?php 
	
	if ( $count>0 ) :
		
		$average = number_format($rating / $count, 2);
		
	endif;

	$title_reply = '';
	
	$commenter = wp_get_current_commenter();
	
	comment_form(array(
		'title_reply' => $title_reply,
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'fields' => array(
			'author' => '<p class="comment-form-author">' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /> '. 
                  '<label for="author">' . __( 'Name', 'woocommerce' ) . ' <small>'.__('(Required)').'</small>'.'</label> '.'</p>',
			'email'  => '<p class="comment-form-email">'. '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" />'.
                  '<label for="email">' . __( 'Mail', 'woocommerce' ) . ' <small>'.__('(Required)').'</small>'. '</label> ' .
   			          '</p>',
		),
		'label_submit' => __('Submit Review', 'woocommerce'),
		'logged_in_as' => '',
		'comment_field' => '
			<p class="comment-form-rating"><select name="rating" id="rating">
				<option value="">'.__('Rate...', 'woocommerce').'</option>
				<option value="5">'.__('Perfect', 'woocommerce').'</option>
				<option value="4">'.__('Good', 'woocommerce').'</option>
				<option value="3">'.__('Average', 'woocommerce').'</option>
				<option value="2">'.__('Not that bad', 'woocommerce').'</option>
				<option value="1">'.__('Very Poor', 'woocommerce').'</option>
			</select></p>
			<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>'
			. $woocommerce->nonce_field('comment_rating', true, false)
	));
  
  if ( have_comments() ) : 

		echo '<ol class="commentlist">';
		
		wp_list_comments( array( 'callback' => 'woocommerce_comments' ) );

		echo '</ol>';
		
	endif;
	
?>

<?php  
    if ( have_comments() ) :
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="pagenav bottom clearfix">
        <?php paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') );?>
    	</nav> <!-- / .pagenav -->
  <?php endif; endif; ?>

</div>
<!-- /#reviews -->
<?php endif; ?>
