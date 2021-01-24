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
define('DB_NAME', $_ENV['DB_NAME'] ?? null);

/** MySQL database username */
define('DB_USER', $_ENV['DB_USER'] ?? null);

/** MySQL database password */
define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? null);

/** MySQL hostname */
define('DB_HOST', $_ENV['DB_HOST'] ?? null);

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'f05d2c175db7260076593430deacfd1c2047f811');
define( 'SECURE_AUTH_KEY',  '25b8556d7b6930ed7a24e767c92643d872197b78');
define( 'LOGGED_IN_KEY',    'f9fd7cae1bb580875c1bbb4629a1e2e8243d9522');
define( 'NONCE_KEY',        '7ce897d460a2cc2b7803c49ebb5c1de26ac6d3c6');
define( 'AUTH_SALT',        '2522760c4cfb887942c90241de612702a2e56a66');
define( 'SECURE_AUTH_SALT', '4472ac168cc61817f2be21c085d0417dde5f3b1b');
define( 'LOGGED_IN_SALT',   'e9aae188c1a4e2defcf30cfb4cbac65b9f91aedd');
define( 'NONCE_SALT',       'bc0463b2372dc42c36d8ddb7de7955dee11fb50e');

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
define('WP_DEBUG', (bool) ($_ENV['WP_DEBUG'] ?? false));
define('WP_DEBUG_LOG', (bool) ($_ENV['WP_DEBUG'] ?? false));

define('WP_HOME', ($_ENV['WP_SITEURL'] ?? 'https://postclick.com'));
define('WP_SITEURL', ($_ENV['WP_SITEURL'] ?? 'https://postclick.com'));

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
	$_SERVER['HTTPS'] = 'on';
}

define('DISALLOW_FILE_EDIT', true);
define('FS_METHOD', 'direct');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
