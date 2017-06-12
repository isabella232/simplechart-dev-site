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

// Setup
define( 'SC_DEV_MODE_PATH', dirname( __FILE__ ) );
require_once( SC_DEV_MODE_PATH . '/inc/helpers.php' );
require_once( SC_DEV_MODE_PATH . '/inc/settings-page.php' );

// User settings page
add_action( 'after_setup_theme', function() {
	Simplechart_Dev_Mode_Settings::instance();
} );

// Override JS if applicable
add_action( 'init', function() {
	$source = simplechart_dev_mode_get_user_js_source();

	// Default to JS from wordpress-simplechart plugin
	if ( empty( $source ) || 'plugin' === $source ) {
		return;
	}

	add_filter( 'simplechart_webpack_public_path', function( $default ) {
		return 'localhost' === simplechart_dev_mode_get_user_js_source() ?
			'http://localhost:8080/static' :
			plugin_dir_url( __FILE__ ) . 'js/';
	} );
	add_filter( 'simplechart_web_app_js_url', function( $default ) {
		return 'localhost' === simplechart_dev_mode_get_user_js_source() ?
			'http://localhost:8080/static/app.js' :
			simplechart_dev_mode_get_js_url( 'app' );
	} );
	add_filter( 'simplechart_vendor_js_url', function( $default ) {
		return 'localhost' === simplechart_dev_mode_get_user_js_source() ?
			'http://localhost:8080/static/vendor.js' :
			simplechart_dev_mode_get_js_url( 'vendor' );
	} );
	add_filter( 'simplechart_widget_loader_url', function( $default ) {
		return 'localhost' === simplechart_dev_mode_get_user_js_source() ?
			'http://localhost:8080/static/widget.js' :
			simplechart_dev_mode_get_js_url( 'widget' );
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

	if ( 'localhost' !== $source ) {
		// add version hash to admin bar note
		if ( class_exists( 'Simplechart' ) ) {
			$app_path = ( 'app' === $source ) ?
				SC_DEV_MODE_PATH . '/js' :
				Simplechart::instance()->get_plugin_dir( 'js/app' );
			$version_hash = simplechart_dev_mode_get_js_version( 'app', $app_path );
			$node_html .= ' (' . esc_html( $version_hash ) . ')';

			// link to version
			$href = simplechart_dev_mode_get_github_commit_url( $version_hash );
		} else {
			$node_html = 'Simplechart plugin not enabled.';
			$href = false;
		}
	} else {
		$href = false;
	}

	$args = array(
		'id' => 'simplechart-dev-mode',
		'title' => $node_html,
		'href' => $href,
	);

	$wp_admin_bar->add_node( $args );
}, 999 );

if ( defined( 'SIMPLECHART_DEV_MODE_BASIC_AUTH_USER' ) &&
	defined( 'SIMPLECHART_DEV_MODE_BASIC_AUTH_PASSWORD' ) ) {
	add_filter( 'simplechart_api_http_headers', function ( $headers ) {
		$user_pass = SIMPLECHART_DEV_MODE_BASIC_AUTH_USER . ':' . SIMPLECHART_DEV_MODE_BASIC_AUTH_PASSWORD;
		// Add basic HTTP auth to dev site
		if ( isset( $_ENV['PANTHEON_ENVIRONMENT'] ) ) {
			$headers['Authorization'] = 'Basic ' . base64_encode( $user_pass );
		}

		return $headers;
	} );
}
