<?php
/*
Plugin Name: WordPress Plugin Framework
Plugin URI: http://github.com
Description: A Starting Point for WordPress Plugins
Version: 1.0
Author: Strange Wind Studio
Author URI: http://github.com
License: GPL2
*/

//define base name. should equal 'wppf', but this accounts for user changing plugin folder name.
$wppf_root = dirname( plugin_basename( __FILE__ ) );

//load textdomain for translations
load_plugin_textdomain( 'wppf', false, $wppf_root . '/lib/languages/' );

//define paths
define( 'WPPF_ROOT', WP_PLUGIN_DIR . '/' . $wppf_root );
define( 'WPPF_LIB', WPPF_ROOT . '/lib' );
define( 'WPPF_ADMIN', WPPF_LIB . '/admin' );
define( 'WPPF_CSS', WPPF_LIB . '/css' );
define( 'WPPF_IMAGES', WPPF_LIB . '/images' );
define( 'WPPF_FUNCTIONS', WPPF_LIB . '/functions' );
define( 'WPPF_JS', WPPF_LIB . '/js' );

//define urls
define( 'WPPF_ROOT_URL', plugins_url( $wppf_root ) );
define( 'WPPF_CSS_URL', WPPF_ROOT_URL . '/lib/css' );
define( 'WPPF_IMAGES_URL', WPPF_ROOT_URL . '/lib/images' );
define( 'WPPF_JS_URL', WPPF_ROOT_URL . '/lib/js' );

//define wppf version
define( 'WPPF_VERSION', '1.0' );

//define license, if needed for auto-updates
// define( 'WPPF_LICENSE', 'single-site' );
// define( 'WPPF_LICENSE', 'unlimited' );

//require files
require_once( WPPF_ADMIN . '/menu.php' );
require_once( WPPF_ADMIN . '/settings.php' );
require_once( WPPF_FUNCTIONS . '/magic.php' );
require_once( WPPF_FUNCTIONS . '/activate.php' );
//REFER TO /lib/functions/update.php BEFORE UNCOMMENTING!
//require_once( WPPF_FUNCTIONS . '/update.php' );

//handle activation & deactivation
register_activation_hook( __FILE__, 'wppf_activate' );
register_deactivation_hook( __FILE__, 'wppf_deactivate' );

//end wppf.php