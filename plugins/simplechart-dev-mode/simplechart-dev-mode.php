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
 * Get URL of app, vendor or widget JS from Simplechart Dev Mode plugin
 *
 * @param string $type 'app', 'vendor' or 'widget'.
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

/**
 * Map options values to labels
 *
 * @return array
 */
function simplechart_dev_mode_get_options() {
	return array(
		'plugin' => __( 'Plugin', 'simplechart-dev-mode' ),
		'app' => __( 'App', 'simplechart-dev-mode' ),
		'localhost' => __( 'localhost:8080', 'simplechart-dev-mode' ),
	);
}

/**
 * Get JS source for current user
 *
 * @param int $id Optional user ID, defaults to current logged-in user
 * @return string 'plugin', 'app', 'localhost'. Defaults to 'plugin' if no user is available
 */
function simplechart_dev_mode_get_user_js_source( $id = null ) {
	$get_id = $id ? $id : get_current_user_id();
	$source = get_user_meta( $get_id, 'simplechart_dev_mode', true );
	return ! empty( $source ) ? $source : 'plugin';
}

// Set up the settings page and maybe overrides.
add_action( 'after_setup_theme', function() {
	require_once( SC_DEV_MODE_PATH . '/modules/settings-page.php' );

	$source = simplechart_dev_mode_get_user_js_source();

	// Default to JS from wordpress-simplechart plugin
	if ( empty( $source ) || 'plugin' === $source ) {
		return;
	}

	// Specify localhost or app to override default JS
	if ( 'localhost' === $source ) {
		$public_path = 'http://localhost:8080/static';
		$app_js_url = 'http://localhost:8080/static/app.js';
		$vendor_js_url = 'http://localhost:8080/static/vendor.js';
		$widget_js_url = 'http://localhost:8080/static/widget.js';
	} else {
		$public_path = plugin_dir_url( __FILE__ ) . 'js/';
		$app_js_url = simplechart_dev_mode_get_js_url( 'app' );
		$vendor_js_url = simplechart_dev_mode_get_js_url( 'vendor' );
		$widget_js_url = simplechart_dev_mode_get_js_url( 'widget' );
	}

	add_filter( 'simplechart_webpack_public_path', function( $default ) {
		return $public_path;
	} );
	add_filter( 'simplechart_web_app_js_url', function( $default ) {
		return $app_js_url;
	} );
	add_filter( 'simplechart_vendor_js_url', function( $default ) {
		return $vendor_js_url;
	} );
	add_filter( 'simplechart_widget_loader_url', function( $default ) {
		return $widget_js_url;
	} );
} );

// Show Simplechart version in admin bar
add_action( 'admin_bar_menu', function( $wp_admin_bar ) {
	$source = simplechart_dev_mode_get_user_js_source();
	$node_html = sprintf(
		'%s %s',
		esc_html__( 'Simplechart JS source:' ),
		esc_html( simplechart_dev_mode_get_options()[ $source ] )
	);

	$args = array(
		'id' => 'simplechart-dev-mode',
		'title' => $node_html,
	);

	if ( 'localhost' !== $source ) {
		// add version hash to title if using plugin or app (not localhost)
		// add 'href' to $args
	}

	$wp_admin_bar->add_node( $args );
}, 999 );