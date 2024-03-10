<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'id21973065_aiagency' );

/** Database username */
define( 'DB_USER', 'id21973065_root' );

/** Database password */
define( 'DB_PASSWORD', 'cactelrtTAKA69++' );

/** Database hostname */
define( 'DB_HOST', 'https://ia-com-agency.netlify.app' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '|I6/W#FYI0|aXebLNm-;L[`RLE|Of.E4> <>m4&(`(>udVM/z4Vx%} 8cW.G=C5!' );
define( 'SECURE_AUTH_KEY',  '7BMg0 2N[hLMt.rN~Wd6y/y23^(Hk:dH6e)e&U~j|JQHzJN|v+)KF5F;`-[:qXBB' );
define( 'LOGGED_IN_KEY',    'mMe~|3Ez;f=nrN4rVD$Q:3)ug~SGOl|h03+]/dl!Bv&oOJI}`7ke7`NwN?bGS7>O' );
define( 'NONCE_KEY',        '[)2je);mpJdHF;f}!vaInH/Z2YB^J.4O0%NQ;KHfh7>v/ItER3IJ?n^kv7nMNoM%' );
define( 'AUTH_SALT',        'CHvkpsdg^@Yb#a*b?j(daNR76Acrln^#RH[f=>hM-bTg]7)|&B.DiFzk3W,guQzp' );
define( 'SECURE_AUTH_SALT', 'BIRB3X;9({r^Rpr88|AqwHn3{mscV?#hSCd%77=?%t@/t jnsgtT4&l!$??/,do8' );
define( 'LOGGED_IN_SALT',   '@blYTlfv0(NIE!;Ej&zn`*z[pCg8]?uzr@u&9od?/;Rq1Y]KRO@I~AUbuh5=D=2w' );
define( 'NONCE_SALT',       '*ZD(B{6l3{ v?Z3BUlq^yv?aK~3vTk!lGNYm][5%ubC|lTH}M_W3/dtRkxRf3m`&' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
