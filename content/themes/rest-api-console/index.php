<?php

include ABSPATH . '/wp-admin/includes/class-wp-screen.php';
include ABSPATH . '/wp-admin/includes/screen.php';
include ABSPATH . '/wp-admin/includes/template.php';

$screen = WP_Screen::get( 'rest-api-console' );
$screen->set_current_screen();

register_admin_color_schemes();

WP_REST_Console::instance();
include WPMU_PLUGIN_DIR . '/rest-api-console/templates/views/app.php';
