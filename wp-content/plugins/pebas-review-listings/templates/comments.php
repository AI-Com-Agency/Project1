<?php
/**
 * Reviews template
 *
 * @author pebas
 * @version 1.0.0
 */

if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
	die ( 'Please do not load this page directly. Thanks!' );
}
if ( post_password_required() ) {
	return;
}
// pebas_review_listings_functions()->get_google_reviews( 'ChIJx-G8w1kQW0cR7tgoSG0RCoU' );
?>
<?php if ( comments_open() ) : ?>

	<?php if ( have_comments() ): ?>
		<section class="single-listing-reviews">
			<!-- Comments Listing -->
			<div id="comments" class="reviews">
				<div class="reviews-meta">
					<?php $comments_count = wp_count_comments( get_the_ID() ) ?>
					<h4 class="single-listing-section-title">
						<?php esc_html_e( 'Reviews', 'pebas-review-listings' ); ?>
					</h4>

					<!-- Single Listing Review / Meta -->
					<div class="lisner-listing-meta">

						<?php $has_review = false; ?>
						<?php if ( lisner_helper::is_plugin_active( 'pebas-review-listings' ) ): ?>
							<?php $has_review = true; ?>
							<!-- Listing / Rating -->
							<?php $avg_rating = pebas_review_listings_functions::get_average_rating( get_the_ID() ); ?>
							<div class="lisner-listing-meta-rating color-<?php echo '0.0' == $avg_rating ? esc_attr( 'disabled' ) : esc_attr( 'success' ); ?>">
								<?php echo esc_html( $avg_rating ); ?>
							</div>
							<div class="reviews-rating">
								<span class="reviews-rating-stars"><?php echo pebas_review_listings_functions::generate_review_starts( get_the_ID() ); ?></span>
								<span class="reviews-rating-count">
                                    (<?php echo esc_html( $comments_count->approved ); ?>)</span>
							</div>
						<?php endif; ?>
					</div>
					<div class="single-listing-main-meta-action">
						<a class="animate"
						   href="#respond"><?php esc_html_e( 'Leave Review', 'pebas-review-listings' ); ?><i
									class="material-icons rotate--90"><?php echo esc_attr( 'subdirectory_arrow_left' ); ?></i></a>
					</div>

				</div>

				<ul class="comments-box comments-box-ajax">
					<?php
					$args                      = array(
						'type'         => 'all',
						'callback'     => 'lisner_review_list_comments',
						'end-callback' => pbs_post_functions::remove_last_div_from_comments(),
					);
					$args['reverse_top_level'] = true;
					wp_list_comments( $args );
					?>

					<?php if ( 0 < get_comment_pages_count() ): ?>
						<!-- Pagination -->
						<?php echo pbs_theme_functions::format_comment_pagination(); ?>
					<?php endif; ?>
				</ul>

			</div>
		</section>
	<?php endif; ?>

	<!-- Single Listing / Comments Form -->
	<section class="single-listing-comments-form">
		<div class="comments">
			<h4 class="single-listing-section-title"><?php esc_html_e( 'Leave Review', 'pebas-review-listings' ) ?></h4>

			<div class="row">
				<div class="col-xl-10">
					<div class="review-form">
						<?php
						$ratings = '<div class="review-ratings"><input type="hidden" name="post_id" value="' . esc_attr( get_the_ID() ) . '"/>';
						$col     = 5;
						$ratings .= '<div class="input-group form-group input-group-alt"><p class="comment-review">
                                <i class="material-icons mf">' . esc_html( 'star' ) . '</i>
					    		<label>' . esc_html__( 'Rating', 'pebas-review-listings' ) . ' <span class="required">*</span></label>
					    		<input type="hidden" id="review" name="review_rating" value="1">
					    		<input type="hidden" id="user_id" name="user_id" value="' . get_current_user_id() . '"/>
					    		<span class="bottom-ratings">
					    			<i class="material-icons clicked">' . esc_html( 'star' ) . '</i>
					    			<i class="material-icons">' . esc_html( 'star_border' ) . '</i>
					    			<i class="material-icons">' . esc_html( 'star_border' ) . '</i>
					    			<i class="material-icons">' . esc_html( 'star_border' ) . '</i>
					    			<i class="material-icons">' . esc_html( 'star_border' ) . '</i>
					    		</span>
					    		<span class="bottom-ratings-count"><span class="rating-current">' . esc_html( '1' ) . '</span>' . esc_html__( 'out of', 'pebas-review-listings' ) . '<span>5</span></span>
					    	</p></div><span class="notice hidden">' . esc_html__( 'You must choose rating!', 'pebas-review-listings' ) . '</span></div>';
						?>
						<?php
						$comments_args                         = array();
						$comment_fields                        = array();
						$comment_fields['email']               = '<div class="input-group">
                            <i class="material-icons mf">' . esc_html( 'email' ) . '</i>
							<label class="hidden-xs-up" for="email">' . esc_html__( 'Email', 'pebas-review-listings' ) . '
					    	    <span class="required hidden-xs-up">' . esc_html( '*' ) . '</span>
							</label>
                       		<input type="text" class="form-control" name="email" id="email" placeholder="' . esc_html__( 'Your email address', 'pebas-review-listings' ) . '">
                       	</div>';
						$comment_fields['author']              = '<div class="input-group">
                             <i class="material-icons mf">' . esc_html( 'person' ) . '</i>
                             <label class="hidden-xs-up" for="author">' . esc_html__( 'Name', 'pebas-review-listings' ) . '
                                <span class="required hidden-xs-up">' . esc_html( '*' ) . '</span>
                             </label>
                          	 <input type="text" class="form-control" name="author" id="author" placeholder="' . esc_html__( 'Your Name', 'pebas-review-listings' ) . '">
                        </div>';
						$comments_args['title_reply']          = '';
						$comments_args['fields']               = apply_filters( 'comment_form_default_fields', $comment_fields );
						$comments_args['label_submit']         = esc_html__( 'Leave Review', 'pebas-review-listings' );
						$comments_args['cancel_reply_link']    = esc_html__( 'or cancel reply', 'pebas-review-listings' );
						$comments_args['comment_field']        = $ratings . '<div class="input-group input-group-textarea">
                                                            <i class="material-icons mf">' . esc_html( 'message' ) . '</i>
                                                           <label class="hidden-xs-up" for="comment">' . esc_html__( 'Review', 'pebas-review-listings' ) . '
                                                             <span class="required hidden-xs-up">' . esc_html( '*' ) . '</span>
                                                           </label>
												            <textarea rows="5" class="form-control" id="comment" name="comment" placeholder="' . esc_html__( 'Remember and note that your review is just your experience.', 'pebas-review-listings' ) . '"></textarea>
        									            </div>';
						$comments_args['comment_notes_before'] = '';
						$comments_args['class_form']           = 'comment-form theme-form';
						comment_form( $comments_args );
						?>
						<div class="ajax-response">
							<?php if ( is_user_logged_in() ): ?>
								<div class="alert alert-success hidden"><?php esc_html_e( 'Your comment has been sent', 'pebas-review-listings' ); ?></div>
							<?php else: ?>
								<div class="alert alert-success hidden"><?php esc_html_e( 'Your comment has been sent and is awaiting moderation', 'pebas-review-listings' ); ?>
								</div>
							<?php endif; ?>
							<div class="alert alert-danger hidden"><?php esc_html_e( 'You missed one or more required fields', 'pebas-review-listings' ); ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php endif; ?>
