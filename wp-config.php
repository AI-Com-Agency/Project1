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
define( 'DB_NAME', 'wordpress_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '~:g4hW(XQ{**0RV)qU]5#8M=E!uJ$QV#v%<[.trWnjc~K1HJ5U%*Z`vu<=4VG$6D' );
define( 'SECURE_AUTH_KEY',  'wTe1KggpisGf*2^P,,T0YC.qYV9Fj[!F^Nc|:eCl(MI/U0Sf*v/)cv)CA?(T6Fl+' );
define( 'LOGGED_IN_KEY',    'n{&_pMEQ!XH_X8v2f~_!F?Rd5M~06rC4{+ x?|AY#&PmS(6ut@KDM{i<S1SK}plg' );
define( 'NONCE_KEY',        '82.WO)^6&m0S:1AIOL+or@yb&#_e4K$;nQy<s;6..wm@xsL&%|3Qt+WQRM.`<P6R' );
define( 'AUTH_SALT',        '8E5G}x|Y%| -.xu&ET.{bcNmvsIGP/:]U1:d7% X}wQ^`Y5Fo@bT*se@1uGYavFp' );
define( 'SECURE_AUTH_SALT', 'Ty/Ki]A6c`7JERox|)[iiyb?l4e/0/O(]2~BJMRw1tM|n):<hMEk$N>9ixo7~a A' );
define( 'LOGGED_IN_SALT',   ',SLASpsk*q,yA@0s~AS<N:+0}}s45^Y[vuNTFkZFVH-xZj;.$lh~rA}3!lxl,Z82' );
define( 'NONCE_SALT',       'sr^9/4cyr$>v{YZaL<W3V2((nHdnM(dUZtLMRSr tdc!YQ-_PnJi2r5}u.PE^KCr' );

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
