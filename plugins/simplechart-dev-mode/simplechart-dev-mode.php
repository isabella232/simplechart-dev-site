<?php
/**
 * Plugin Name: Simplechart Dev Mode
 * Description: Override WordPress Fieldmanager plugin's JS with local copy from app JS repo
 * Author: Josh Kadis, Alley Interactive
 * Version: 0.0.1
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package simplechart-dev-mode
 */

define( 'SC_DEV_MODE_PATH', dirname( __FILE__ ) );

/**
 * Get the Git hash "version" from the filename
 *
 * @param string $type 'app' or 'widget'.
 * @param string $path Absolute path to directory containing the files.
 * @return string $version Git hash or empty string.
 */
function simplechart_dev_mode_get_js_version( $type, $path ) {
	$contents = scandir( $path );
	if ( empty( $contents ) ) {
		return '';
	}

	foreach ( $contents as $file ) {
		preg_match( '/^' . $type . '\.(\w+)\.js$/', $file, $matches );
		if ( ! empty( $matches[1] ) ) {
			return $matches[1];
		}
	}
	return '';
}

/**
 * Get URL of app or widget JS from Simplechart Dev Mode plugin
 *
 * @param string $type 'app' or 'widget'.
 * @return string $url URL or empty string.
 */
function simplechart_dev_mode_get_js_url( $type ) {
	$version = simplechart_dev_mode_get_js_version( $type, SC_DEV_MODE_PATH . '/js' );
	if ( empty( $version ) ) {
		return '';
	}
	$url_path = sprintf( 'simplechart-dev-mode/js/%s.%s.js', $type, $version );
	return plugins_url( $url_path );
}



// Set up the settings page and maybe overrides.
add_action( 'after_setup_theme', function() {
	require_once( SC_DEV_MODE_PATH . '/modules/settings-page.php' );

	$overrides = get_user_meta( get_current_user_id(), 'simplechart_dev_mode', true );

	if ( ! empty( $overrides['override_app'] ) ) {
		add_filter( 'simplechart_webpack_public_path', function( $default ) {
			return plugin_dir_url( __FILE__ ) . 'js/';
		} );

		add_filter( 'simplechart_web_app_js_url', function( $default ) {
			return simplechart_dev_mode_get_js_url( 'app' );
		} );
		add_filter( 'simplechart_widget_loader_url', function( $default ) {
			return simplechart_dev_mode_get_js_url( 'widget' );
		} );
	}
} );
