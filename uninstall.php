<?php
/**
 * @package Internals
 *
 * This code is used when the plugin is removed (not just deactivated but actively deleted through the WordPress Admin).
 */

//if uninstall not called from WordPress exit
if( !defined('WP_UNINSTALL_PLUGIN') )
    exit();

foreach ( array('mailto', 'emailfrom', 'emailsubject', 'thankyoumessage', 'apiKey', 'include-css', 'googleanalytics', 'clickyanalytics') as $option) {
	delete_option( 'fwsacf-'.$option );
}
