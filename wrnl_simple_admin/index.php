<?php

	/*
		Plugin Name: WRNL Simple Admin
		Plugin URI: http://wernull.com/plugins/wrnl_simple_admin
		Description: Remove features of the WordPress admin bar
		Version: 0.1.0
		Author: Kyle Werner
		Author URI: http://wernull.com

	    This program is free software; you can redistribute it and/or modify
	    it under the terms of the GNU General Public License version 2, 
	    as published by the Free Software Foundation.

	    This program is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU General Public License for more details.
	*/

	$wrnl_simple_admin_options = wrnl_get_options_stored();

	if($wrnl_simple_admin_options['custom_logout']){
		add_action( 'wp_before_admin_bar_render', 'wrnl_custom_logout_link' );
	}
	if($wrnl_simple_admin_options['hide_wp_logo']){
		add_action( 'wp_before_admin_bar_render', 'wrnl_dashboard_tweaks' );
	}
	if($wrnl_simple_admin_options['custom_login']){
		add_action("login_head", "wrnl_login_head");
		add_filter('login_headerurl', 'wrnl_url_login');
	}

	/*
	Remove Howdy user name drop down and replace it with just a logout button
	*/
	function wrnl_custom_logout_link() {
		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array(
			'id'    => 'wp-custom-logout',
			'title' => 'Logout',
			'parent'=> 'top-secondary',
			'href'  => wp_logout_url()
		) );
		$wp_admin_bar->remove_menu('my-account');
	}

	/*
	Remove WordPress logo and link drop down
	*/
	function wrnl_dashboard_tweaks() {
		global $wp_admin_bar;
		
		$wp_admin_bar->remove_menu('wp-logo');
		$wp_admin_bar->remove_menu('about');
		$wp_admin_bar->remove_menu('wporg');
		$wp_admin_bar->remove_menu('documentation');
		$wp_admin_bar->remove_menu('support-forums');
		$wp_admin_bar->remove_menu('feedback');
	}

	/*
	Change login image to custom logo. This needs to have a settings page built for dynamic updating 
	*/
	function wrnl_login_head() {
		global $wrnl_simple_admin_options;
		echo "
		<style>
		body.login #login h1 a {
			background: url('".$wrnl_simple_admin_options['custom_login_image']."') no-repeat scroll center top transparent;
			-webkit-background-size: contain;
		    -moz-background-size: contain;
		    -o-background-size: contain;
		    background-size: contain;
		    margin-bottom: 1em;
		}
		</style>
		";
	}

	/*
	Change login logo link to site home url (default) or user input
	*/
	function wrnl_url_login(){
		global $wrnl_simple_admin_options;
	    return $wrnl_simple_admin_options['custom_login_path'];
	}


	/*
	plugin options
	*/
	function wrnl_get_options_stored(){
		$option = get_option('wrnl_simple_admin');
		
		if(!is_array($option)) {
			$option = array();
		} 

		$option_default = array();
		$option_default['custom_logout'] = true;
		$option_default['hide_wp_logo'] = true;
		$option_default['custom_login'] = true;
		$option_default['custom_login_path'] = home_url();
		$option_default['custom_login_image'] = plugins_url( 'images/default.png' , __FILE__ );

		$option = array_merge($option_default, $option);

		return $option;
	}



	
?>