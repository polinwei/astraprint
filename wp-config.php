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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'demo_astra' );

/** Database username */
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', 'X@mpp' );

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
define( 'AUTH_KEY',         '6%MA*$rc-sd+kJ+&H;$<uNcFfkN; -9Uu09O~Dr7}}&&QlL]8ALpm9OUk$}qW2KV' );
define( 'SECURE_AUTH_KEY',  '^F@R`v8+/7#.d2/cn6-Wwy7/0%x+D}5#34 n[W3:t{)*EL;h]Vrau3p/xB3EUS[x' );
define( 'LOGGED_IN_KEY',    ' d#!LVvD:I7V~x^(?gdY1|D.d9d#x|xb7OtPykDj@jOqrs_Q7e`fylkvOX7eTH0Z' );
define( 'NONCE_KEY',        'OG/P$4t-in$tL`uFmbyBWYXZ(L{DI+n{PJ;VElA177o4}A)GU48U@WvTAL>jn5lw' );
define( 'AUTH_SALT',        'cB1*}dHv3V3`fJUE0fWD`BrS1CH1OPYCJSRdd>U7q6Qw=! tyJQaW2x1y.jq0i.X' );
define( 'SECURE_AUTH_SALT', 'qJjO(^xq<JxQ,D(:aJ7ipe)C~L,}HQtA4P%C+= Ba<=!X3y/F/ZB(9&p/W~GKeh[' );
define( 'LOGGED_IN_SALT',   'iHE:~XH|O6RP%Er1HORJKsJX(.T+Fy$w}FeO;~P`1rFV(<:DDU$KLdZ:?cjk3WrX' );
define( 'NONCE_SALT',       '<0Z9YEajH!j -M36}Qq+WkD<$oz&Ny&/W,EWdjmT668ur1O/_$y2W-<kG,>T.uY/' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'print_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
