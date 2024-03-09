<?php
/**
 * Template Name: My Account Page Template
 * Description: Page template for user dashboard
 *
 * @author pebas
 * @version 1.0.0
 */
?>
<?php get_header(); ?>
<?php
//wp_enqueue_script( 'lisner-dashboard-theme' );
$option = get_option( 'pbs_option' );
$user   = get_userdata( get_current_user_id() );
?>
<?php the_post(); ?>

<main class="main-profile <?php echo ! is_user_logged_in() ? esc_attr( 'not-logged' ) : ''; ?>">

    <div class="container-fluid">

        <!-- Profile / Header -->
        <div class="row">
            <div class="col-sm-12 p-0">
                <div class="lisner-profile-header">
                    <div class="lisner-profile-header__title">
						<?php if ( is_user_logged_in() ) : ?>
                            <h4><?php esc_html_e( 'My dashboard', 'lisner-core' ); ?></h4>
						<?php else: ?>
                            <h4><?php esc_html_e( 'You have to be logged in to access my account page',
									'lisner-core' ); ?></h4>
						<?php endif; ?>
                    </div>
                    <div class="lisner-profile-header__user d-flex justify-content-between align-items-center">
                        <div class="lisner-profile-header__user-info">
							<?php if ( is_user_logged_in() ) : ?>
								<?php printf( __( 'Hello %s, you can %s', 'lisner-core' ),
									'<span class="author">' . $user->display_name . '</span>',
									'<a href="' . esc_url( wp_logout_url( home_url() ) ) . '" class="auth-link">' . esc_html__( 'log out',
										'lisner-core' ) . '<i class="material-icons mf">' . esc_html( 'exit_to_app' ) . '</i> </a>' ); ?>
							<?php else: ?>
								<?php printf( __( 'Click to login: %s', 'lisner-core' ),
									'<a href="javascript:" class="auth-link" data-toggle="modal" data-target="#modal-auth">' . esc_html__( 'log in',
										'lisner-core' ) . '<i class="material-icons mf">' . esc_html( 'power_settings_new' ) . '</i></a>' ); ?>
							<?php endif; ?>
                        </div>
						<?php if ( is_user_logged_in() ) : ?>
                            <div class="lisner-profile-header__user-image"><?php echo get_avatar( $user->ID, '43', '',
									'', array( 'class' => 'rounded-circle' ) ); ?></div>
						<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

		<?php if ( is_user_logged_in() ) : ?>
            <!-- Profile / Content -->
			<?php the_content(); ?>
		<?php endif; ?>

		<?php do_action( 'lisner_account_after' ); ?>
    </div>

</main>

<?php get_footer(); ?>
