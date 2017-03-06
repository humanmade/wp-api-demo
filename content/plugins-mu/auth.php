<?php

add_action( 'admin_enqueue_scripts', function() {
  wp_enqueue_script( 'wp-api' );
} );
