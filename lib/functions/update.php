<?php
/**
 * These functions are for self-hosted plugins with automatic updates.
 * This file should not be required from wppf.php unless needed.
 * This code is based on the excellent write-up found at:
 * http://konstruktors.com/blog/wordpress/2538-automatic-updates-for-plugins-and-themes-hosted-outside-wordpress-extend/
 * In addition to this file, you will find the server-side code in /api/index.php
 * This code assumes you have index.php located at http://api.example.com/update/
 */

// force update check. This is for testing only!
//set_site_transient('update_plugins', null);

// Take over the update check
add_filter('pre_set_site_transient_update_plugins', 'wppf_update_check');
function wppf_update_check($checked_data)
{
	$wppf_api_url = 'http://api.example.com/update/';
	$wppf_plugin_slug = 'wppf';
	
	if (empty($checked_data->checked))
		return $checked_data;
	
	$request_args = array(
		'slug' => $wppf_plugin_slug,
		'version' => $checked_data->checked[$wppf_plugin_slug .'/'. $wppf_plugin_slug .'.php'],
		//send license type, if needed
		//'license' => WPPF_LICENSE
	);

	$request_string = wppf_prep_request('basic_check', $request_args);

	// Start checking for an update
	$raw_response = wp_remote_post($wppf_api_url, $request_string);
	
	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);
	
	if (is_object($response) && !empty($response->new_version)) // Feed the update data into WP updater
		$checked_data->response[$wppf_plugin_slug .'/'. $wppf_plugin_slug .'.php'] = $response;
	
	return $checked_data;
}

// Take over the Plugin info screen
add_filter('plugins_api', 'wppf_api_call', 10, 3);
function wppf_api_call($def, $action, $args)
{
	$wppf_api_url = 'http://api.example.com/update/';
	$wppf_plugin_slug = 'wppf';
	
	if ($args->slug != $wppf_plugin_slug)
		return false;
	
	// Get the current version
	$plugin_info = get_site_transient('update_plugins');
	$current_version = $plugin_info->checked[$wppf_plugin_slug .'/'. $wppf_plugin_slug .'.php'];
	$args->version = $current_version;
	
	$request_string = wppf_prep_request($action, $args);
	
	$request = wp_remote_post($wppf_api_url, $request_string);
	
	if (is_wp_error($request)) {
		$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);
		
		if ($res === false)
			$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
	}
	
	return $res;
}

function wppf_prep_request($action, $args)
{
	global $wp_version;
	
	return array(
		'body' => array(
			'action' => $action, 
			'request' => serialize($args),
			'api-key' => md5(get_bloginfo('url'))
		),
		'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
	);	
}

add_action('admin_init', 'wppf_update');
function wppf_update()
{
	// Don't do anything if we're on the latest version
	if( version_compare( get_option( 'wppf_version' ), WPPF_VERSION, '>=') )
		return;
		
	//EXAMPLE OF UPDATE CODE
	//Update to WPPF 1.1
	/*
	if( version_compare( get_option( 'wppf_version' ), '1.1', '<') )
	{
		update_option( 'wppf_version', '1.1' );
		
		//do other updatey stuff
	}
	*/
	
	//submenu-only version
	//wp_redirect( admin_url( 'options-general.php?page=wppf&wppf-updated=true' ) );
	//top-level menu version
	wp_redirect( admin_url( 'admin.php?page=wppf&wppf-updated=true' ) );
}

add_action('admin_notices', 'wppf_admin_notices');
function wppf_admin_notices()
{
	if ( isset($_GET['page'] ) && $_GET['page'] == 'wppf' && isset( $_GET['settings-updated']) && $_GET['settings-updated'] == 'true' )
	{
		echo '<div class="updated" id="message">' . __( 'Your WPPF Settings have been saved.', 'wppf' ) . '</div>';
	}
	if ( isset($_GET['page'] ) && $_GET['page'] == 'wppf' && isset( $_GET['wppf-updated']) && $_GET['wppf-updated'] == 'true' )
	{
		echo '<div class="updated" id="message">' . __( 'WPPF has been updated to Version ' . get_option('wppf_version'), 'wppf' ) . '</div>';
	}
}

//end lib/functions/update.php