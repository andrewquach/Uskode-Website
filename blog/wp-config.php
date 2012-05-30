<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'uskode_blog');

/** MySQL database username */
define('DB_USER', 'uskodeb');

/** MySQL database password */
define('DB_PASSWORD', 'uskode');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'L$xr21GX`/Ewt}hq[eMJP-7wt~V{:.]e=#YJYc,S?ijcEbTNU}N,$bF1VC:{@Psa');
define('SECURE_AUTH_KEY',  '|$&ns1UW(8ro9ng26C0;ve&Ne~;8;qjK,:Xe//ui![W7P9>(;a./|9C#7_De/,Gs');
define('LOGGED_IN_KEY',    'GMZ0SPd6=cIN$qfbb4!jU9%+E`YNz`@g+5wM$*K]s{OZ?!fN.DHh F(dqwj=h:Qf');
define('NONCE_KEY',        'yCz+sOAHMd7c</C)qJlV/eo!Iup%Gd8aB}>1HC2in|k^R0-#SZaAcmFDwXkx$?ai');
define('AUTH_SALT',        'FI<baKiXQY)|0nvdgnAK-e21&F9v5D5)j},v4Sd(OY~#g >ia^-F=*`myAf|4?:q');
define('SECURE_AUTH_SALT', ' )Qtcxd{x6[C-8,+;$IWn?bB99-,5*P7mo1red/FUfj=+F}5@oKZDqDu@lOpeT%0');
define('LOGGED_IN_SALT',   'S#iz=)J5ZpM[n4k?=b3:JK^<3lf!<KN>g<)Lrzer-w4kmx@22m:7h,AzF[X+: 93');
define('NONCE_SALT',       '8T}o0twK;r!--M?eW*Vy]MTVfnxBPD(A[)fi.Z I)n6h-w=}&,eK#dk{)t]n@^!H');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
