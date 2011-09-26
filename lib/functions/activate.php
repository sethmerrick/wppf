<?php

function wppf_activate()
{
	//set version number in database
	if( !get_option( 'wppf_version' ) )
	{
		update_option( 'wppf_version', '1.0' );
	}
	
	//set option defaults
	if( !get_option( 'wppf_options' ) )
	{
		$options = array();
		$options['wppf_text'] = 'option default';
		$options['wppf_checkbox'] = 1;
		$options['wppf_select'] = 'one';
		
		update_option( 'wppf_options', $options );
	}
}

function wppf_deactivate()
{

}

//end lib/functions/activate.php