<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )	exit ();
//Delete plugin data that had been saved to WP DB
delete_option('newsboard-settings');delete_option('newsboard-main');delete_option('newsboard-appearance');
?>