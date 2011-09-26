<?php

/**
 * This function adds a submenu to the specified sidebar menu.
 * In this example, it adds a 'WPPF' link to the 'Settings' menu.
 * Below is more robust code for adding a Top-Level Menu.
 * To use, uncomment the function and comment out the two functions below.
 */
/*
add_action( 'admin_menu', 'wppf_menu' );
function wppf_menu()
{
	$_wppf_settings_page = add_submenu_page( 'options-general.php', __( 'WPPF Settings', 'wppf' ), __( 'WPPF', 'wppf' ), 'manage_options', 'wppf', 'wppf_settings_page' );
	
	add_action( 'admin_print_styles-' . $_wppf_settings_page, 'wppf_settings_page_enqueue' );
}
*/

/**
 * These two functions add a Top-Level menu with two submenu pages.
 * To use, uncomment the two functions and comment out the single wppf_menu funciton above.
 */
add_action( 'admin_menu', 'wppf_add_admin_menu' );
function wppf_add_admin_menu()
{
	add_menu_page( 'WPPF', ' WPPF', 'manage_options', 'wppf', 'wppf_settings_page', WPPF_IMAGES_URL . '/wppf-16.png' );
}

add_action( 'admin_menu', 'wppf_add_admin_submenus' );
function wppf_add_admin_submenus()
{
	$_wppf_settings_page = add_submenu_page( 'wppf', __( 'WPPF Settings', 'wppf' ), __( 'WPPF Settings', 'wppf' ), 'manage_options', 'wppf', 'wppf_settings_page' );
	$_wppf_settings_page_two = add_submenu_page( 'wppf', __( 'WPPF Settings 2', 'wppf' ), __( 'WPPF Settings 2', 'wppf' ), 'manage_options', 'wppf-2', 'wppf_settings_page_two' );
	
	add_action( 'admin_print_styles-' . $_wppf_settings_page, 'wppf_settings_page_enqueue' );
	add_action( 'admin_print_styles-' . $_wppf_settings_page_two, 'wppf_settings_page_enqueue' );
}

//register admin styles and scripts
add_action( 'admin_init', 'wppf_admin_init' );
function wppf_admin_init()
{
	wp_register_style( 'wppf_admin_css', WPPF_CSS_URL . '/wppf-admin.css' );
	
	wp_register_script( 'wppf_admin_js', WPPF_JS_URL . '/wppf-admin.js' );
}

//enqueue admin styles and scripts
function wppf_settings_page_enqueue()
{
	wp_enqueue_style( 'wppf_admin_css' );
	
	wp_enqueue_script( 'wppf_admin_js' );
}

//end lib/admin/wppf-menu.php