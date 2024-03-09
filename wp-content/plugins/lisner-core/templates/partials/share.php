<?php
/**
 * Template Name: Share Post
 * Description: Partial content for single posts
 *
 * @author pebas
 * @version 1.0.0
 * @package partials
 */

$option = get_option( 'pbs_option' );
?>
<?php if ( isset( $option['share-posts'] ) && ! empty( $option['share-posts'] ) ) : ?>
    <!-- Share Post -->
    <div class="share share-post">
        <ul class="list-unstyled list-inline">
			<?php if ( in_array( 'facebook', $option['share-posts'] ) ) : ?>
                <!-- Facebook -->
                <li class="list-inline-item">
                    <a class="facebook" data-toggle="tooltip"
                       title="<?php esc_attr_e( 'Facebook Share', 'lisner-core' ); ?>"
                       href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_the_permalink() ); ?>"
                       target="_blank">
                        <i class="fab fa-facebook-f"></i>
                        <span class="hidden"><?php esc_html_e( 'Share on Facebook', 'lisner-core' ); ?></span>
                    </a>
                </li>
			<?php endif; ?>
			<?php if ( in_array( 'google', $option['share-posts'] ) ) : ?>
                <!-- Google+ -->
                <li class="list-inline-item">
                    <a class="google" data-toggle="tooltip"
                       title="<?php esc_attr_e( 'Share On Google+', 'lisner-core' ); ?>"
                       href="https://plus.google.com/share?url=<?php echo urlencode( get_permalink() ) ?>"
                       target="_blank">
                        <i class="fab fa-google-plus-g"></i>
                        <span class="hidden"><?php esc_html_e( 'Share on Google', 'lisner-core' ); ?></span>
                    </a>
                </li>
			<?php endif; ?>
			<?php if ( in_array( 'twitter', $option['share-posts'] ) ) : ?>
                <!-- Twitter  -->
                <li class="list-inline-item">
                    <a class="twitter" data-toggle="tooltip"
                       title="<?php esc_attr_e( 'Twitter Share', 'lisner-core' ); ?>"
                       href="http://twitter.com/intent/tweet?text=<?php echo urlencode( get_permalink() ) ?>"
                       target="_blank">
                        <i class="fab fa-twitter"></i>
                        <span class="hidden"><?php esc_html_e( 'Share on Twitter', 'lisner-core' ); ?></span>
                    </a>
                </li>
			<?php endif; ?>
			<?php if ( in_array( 'pinterest', $option['share-posts'] ) ) : ?>
                <!-- Pinterest -->
                <li class="list-inline-item">
                    <a class="pinterest" data-toggle="tooltip"
                       title="<?php esc_attr_e( 'Pin It', 'lisner-core' ); ?>"
                       href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( get_permalink() ); ?>media=<?php echo urlencode( get_the_post_thumbnail_url() ); ?>"
                       target="_blank">
                        <i class="fab fa-pinterest"></i>
                        <span class="hidden"><?php esc_html_e( 'Pin On Pinterest', 'lisner-core' ); ?></span>
                    </a>
                </li>
			<?php endif; ?>
			<?php if ( in_array( 'linkedin', $option['share-posts'] ) ) : ?>
                <!-- Linkedin -->
                <li class="list-inline-item">
                    <a class="linkedin" data-toggle="tooltip"
                       title="<?php esc_attr_e( 'Share On Linkedin', 'lisner-core' ); ?>"
                       href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>&title=<?php echo urlencode( get_the_title() ); ?>
&summary=<?php echo urlencode( get_the_excerpt() ) ?>&source=<?php echo get_bloginfo( 'site_title' ); ?>"
                       target="_blank">
                        <i class="fab fa-linkedin"></i>
                        <span class="hidden"><?php esc_html_e( 'Share On Linkedin', 'lisner-core' ); ?></span>
                    </a>
                </li>
			<?php endif; ?>
			<?php if ( in_array( 'reddit', $option['share-posts'] ) ) : ?>
                <!-- Reddit -->
                <li class="list-inline-item">
                    <a class="reddit" data-toggle="tooltip"
                       title="<?php esc_attr_e( 'Share on Reddit', 'lisner-core' ); ?>"
                       href="https://www.reddit.com/submit?url=<?php echo urlencode( get_permalink() ); ?>"
                       target="_blank">
                        <i class="fab fa-reddit"></i>
                        <span class="hidden"><?php esc_html_e( 'Share On Reddit', 'lisner-core' ); ?></span>
                    </a>
                </li>
			<?php endif; ?>
			<?php if ( in_array( 'print', $option['share-posts'] ) ) : ?>
                <!-- Print -->
                <li class="list-inline-item">
                    <a class="print icon-outline" data-toggle="tooltip"
                       title="<?php esc_attr_e( 'Print Article', 'lisner-core' ); ?>"
                       onclick="printArticle('post-print')"
                       href="javascript:">
                        <i class="material-icons"><?php echo esc_html( 'print' ); ?></i>
                        <span class="hidden"><?php esc_html_e( 'Print Article', 'lisner-core' ); ?></span>
                    </a>
                </li>
                <script>
                    function printArticle(divName) {

                        let printContents = document.getElementById(divName).innerHTML,
                            w = window.open();
                        w.document.write(printContents);
                        w.print();
                        w.close();
                    }
                </script>
			<?php endif; ?>
        </ul>
    </div>
<?php endif; ?>
