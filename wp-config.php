<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'heyguys' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '=TB|-kaH.eQs&jCn/0q-B291b!UzGMbD4&HWEYkcd|;ZZzppD6~@rP/48E~nsw]#' );
define( 'SECURE_AUTH_KEY',  'h*o*X+UB}mu_z&>G/r>L_Oh]W!E&DI{/F+?:R.xYfk]%j_a2$me;g#_jhJ-j`@bn' );
define( 'LOGGED_IN_KEY',    't1yY;5T(1#:R)tJM;hoXgj12%P58!TE 5rd=>!;g{KSsdrZWDH$|$vNH$e)) 9;O' );
define( 'NONCE_KEY',        '4c=7DXaL:E8p5Lp|r3PY%$bqB H~$i{/Z3=3~hZia:u<TxD(x -Po*yXAejsS:&L' );
define( 'AUTH_SALT',        'I_Yt%PmH=idxNd)!lJ32s?emGFl|~+y,~)XIOnk#4t&lMA1}I$^F#1!2AMIO 4[@' );
define( 'SECURE_AUTH_SALT', 'n@u2FsG |,0)h9iQw|qrB1&O3w^(>C?=Z+f{|T%89yZqX+7dmEl 9` rB9yp47E1' );
define( 'LOGGED_IN_SALT',   '.Z)y+hEXw?Jzr0W9Q]Gz7D83Bkt7q;0mt@AtJRs&|z}h<6R}v[UhjOV:>C&[]<bS' );
define( 'NONCE_SALT',       '*-QR5/u%q!IS<`41U1vS(fynK$u5m;7[9)9V.,PeI2JXXyn64D/uIeD: OpEy:),' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
