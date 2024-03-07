<?php $previous_post = get_previous_post(); ?>
<?php $next_post = get_next_post(); ?>
<?php if ( $previous_post || $next_post ) : ?>
    <div class="post-section prev-next">
		<?php if ( $previous_post ) : ?>
            <div class="prev-next-post prev">
                <a href="<?php echo get_permalink( $previous_post->ID ); ?>">
                    <small><span><?php echo esc_html( '←' ); ?></span><?php esc_html_e( 'Prev Post', 'lisner' ); ?>
                    </small>
                    <h6><?php echo esc_html( $previous_post->post_title ); ?></h6>
                </a>
            </div>
		<?php endif; ?>
		<?php if ( $next_post ) : ?>
            <div class="prev-next-post next">
                <a href="<?php echo get_permalink( $next_post->ID ); ?>">
                    <small><?php esc_html_e( 'Next Post', 'lisner' ); ?><span><?php echo esc_html( '→' ); ?></span>
                    </small>
                    <h6><?php echo esc_html( $next_post->post_title ); ?></h6>
                </a>
            </div>
		<?php endif; ?>
    </div>
<?php endif; ?>
