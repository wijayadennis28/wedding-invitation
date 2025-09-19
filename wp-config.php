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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', '/home/u910603741/domains/invitation.digital-nest.tech/public_html/wp-content/plugins/wp-super-cache/' );
define( 'DB_NAME', 'u910603741_YA7Yh' );

/** Database username */
define( 'DB_USER', 'u910603741_5Q0v9' );

/** Database password */
define( 'DB_PASSWORD', 'AhphQTqi1K' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'rT2nQnv;7+iW0ib(}ec}}{/}P7,ltodcV4>Yv.LaQ%t|+ppBLd<?:pzp8jVt{=*e' );
define( 'SECURE_AUTH_KEY',   'FQ0[PxSx{*)Z|88Hrb>Y2BZVEY#_N.[jA9dzHwb8j0|krD,e:yi9dc-Kv)CF9xkw' );
define( 'LOGGED_IN_KEY',     'lzXBBK_eg3`O`;C:Z5=1eZbM%Q0-Jd64vfMM)})0Y{(rwt$l|n8oJgYh}o_`ZH]}' );
define( 'NONCE_KEY',         'o^ueV6eDk9}a7KtI)|xODZ K]kXP:}7A,rP0965`dg7IDAvCqjujW8=CJjDZSDm^' );
define( 'AUTH_SALT',         ' ?!p[*vWj)dp=;-i!=<W>nu+l)+jy)VW] pk?`zu0Jn$l*@|/9TY7kmz-etB3V&~' );
define( 'SECURE_AUTH_SALT',  '}>g/0RIZN^EB|)a-pM0:dPyIq%}[D7+qp) AOlJeV;0+,>cYt9QNK}(W]20p/u}V' );
define( 'LOGGED_IN_SALT',    'B)8ZeEHz(f=vTJlh:T2/?Z*c|#U<1L6]zLX:;Cr:iCi7aiKm3lB9V*m?cI)?eYn(' );
define( 'NONCE_SALT',        '/}]dJi+G4q|Y28+-dj,D`:d7*WJf%Cu#bS) :G552^PJ>,I -bT@sgh2T5*x+CBh' );
define( 'WP_CACHE_KEY_SALT', '8qJDO~ukk]<^XM{THv/cKNXIJop/)hj)KUff)qmWVJ%_[<DVj9zMI_%`+<JxJEcy' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '6d82a3bab8c7adda7a715626d1a81b17' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
