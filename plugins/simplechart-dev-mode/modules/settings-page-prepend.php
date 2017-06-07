<?php
/**
 * Content prepended to the fields in the settings page
 */

$default_js_path = Simplechart::instance()->get_plugin_dir( 'js/app' );
$default_app_version = simplechart_dev_mode_get_js_version( 'app', $default_js_path );
$default_widget_version = simplechart_dev_mode_get_js_version( 'widget', $default_js_path );
$override_app_version = simplechart_dev_mode_get_js_version( 'app', SC_DEV_MODE_PATH . '/js' );
$override_widget_version = simplechart_dev_mode_get_js_version( 'widget', SC_DEV_MODE_PATH . '/js' );

/**
 * Get GitHub URL of commit
 *
 * @param string $hash Git hash.
 * @return string URL.
 */
function simplechart_dev_mode_github_commit_link( $hash ) {
	echo '<a target="_blank" href="https://github.com/alleyinteractive/simplechart/commit/' . esc_attr( $hash ) . '">' . esc_html( $hash ) . '<a>';
}

?>
<h3><?php esc_html_e( 'Background info', 'simplechart-dev-mode' ); ?></h3>
<p><?php echo wp_kses_post( __( 'The <a href="https://github.com/alleyinteractive/wordpress-simplechart" target="_blank">WordPress Simplechart</a> plugin ships with its own <a href="https://github.com/alleyinteractive/wordpress-simplechart/tree/master/js/app" target="_blank">pre-bundled JavaScript files</a> of the Simplechart editor app and widget for template embeds.', 'simplechart-dev-mode' ) ); ?></p>
<p><?php echo wp_kses_post( __( 'These settings allow you to override the <em>plugin\'s</em> JavaScript files with a different version from the <a href="https://github.com/alleyinteractive/simplechart" target="_blank">Simplechart</a> repository.', 'simplechart-dev-mode' ) ); ?></p>
<p><?php echo wp_kses_post( __( 'This is helpful for testing recent changes to the <em>JS app</em> that not yet been merged into the <em>WordPress plugin</em>.', 'simplechart-dev-mode' ) ); ?></p>

<h3><?php esc_html_e( 'Available versions', 'simplechart-dev-mode' ); ?></h3>
<table cellspacing="10">
	<tr>
		<th></th>
		<th><?php esc_html_e( 'Default', 'simplechart-dev-mode' ); ?></th>
		<th><?php esc_html_e( 'Override', 'simplechart-dev-mode' ); ?></th>
	</tr>
	<tr>
		<td><code>app.${version}.js</code></td>
		<td><?php simplechart_dev_mode_github_commit_link( $default_app_version ); ?></td>
		<td><?php simplechart_dev_mode_github_commit_link( $override_app_version ); ?></td>
	</tr>
	<tr>
		<td><code>widget.${version}.js</code></td>
		<td><?php simplechart_dev_mode_github_commit_link( $default_widget_version ); ?></td>
		<td><?php simplechart_dev_mode_github_commit_link( $override_widget_version ); ?></td>
	</tr>
</table>

<h3><?php esc_html_e( 'Update settings', 'simplechart-dev-mode' ); ?></h3>
