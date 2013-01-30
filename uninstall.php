<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

//Delete plugin data that had been saved to WP DB
delete_option('newsboard-plugin-settings');
delete_option('newsboard-plugin-main');
delete_option('newsboard-plugin-appearance');
?>