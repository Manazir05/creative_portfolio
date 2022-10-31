<?php
/**
 * @package Creative Portfolio
 */
/*
Plugin Name: Creative Portfolio
Plugin URI: https:
Description: A Custom plugin for Creative portfolio management
Version: 1.0
Author: Manazir Mustafa
Author URI: https://github.com/dev-manazir
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: sep
*/

// Make sure not to expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'PORTFOLIO_VERSION', '1.0' );
define( 'PORTFOLIO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


if ( file_exists( PORTFOLIO_PLUGIN_DIR . '/CMB2/init.php' ) ) {
	require_once( PORTFOLIO_PLUGIN_DIR . '/CMB2/init.php' );
}

// Run instantly with plugin activation
require_once( PORTFOLIO_PLUGIN_DIR . 'initialize_db.php' );
$init_db = new InitializeDatabase();
register_activation_hook( __FILE__ , array( $init_db, 'portfolio_settings_data_initialization' ) );

/**
 * !!Caution!!
 * Comment out only if preserving custom options are not required with plugin re-activation
 */
register_deactivation_hook( __FILE__ , array( $init_db, 'portfolio_settings_data_rollback' ) );

require_once( PORTFOLIO_PLUGIN_DIR . 'class-zip-uploader.php' );
require_once( PORTFOLIO_PLUGIN_DIR . 'AdminNotice.php' );
require_once( PORTFOLIO_PLUGIN_DIR . 'custom_options.php' );
require_once( PORTFOLIO_PLUGIN_DIR . 'static_animated.php' );
require_once( PORTFOLIO_PLUGIN_DIR . 'html5_rich.php' );
require_once( PORTFOLIO_PLUGIN_DIR . 'development_cms.php' );
require_once( PORTFOLIO_PLUGIN_DIR . 'video_production.php' );

include_once( ABSPATH . "wp-config.php");
include_once( ABSPATH . "wp-includes/wp-db.php");
require_once( ABSPATH . 'wp-admin/includes/file.php' );


function cps_remove_footer_admin () {
	echo 'Portfolio Management System by Software Development team.';
}
add_filter('admin_footer_text', 'cps_remove_footer_admin');

/**
 * function to remove screen options from admin screens
 */
function sep_remove_screen_options() {		
	return false;		
}
add_filter( 'screen_options_show_screen', 'sep_remove_screen_options' );



/*
* Writes string, array, and object data to the WP error log.
* 
* To use, pass the result to write to the log as follows:
* write_log( $value_to_write );
*
* @param string|array|object $log
*/
function write_log( $log )  {
   if ( is_array( $log ) || is_object( $log ) ) {
	   error_log( print_r( $log, true ) );
   } else {
	   error_log( $log );
   }
}