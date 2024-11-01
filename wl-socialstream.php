<?php
/*
Plugin Name: Webleister SocialMedia Stream
Plugin URI:  http://www.webleister.ch
Description: Bietet M&ouml;glichkeiten von verschiedenen Sozialen Netzwerken Beitr&auml;ge anzuzeigen
Version:     1.1
Author:      Webleister GmbH
Author URI:  http://www.webleister.ch
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: wl-socialstream
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'wl_socialstream\PLUGIN', __FILE__ );

define( 'wl_socialstream\PLUGIN_BASENAME', plugin_basename( \wl_socialstream\PLUGIN ) );

define( 'wl_socialstream\PLUGIN_NAME', trim( dirname( \wl_socialstream\PLUGIN_BASENAME ), '/' ) );

define( 'wl_socialstream\PLUGIN_DIR', untrailingslashit( dirname( \wl_socialstream\PLUGIN ) ) );

foreach ( glob( plugin_dir_path( __FILE__ ) . "*/_*.php" ) as $file ) {
    include_once $file;
}
?>