<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_review_listings_functions
 */
class pebas_review_listings_functions {

	protected static $_instance = null;

	/**
	 * @return null|pebas_review_listings_functions
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_review_listings_functions constructor.
	 */
	public function __construct() {
		// add actions
		add_action( 'init', array( $this, 'enable_comments' ) );
		add_action( 'lisner_review_comments_template', array( $this, 'review_comments_template' ) );
		add_action( 'comment_post', array( $this, 'save_comment_meta_data' ) );
		add_action( 'comment_text', array( $this, 'admin_comments_style' ) );

		// remove images when deleting comment too
		add_action( 'delete_comment', array( $this, 'delete_comment_images' ), 10, 2 );

		// add form enctype to support image uploads
		add_action( 'wp_footer', array( $this, 'form_enctype_switch' ) );

		// review comment likes ajax
		add_action( 'lisner_ajax_comment_like', array( $this, 'like_comment' ) );
		add_action( 'lisner_ajax_nopriv_comment_like', array( $this, 'like_comment' ) );

		// add filters
		add_filter( 'comment_form_field_comment', array( $this, 'review_form_image_upload' ), 10, 1 );

		// add image sizes
		add_image_size( 'review_image', 194, 140, true );
	}

	/**
	 * Make sure that comments are supported by WP Job Manager job_listing post type
	 */
	public function enable_comments() {
		add_post_type_support( 'job_listing', 'comments' );
	}

	public function admin_comments_style( $comment = '' ) {
		$comment_id = get_comment_ID();
		$rating     = get_comment_meta( $comment_id, 'review_rating', true );
		if ( isset( $rating ) && ! empty( $rating ) ) {
			$comment .= '<div class="review-rating">' . self::generate_review_starts( $comment_id, $rating ) . '</div>';
		}
		$comment .= '<div class="review-likes-wrapper">';
		$likes   = get_comment_meta( $comment_id, 'review_likes', true );
		if ( isset( $likes ) && ! empty( $likes ) ) {
			$comment .= '<div class="review-likes"><i class="material-icons mf">' . esc_html( 'sentiment_satisfied' ) . '</i>' . $likes . '</div>';
		}
		$dislikes = get_comment_meta( $comment_id, 'review_dislikes', true );
		if ( isset( $dislikes ) && ! empty( $dislikes ) ) {
			$comment .= '<div class="review-likes dislike"><i class="material-icons mf">' . esc_html( 'sentiment_dissatisfied' ) . '</i>' . $dislikes . '</div>';
		}
		$comment .= '</div>';

		$images = get_comment_meta( $comment_id, 'review_gallery', '' );
		if ( isset( $images ) && ! empty( $images ) ) :
			$comment .= '<div class="comment-images lisner-gallery">';
			foreach ( $images as $image ) :
				$image_full = wp_get_attachment_image_src( $image, 'full' );
				$image_src  = wp_get_attachment_image_src( $image, 'review_image' );
				$comment    .= '<a href="' . esc_url( $image_full[0] ) . '"><img
								src="' . esc_url( $image_src[0] ) . '" alt=""></a>';
			endforeach;
			$comment .= '</div>';
		endif;


		return $comment;
	}

	/**
	 * Change default comments template
	 */
	public function review_comments_template() {
		add_filter( 'comments_template', function ( $template ) {
			return PEBAS_RV_DIR . 'templates/comments.php';
		} );
		comments_template( '', true );
	}


	/**
	 * Add image uploading field to default comment template
	 *
	 * @return string
	 */
	public function review_form_image_upload( $comment ) {
		if ( ! is_singular( 'job_listing' ) ) {
			return $comment;
		}
		$description = sprintf( esc_html__( 'Maximum image size: %s', 'pebas-review-listings' ),
			size_format( wp_max_upload_size() ) );
		$comment     .= '<div class="input-group input-group-alt">
                             <i class="material-icons mf">' . esc_html( 'cloud_upload' ) . '</i>
                             <label class="hidden-xs-up" for="review_gallery">' . esc_html__( 'Review Images',
				'pebas-review-listings' ) . '
                                <span class="required hidden-xs-up">' . esc_html( '*' ) . '</span>
                             </label>
                          	 <input class="lisner-input-file form-control" 
                          	 data-multiple-caption="' . sprintf( esc_html__( '%s files selected',
				'pebas-review-listings' ), '{count}' ) . '"
                          	 type="file" name="review_gallery[]" id="review_gallery" multiple accept="image/*">
                             <span class="file-upload-label">' . esc_html__( 'Browse', 'pebas-review-listings' ) . '</span>
                          	 <span class="file-upload-files"></span> <span class="description">' . $description . '</span>
                        </div>';

		return $comment;
	}

	/**
	 * Update comment metadata
	 *
	 * @param $comment_id
	 */
	public function save_comment_meta_data( $comment_id ) {
		// update images
		$this->save_comment_images( $comment_id );

		// update rating
		update_comment_meta( $comment_id, 'review_rating', $_POST['review_rating'] );
	}

	/**
	 * Upload multiple comment images
	 *
	 * @param $comment_id
	 */
	public function save_comment_images( $comment_id ) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		$files = $_FILES['review_gallery'];
		foreach ( $files['name'] as $key => $value ) {
			if ( $files['name'][ $key ] ) {
				$file   = array(
					'name'     => $files['name'][ $key ],
					'type'     => $files['type'][ $key ],
					'tmp_name' => $files['tmp_name'][ $key ],
					'error'    => $files['error'][ $key ],
					'size'     => $files['size'][ $key ]
				);
				$_FILES = array( 'review_gallery' => $file );
				$size   = getimagesize( $file['tmp_name'] );
				if ( $size ) {
					$attachment_id = media_handle_upload( 'review_gallery', $comment_id );

					add_comment_meta( $comment_id, 'review_gallery', $attachment_id, false );
				}
			}
		}
	}

	/**
	 * Delete comment images on comment delete
	 *
	 * @param $comment_id
	 * @param $comment
	 */
	public function delete_comment_images( $comment_id, $comment ) {
		global $wpdb;
		// get comment images from database
		$image_ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT meta_value FROM {$wpdb->commentmeta} WHERE meta_key = 'review_gallery' AND comment_id = %s",
				$comment_id
			)
		);

		// delete comment images
		foreach ( $image_ids as $id ) {
			wp_delete_attachment( $id, true );
		}

		// delete comment meta associated with images
		delete_comment_meta( $comment_id, 'review_images' );

	}

	/**
	 * Enable from image uploading
	 */
	public function form_enctype_switch() {
		if ( is_singular( 'job_listing' ) && comments_open() ) {
			?>
			<script>
              jQuery('#commentform')[0].encoding = 'multipart/form-data';
			</script>
			<?php
		}
	}

	/**
	 * Get the ip address of the client
	 *
	 * @return bool|mixed|string|void
	 */
	public function get_client_ip() {
		$ip = '';

		if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$ip_list = explode( ',', $ip );

		if ( get_option( 'pebas-detect-has_reverse_proxy', 0 ) && isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
			$ip_list = explode( ',', @$_SERVER["HTTP_X_FORWARDED_FOR"] );
			$ip_list = array_map( 'detect_normalize_ip', $ip_list );

			$trusted_proxies = explode( ',', get_option( 'pebas-detect-trusted_proxy_ips' ) );

			// Always trust localhost
			$trusted_proxies[] = '';
			$trusted_proxies[] = '::1';
			$trusted_proxies[] = '127.0.0.1';

			$trusted_proxies = array_map( 'detect_normalize_ip', $trusted_proxies );
			$ip_list[]       = $ip;

			$ip_list = array_diff( $ip_list, $trusted_proxies );
		}
		// Fallback IP
		array_unshift( $ip_list, '::1' );

		// Each Proxy server append their information at the end, so the last IP is most trustworthy.
		$ip = end( $ip_list );
		$ip = self::detect_normalize_ip( $ip );

		if ( ! $ip ) {
			$ip = '::1';
		} // By default, use localhost

		$ip = apply_filters( 'detect_client_ip', $ip, $ip_list );

		return $ip;
	}

	/**
	 * Normalize detected IP address
	 *
	 * @param $ip
	 *
	 * @return bool|string
	 */
	public function detect_normalize_ip( $ip ) {
		$ip     = trim( $ip );
		$binary = @inet_pton( $ip );
		if ( empty( $binary ) ) {
			return $ip;
		}

		$ip = inet_ntop( $binary );

		return $ip;
	}

	/**
	 * Check whether current client has liked or disliked the review comment
	 *
	 * @param $comment_id
	 *
	 * @return bool
	 */
	public static function has_user_liked_comment( $comment_id ) {
		$likes = get_comment_meta( $comment_id, 'review_likes_ip' );
		$ips   = array_unique( $likes );
		$ips   = implode( PHP_EOL, $ips );
		$ips   = explode( PHP_EOL, $ips );
		$ip    = pebas_review_listings_functions()->get_client_ip();
		if ( in_array( $ip, $ips ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Update review comment likes and dislikes
	 */
	public function like_comment() {
		$like = isset( $_POST['like'] ) ? $_POST['like'] : '';
		$ip   = isset( $_POST['ip'] ) ? $_POST['ip'] : '';
		$id   = isset( $_POST['id'] ) ? $_POST['id'] : '';
		$ips  = get_comment_meta( $id, 'review_likes_ip' );

		$ips = implode( PHP_EOL, $ips );
		$ips = explode( PHP_EOL, $ips );
		if ( ! in_array( $ip, $ips ) ) {
			$ips[] = $ip;
			$ips   = array( implode( PHP_EOL, $ips ) );
			update_comment_meta( $id, 'review_likes_ip', implode( ',', $ips ) );

			if ( '1' == $like ) {
				$cur_likes = get_comment_meta( $id, 'review_likes', true );
				if ( empty( $cur_likes ) ) {
					update_comment_meta( $id, 'review_likes', 1 );
				} else {
					$cur_likes ++;
					update_comment_meta( $id, 'review_likes', $cur_likes );
				}
			} else {
				$cur_dislikes = get_comment_meta( $id, 'review_dislikes', true );
				if ( empty( $cur_likes ) ) {
					update_comment_meta( $id, 'review_dislikes', 1 );
				} else {
					$cur_dislikes += 1;
					update_comment_meta( $id, 'review_dislikes', $cur_dislikes );
				}
			}

			$result['error']  = false;
			$result['notice'] = esc_html__( 'Thank you for voting!', 'pebas-review-listings' );

		} else {
			$result['error']  = true;
			$result['notice'] = esc_html__( 'You have already voted!', 'pebas-review-listings' );
		}


		wp_send_json_success( $result );
	}

	public function get_google_reviews( $place_id ) {
		//todo: make sure that api is pulled from theme options
		// check whether data is already exists so we don't have to pull it each time
		$transient = get_transient( "google_review_{$place_id}" );
		if ( isset( $transient ) && ! empty( $transient ) ) {
			$body = $transient;
		} else {
			$response = wp_remote_get( "https://maps.googleapis.com/maps/api/place/details/json?placeid={$place_id}&key=AIzaSyAze3_5wbmLHwo1T-JulnbwCJop8kI_Qvk" );
			$body     = json_decode( $response['body'] );
			$body     = $body->result;
			set_transient( "google_review_{$place_id}", $body,
				WEEK_IN_SECONDS ); //todo maybe allow time to be changed by users
		}

		var_dump( $body->rating );
		var_dump( $body->reviews );
		var_dump( $body->icon );
		var_dump( $body->photos );

		return $body;
	}

	public function get_google_reviews_comment( $place_id ) {
		pebas_review_listings_functions()->get_google_reviews( $place_id );
	}

	/**
	 * Get average review rating for the given post
	 *
	 * @param $post_id
	 *
	 * @return float|int|string
	 */
	public static function get_average_rating( $post_id ) {
		$reviews    = get_approved_comments( $post_id, array( 'meta_key' => 'review_rating' ) );
		$avg_rating = '0.0';
		if ( $reviews ) :
			$ratings = array();
			foreach ( $reviews as $review ) {
				$rating    = get_comment_meta( $review->comment_ID, 'review_rating', true );
				$ratings[] = $rating;
			}
			$avg_rating = array_sum( $ratings ) / count( $ratings );
		endif;

		return number_format_i18n( $avg_rating, 1 );
	}

	/**
	 * Generate stars by average rating
	 *
	 * @param $id
	 * @param $rating
	 *
	 * @return string
	 */
	public static function generate_review_starts( $id = '', $rating = '' ) {
		$id     = ! empty( $id ) ? $id : get_the_ID();
		$rating = ! empty( $rating ) ? $rating : self::get_average_rating( $id );
		ob_start();
		?>
		<i class="material-icons mf"><?php echo esc_html( 'star' ); ?></i>
		<i class="material-icons mf"><?php echo 1.8 <= $rating ? esc_html( 'star' ) : ( 1.2 <= $rating ? esc_html( 'star_half' ) : esc_html( 'star_border' ) ); ?></i>
		<i class="material-icons mf"><?php echo 2.8 <= $rating ? esc_html( 'star' ) : ( 2.2 <= $rating ? esc_html( 'star_half' ) : esc_html( 'star_border' ) ); ?></i>
		<i class="material-icons mf"><?php echo 3.8 <= $rating ? esc_html( 'star' ) : ( 3.2 <= $rating ? esc_html( 'star_half' ) : esc_html( 'star_border' ) ); ?></i>
		<i class="material-icons mf"><?php echo 4.8 <= $rating ? esc_html( 'star' ) : ( 4.2 <= $rating ? esc_html( 'star_half' ) : esc_html( 'star_border' ) ); ?></i>
		<?php
		$stars = ob_get_clean();

		return wp_kses_post( $stars );
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_review_listings_functions
 */
function pebas_review_listings_functions() {
	return pebas_review_listings_functions::instance();
}

/**
 * Function used to display comment in custom formatted list
 *
 * @param $comment
 * @param $args
 * @param $depth
 */
function lisner_review_list_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$add_below          = '';
	?>
	<!-- Comment -->
	<li class="comment">
	<!-- Comment Meta -->
	<div class="comment-meta">
		<!-- Comment Author -->
		<div class="media">
			<div class="media-info">
				<a href="<?php echo esc_url( get_author_posts_url( $comment->user_id ) ); ?>">
					<?php echo get_avatar( $comment->user_id, '60', '', '', array( 'class' => 'rounded-circle' ) ); ?>
				</a>
				<?php $author_data = get_userdata( $comment->user_id ); ?>
				<div class="media-author">
					<span class="author"><?php echo isset( $author_data->display_name ) ? esc_html( $author_data->display_name ) : esc_html( get_comment_author() ); ?></span>
					<span class="time"><?php echo human_time_diff( get_comment_time( 'U' ),
								current_time( 'timestamp' ) ) . esc_html__( ' ago', 'pebas-review-listings' ); ?></span>
					<div class="reviews-rating">
						<?php $rating = get_comment_meta( get_comment_ID(), 'review_rating', true ); ?>
						<span class="reviews-rating-stars"><?php echo pebas_review_listings_functions::generate_review_starts( '',
								$rating ); ?></span>
					</div>
				</div>
			</div>
			<div class="comment-body media-body">
				<?php
				if ( $comment->comment_approved != '0' ) {
					?>
					<p><?php echo get_comment_text(); ?></p>
					<?php
				} else { ?>
					<p><?php esc_html_e( 'Your comment is awaiting moderation.', 'pebas-review-listings' ); ?></p>
					<?php
				}
				?>

				<?php $images = get_comment_meta( get_comment_ID(), 'review_gallery', '' ); ?>
				<?php if ( isset( $images ) && ! empty( $images ) ) : ?>
					<div class="comment-images lisner-gallery">
						<?php foreach ( $images as $image ) : ?>
							<?php $image_full = wp_get_attachment_image_src( $image, 'full' ); ?>
							<?php $image_src = wp_get_attachment_image_src( $image, 'review_image' ); ?>
							<a href="<?php echo esc_url( $image_full[0] ); ?>"><img
										src="<?php echo esc_url( $image_src[0] ); ?>" alt=""></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<!-- Single Listing Reviews / Comment Action -->
				<?php $ip = pebas_review_listings_functions()->get_client_ip(); ?>
				<div class="comment-action">
					<?php $comment_likes = get_comment_meta( $comment->comment_ID, 'review_likes', true ); ?>
					<?php $comment_likes = isset( $comment_likes ) && ! empty( $comment_likes ) ? $comment_likes : 0; ?>
					<?php $comment_dislikes = get_comment_meta( $comment->comment_ID, 'review_dislikes', true ); ?>
					<?php $comment_dislikes = isset( $comment_dislikes ) && ! empty( $comment_dislikes ) ? $comment_dislikes : 0; ?>
					<?php $comment_author = $comment->user_id; ?>
					<?php if ( $comment_author != get_current_user_id() && ! pebas_review_listings_functions::has_user_liked_comment( $comment->comment_ID ) ) : ?>
						<div class="comment-action-like">
							<a data-like="1" href="javascript:" class="btn ajax-like "><i
										class="material-icons mf"><?php echo esc_html( 'sentiment_satisfied' ); ?></i><?php esc_html_e( 'I Agree',
									'pebas-review-listings' ); ?>
							</a>
							<span><?php echo esc_html( $comment_likes ); ?></span>
						</div>
						<div class="comment-action-like dislike">
							<a data-like="0" href="javascript:" class="btn ajax-like"><i
										class="material-icons mf"><?php echo esc_html( 'sentiment_dissatisfied' ); ?></i><?php esc_html_e( 'I fail to agree',
									'pebas-review-listings' ); ?>
							</a>
							<span><?php echo esc_html( $comment_dislikes ); ?></span>
						</div>
					<?php else: ?>
						<div class="comment-action-like">
							<i class="material-icons mf"><?php echo esc_html( 'sentiment_satisfied' ); ?></i>
							<span><?php echo esc_html( $comment_likes ); ?></span>
						</div>
						<div class="comment-action-like dislike">
							<i class="material-icons mf"><?php echo esc_html( 'sentiment_dissatisfied' ); ?></i><span><?php echo esc_html( $comment_dislikes ); ?></span>
						</div>
					<?php endif; ?>
					<input class="client-ip" type="hidden"
					       value="<?php echo esc_attr( $ip ); ?>">
					<input class="comment-id" type="hidden"
					       value="<?php echo esc_attr( $comment->comment_ID ); ?>">
				</div>
			</div>
		</div>
	</div>
	<?php
}
