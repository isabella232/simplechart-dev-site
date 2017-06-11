<?php
/**
 * Content prepended to the fields in the settings page
 */

$default_js_path = Simplechart::instance()->get_plugin_dir( 'js/app' );
$default_app_version = simplechart_dev_mode_get_js_version( 'app', $default_js_path );
$default_widget_version = simplechart_dev_mode_get_js_version( 'widget', $default_js_path );
$override_app_version = simplechart_dev_mode_get_js_version( 'app', SC_DEV_MODE_PATH . '/js' );
$override_widget_version = simplechart_dev_mode_get_js_version( 'widget', SC_DEV_MODE_PATH . '/js' );

?>
<h3><?php esc_html_e( 'Background info', 'simplechart-dev-mode' ); ?></h3>
<p><?php echo wp_kses_post( __( 'The <a href="https://github.com/alleyinteractive/wordpress-simplechart" target="_blank">WordPress Simplechart</a> plugin ships with its own <a href="https://github.com/alleyinteractive/wordpress-simplechart/tree/master/js/app" target="_blank">pre-bundled JavaScript files</a> of the Simplechart editor app and widget for template embeds.', 'simplechart-dev-mode' ) ); ?></p>
<p><?php echo wp_kses_post( __( 'Simplechart Dev Mode allows you to override the <em>plugin\'s</em> JavaScript files with a different version from the <a href="https://github.com/alleyinteractive/simplechart" target="_blank">Simplechart</a> repository or your local Node development environment.', 'simplechart-dev-mode' ) ); ?></p>
<p><?php echo wp_kses_post( __( 'This is helpful for testing recent changes to the <em>JS app</em> that not yet been merged into the <em>WordPress plugin</em>.', 'simplechart-dev-mode' ) ); ?></p>

<?php if ( defined( 'SIMPLECHART_DEV_MODE_AUTODEPLOY_APP_BRANCH' ) || defined( 'SIMPLECHART_DEV_MODE_AUTODEPLOY_PLUGIN_BRANCH' ) ) : ?>
	<h3><?php esc_html_e( 'Automated deployments', 'simplechart-dev-mode' ); ?>
	<ol>
		<?php if ( defined( 'SIMPLECHART_DEV_MODE_AUTODEPLOY_APP_BRANCH' ) ) : ?>
			<li>
				<a
					href="https://github.com/alleyinteractive/simplechart/tree/<?php echo esc_attr( SIMPLECHART_DEV_MODE_AUTODEPLOY_APP_BRANCH ); ?>"
					target="_blank"
				>simplechart/<?php echo esc_html( SIMPLECHART_DEV_MODE_AUTODEPLOY_APP_BRANCH ); ?></a>
			</li>
		<?php endif; ?>
		<?php if ( defined( 'SIMPLECHART_DEV_MODE_AUTODEPLOY_PLUGIN_BRANCH' ) ) : ?>
			<li>
				<a
					href="https://github.com/alleyinteractive/wordpress-simplechart/tree/<?php echo esc_attr( SIMPLECHART_DEV_MODE_AUTODEPLOY_PLUGIN_BRANCH ); ?>"
					target="_blank"
				>wordpress-simplechart/<?php echo esc_html( SIMPLECHART_DEV_MODE_AUTODEPLOY_PLUGIN_BRANCH ); ?></a>
			</li>
		<?php endif; ?>
	</ol>
<?php endif; ?>

<h3><?php esc_html_e( 'Select version', 'simplechart-dev-mode' ); ?></h3>

<table cellspacing="10">
	<tr>
		<th><?php esc_html_e( 'Path', 'simplechart-dev-mode' ); ?></th>
		<th><?php esc_html_e( 'Version', 'simplechart-dev-mode' ); ?></th>
	</tr>
	<tr>
		<td><code>plugins/wordpress-simplechart/js/app/app.${version}.js</code></td>
		<td><?php echo simplechart_dev_mode_get_github_commit_link( $default_app_version ); ?></td>
	</tr>
	<tr>
		<td><code>plugins/simplechart-dev-mode/js/app.${version}.js</code></td>
		<td><?php echo simplechart_dev_mode_get_github_commit_link( $override_app_version ); ?></td>
	</tr>
	<tr>
		<td><code>http://localhost:8080/static/app.${version}.js</code></td>
		<td><?php _e( 'n/a', 'simplechart-dev-mode' ); ?></td>
	</tr>
</table>
