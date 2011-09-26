<?php 
/**
 * This code assumes there is an update from WPPF 1.0 to WPPF 1.1 available.
 * refer to http://konstruktors.com/blog/wordpress/2538-automatic-updates-for-plugins-and-themes-hosted-outside-wordpress-extend/ for more info
 * Be sure to remove this file and the api/ folder from plugin root before deploying plugin.
 */

// Process API requests
$action = $_POST['action'];
$args = unserialize(stripslashes($_POST['request']));

if( !is_object( $args ) )
{
	$license = !empty( $args['license'] ) ? $args['license'] : '';
}
elseif( is_object( $args ) )
{
	$args_array = get_object_vars( $args );
	$license = !empty( $args_array['license'] ) ? $args_array['license'] : '';
}

if( $licence == 'unlimited' )
{
	$dl_link = 'http://api.example.com/update/wppf-11-un.zip';
}
elseif( $license == 'single-site' )
{
	$dl_link = 'http://api.example.com/update/wppf-11-ss.zip';
}
else
{
	$dl_link = 'http://api.example.com/update/wppf-11.zip';
}

$packages['wppf'] = array(
	'versions' => array(
		'1.1' => array(
			'version' => '1.1',
			'date' => '2011-09-26',
			'tested' => '3.2.1',
			'package' => $dl_link
		)
	),
	'info' => array(
		'url' => 'http://example.com'
	)	
);

if (is_array($args))
	$args = array_to_object($args);

if(is_array($packages[$args->slug]))
	$latest_package = array_shift($packages[$args->slug]['versions']);

// basic_check
if ($action == 'basic_check') {	
	$update_info = array_to_object($latest_package);
	$update_info->slug = $args->slug;
	
	if (version_compare($args->version, $latest_package['version'], '<'))
		$update_info->new_version = $update_info->version;
	
	print serialize($update_info);
}

// plugin_information
if ($action == 'plugin_information') {	
	$data = new stdClass;
	
	$data->slug = $args->slug;
	$data->version = $latest_package['version'];
	$data->last_updated = $latest_package['date'];
	$data->tested = $latest_package['tested'];
	$data->download_link = $latest_package['package'];
	
	$data->sections = array('description' => 'WPPF 1.1');

	print serialize($data);
}

// theme_update
if ($action == 'theme_update') {
	$update_info = array_to_object($latest_package);
	
	//$update_data = new stdClass;
	$update_data = array();
	$update_data['package'] = $update_info->package;	
	$update_data['new_version'] = $update_info->version;
	$update_data['url'] = $packages[$args->slug]['info']['url'];
		
	if (version_compare($args->version, $latest_package['version'], '<'))
		print serialize($update_data);	
}

function array_to_object($array = array()) {
    if (empty($array) || !is_array($array))
		return false;
		
	$data = new stdClass;
    foreach ($array as $akey => $aval)
            $data->{$akey} = $aval;
	return $data;
}

//end api.example.com/update/index.php