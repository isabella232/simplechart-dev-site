<?php

/**
 * Set metadata defaults for Simplechart (title, caption, credit, subtitle)
 *
 * @param array $opts
 * @return array
 */
add_filter( 'simplechart_chart_default_metadata', function( $opts ) {
	// Enable the subtitle field - set truthy value for empty field
	$opts['subtitle'] = true;
	return $opts;
} );
