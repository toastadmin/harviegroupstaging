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
define('DB_NAME', 'harviegroup_wp');

/** MySQL database username */
define('DB_USER', 'prod_dbadmin');

/** MySQL database password */
define('DB_PASSWORD', 'YLJ0lLJthXPZHgE');

/** MySQL hostname */
define('DB_HOST', 'prod-wordpress.cfsd6peqwyib.ap-southeast-2.rds.amazonaws.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'lLnykWpNtCNTrDa3IGN1FFDf2BJCYJI6eaVFJqV6Hezml7J5Y6aLOQA5OJ39TObE');
define('SECURE_AUTH_KEY',  'ft6DsIOZ7LRRpAVOi5wkeg5LYWLfjnepVBfUpER6CcxVKEvbn9SiZvlghwHENJSi');
define('LOGGED_IN_KEY',    'cyUcNp3dXdJaRI5zckYsB7GrPVhs1nNu9UxnnC3lvjW0cWGj7eWCdL6LK4WSoPgX');
define('NONCE_KEY',        'Y80CXf4m0386qEdadk9hEFdZC0Ff2j5AJnlFxQ79VOMQJIFUW7gHUny1c04A70tJ');
define('AUTH_SALT',        'EBClKeRTxxHezVTek9FqnCPvUKcgTZdlBr92yZLW1NYLh72LqrpjDgoQA6DfQYLs');
define('SECURE_AUTH_SALT', 'DP1gG4iQY0KbO709CelNl9wATbR9nD5tJLSXvafW81i92Y6PmQUHXP25f4S4yqvG');
define('LOGGED_IN_SALT',   '3olm8ceIfM60HKpb1qK3o7o6ge7Bw8EH4RoNq2umiWAqugeCzN1SwBtJovmz1F4S');
define('NONCE_SALT',       'db6wmDQCose8H72oFha1fj49GFAp4eQwldDSmZAGjjZIuJFW2ROVgg5OZHgYXpc6');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

define('FORCE_SSL_ADMIN',   true);
define('FORCE_SSL_LOGIN',   true);
define('FORCE_SSL_CONTENT', true);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
