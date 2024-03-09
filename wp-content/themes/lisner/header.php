<!doctype html >

<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
	<?php do_action( 'pbs_head' ); ?>
</head>


<?php $option = get_option( 'pbs_option' ); ?>
<body <?php body_class() ?> itemscope="itemscope" itemtype="https://schema.org/WebPage">
<!-- Header -->
<header class="header header-top <?php echo class_exists( 'Lisner_Core' ) && isset( $option['menu-sticky'] ) && 'yes' == $option['menu-sticky'] ? esc_attr( 'header-sticky' ) : ''; ?>">
	<?php get_template_part( 'views/header/header-0' ); ?>
</header>
