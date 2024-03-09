<?php
/**
 * Comments template
 *
 * @author pebas
 * @version 1.0.0
 */

if ( post_password_required() ) {
	return;
}
?>

<?php if ( have_comments() ): ?>
	<section class="single-listing-comments">
		<!-- Comments Listing -->
		<div id="comments" class="comments">
			<div class="comments-meta">
				<?php $comments_count = wp_count_comments( get_the_ID() ) ?>
				<h4 class="single-listing-section-title">
					<?php esc_html_e( 'Comments', 'lisner' ); ?>
					<span class="reviews-rating-count">(<?php echo esc_html( $comments_count->approved ); ?>)</span>
				</h4>

			</div>

			<div class="comments-box comments-box-ajax">
				<?php
				$args           = array(
					'short_ping'   => true,
					'type'         => 'all',
					'callback'     => 'pbs_list_comments',
					'end-callback' => pbs_post_functions::remove_last_div_from_comments(),
				);
				$comments_order = isset( $_POST['comments_filter'] ) ? $_POST['comments_filter'] : '';
				wp_list_comments( $args );
				?>

				<?php if ( 0 < get_comment_pages_count() ): ?>
					<!-- Pagination -->
					<?php echo pbs_theme_functions::format_comment_pagination(); ?>
				<?php endif; ?>
			</div>

		</div>
	</section>
<?php endif; ?>

<?php if ( comments_open() ) : ?>
	<!-- Single Listing / Comments Form -->
	<section class="single-listing-comments-form">
		<div class="comments">
			<h4 class="single-listing-section-title"><?php esc_html_e( 'Leave Comment', 'lisner' ) ?></h4>

			<div class="row">
				<div class="col-xl-10">
					<div class="review-form comment-form">
						<?php
						$comments_args                         = array();
						$comment_fields                        = array();
						$comment_fields['email']               = '<div class="input-group">
                            <i class="material-icons mf">' . esc_html( 'email' ) . '</i>
							<label class="hidden-xs-up" for="email">' . esc_html__( 'Email', 'lisner' ) . '
					    	    <span class="required hidden-xs-up">' . esc_html( '*' ) . '</span>
							</label>
                       		<input type="text" class="form-control" name="email" id="email" placeholder="' . esc_attr__( 'Your email address', 'lisner' ) . '">
                       	</div>';
						$comment_fields['author']              = '<div class="input-group">
                             <i class="material-icons mf">' . esc_html( 'person' ) . '</i>
                             <label class="hidden-xs-up" for="author">' . esc_html__( 'Name', 'lisner' ) . '
                                <span class="required hidden-xs-up">' . esc_html( '*' ) . '</span>
                             </label>
                          	 <input type="text" class="form-control" name="author" id="author" placeholder="' . esc_attr__( 'Your Name', 'lisner' ) . '">
                        </div>';
						$comments_args['title_reply']          = '';
						$comments_args['fields']               = apply_filters( 'comment_form_default_fields', $comment_fields );
						$comments_args['label_submit']         = esc_html__( 'Leave Comment', 'lisner' );
						$comments_args['cancel_reply_link']    = esc_html__( 'or cancel reply', 'lisner' );
						$comments_args['comment_field']        = '<div class="input-group input-group-textarea">
                                                            <i class="material-icons mf">' . esc_html( 'message' ) . '</i>
                                                           <label class="hidden-xs-up" for="comment">' . esc_html__( 'Comment', 'lisner' ) . '
                                                             <span class="required hidden-xs-up">' . esc_html( '*' ) . '</span>
                                                           </label>
												            <textarea rows="5" class="form-control" id="comment" name="comment" placeholder="' . esc_attr__( 'Please type your comment.', 'lisner' ) . '"></textarea>
        									            </div>';
						$comments_args['comment_notes_before'] = '';
						$comments_args['class_form']           = 'comment-form theme-form';
						comment_form( $comments_args );
						?>
						<div class="ajax-response">
							<?php if ( is_user_logged_in() ): ?>
								<div class="alert alert-success hidden"><?php esc_html_e( 'Your comment has been sent', 'lisner' ); ?></div>
							<?php else: ?>
								<div class="alert alert-success hidden"><?php esc_html_e( 'Your comment has been sent and is awaiting moderation', 'lisner' ); ?>
								</div>
							<?php endif; ?>
							<div class="alert alert-danger hidden"><?php esc_html_e( 'You missed one or more required fields', 'lisner' ); ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>