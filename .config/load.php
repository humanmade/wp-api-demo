<?php

define( 'AUTHBROKER_LOG', ini_get( 'error_log' ) );

if ( Altis\get_environment_type() !== 'local' ) {
	// wp-api.org/tachyon isn't accessible, because the main network site
	// is not accessible.
	define( 'TACHYON_URL', 'https://demo.wp-api.org/tachyon' );
}
