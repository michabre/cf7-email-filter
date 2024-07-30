<?php

// Check that code was called from WordPress with
// uninstall constant declared
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit;
}

// Check if options exist and delete them if present
if ( false != get_option( 'cf7_email_filter_options' ) ) {
  delete_option( 'cf7_email_filter_options' );
}