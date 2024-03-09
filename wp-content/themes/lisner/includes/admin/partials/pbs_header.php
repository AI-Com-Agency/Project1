<?php

global $submenu;

if ( isset( $submenu['pbs_menu_welcome'] ) ) {
	$pbs_menu_items = $submenu['pbs_menu_welcome'];
}

if ( ! empty( $pbs_menu_items ) && is_array( $pbs_menu_items ) ) {
	?>
    <div class="wrap about-wrap td-wp-admin-header ">
        <h2 class="nav-tab-wrapper">

			<?php
			foreach ( $pbs_menu_items as $pbs_menu_item ) {
				?>
                <a href="admin.php?page=<?php echo esc_attr( $pbs_menu_item[2] ) ?>"
                   class="nav-tab <?php if ( isset( $_GET['page'] ) and $_GET['page'] == $pbs_menu_item[2] ) {
					   echo 'nav-tab-active';
				   } ?> "><?php echo wp_kses_post( $pbs_menu_item[0] ); ?></a>
				<?php
			}
			?>
        </h2>
    </div>
	<?php
}

?>


