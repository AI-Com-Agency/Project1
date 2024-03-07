<?php
/**
 * Widget Social Template
 *
 * @author pebas
 * @version 1.0.0
 * @package templates/widgets/
 *
 * @var $instance
 */
?>
<?php $socials = array(); ?>
<?php $socials['facebook'] = isset( $instance['facebook'] ) ? $instance['facebook'] : ''; ?>
<?php $socials['twitter'] = isset( $instance['twitter'] ) ? $instance['twitter'] : ''; ?>
<?php $socials['google'] = isset( $instance['google'] ) ? $instance['google'] : ''; ?>
<?php $socials['youtube'] = isset( $instance['youtube'] ) ? $instance['youtube'] : ''; ?>
<?php $socials['instagram'] = isset( $instance['instagram'] ) ? $instance['instagram'] : ''; ?>
<?php $socials['pinterest'] = isset( $instance['pinterest'] ) ? $instance['pinterest'] : ''; ?>
<?php $socials['email'] = isset( $instance['email'] ) ? $instance['email'] : ''; ?>

<?php if ( ! empty( $socials ) ) : ?>
    <div class="widget-socials">
        <ul class="list-inline m-0">
			<?php foreach ( $socials as $name => $link ) : ?>
				<?php if ( $link ) : ?>
					<?php $icon = $name; ?>
					<?php if ( 'facebook' == $name ) : ?>
						<?php $icon = 'fab fa-facebook-f'; ?>
					<?php elseif ( 'google' == $name ) : ?>
						<?php $icon = 'fab fa-google-plus-g'; ?>
					<?php elseif ( 'pinterest' == $name ) : ?>
						<?php $icon = 'fab fa-pinterest-p'; ?>
					<?php elseif ( 'email' == $name ) : ?>
						<?php $icon = 'email'; ?>
					<?php else: ?>
						<?php $icon = 'fab fa-' . $icon; ?>
					<?php endif; ?>
                    <li class="list-inline-item">
                        <a href="<?php echo 'email' == $name ? esc_attr( 'mailto:' . $link ) : esc_url( $link ); ?>"
							<?php echo 'email' != $name ? esc_attr( 'target="_blank"' ) : ''; ?>
                           class="<?php echo esc_attr( $name ) ?>"><i
                                    class="<?php echo 'email' == $name ? esc_attr( 'material-icons' ) : esc_attr( $icon . ' fa-fw' ) ?>"><?php echo 'email' == $name ? esc_attr( 'email' ) : ''; ?></i></a>
                    </li>
				<?php endif; ?>
			<?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

