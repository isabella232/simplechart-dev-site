<?php
/**
 * Various helper functions
 */

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

/**
 * Get GitHub URL of commit
 *
 * @param string $hash Git hash.
 * @return string URL.
 */
function simplechart_dev_mode_get_github_commit_url( $hash ) {
	return 'https://github.com/alleyinteractive/simplechart/commit/' . esc_attr( $hash );
}

/**
 * Get link HTML for GitHub URL of commit
 *
 * @param string $hash Git hash.
 * @return string link HTML.
 */
function simplechart_dev_mode_get_github_commit_link( $hash ) {
	return sprintf(
		'<a target="_blank" href="%s">%s<a>',
		esc_url( simplechart_dev_mode_get_github_commit_url( $hash ) ),
		esc_html( $hash )
	);
}
