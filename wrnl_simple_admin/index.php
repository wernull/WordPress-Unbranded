<?php

	/*
		Plugin Name: WRNL Simple Admin
		Plugin URI: http://wernull.com/simple-admin
		Description: Remove features of the WordPress admin bar
		Version: 0.0.1
		Author: Kyle Werner
		Author URI: http://wernull.com
		License: GPLv2 or later
	
	*/

	/*
	Remove Howdy user name drop down and replace it with just a logout button
	*/
	add_action( 'wp_before_admin_bar_render', 'custom_logout_link' );
	function custom_logout_link() {
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
	add_action( 'wp_before_admin_bar_render', 'dashboard_tweaks' );
	function dashboard_tweaks() {
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
	add_action("login_head", "my_login_head");
	function my_login_head() {
		echo "
		<style>
		body.login #login h1 a {
			background: url('".get_bloginfo('template_url')."/images/logo-blue.png') no-repeat scroll center top transparent;
			-webkit-background-size: contain;
		    -moz-background-size: contain;
		    -o-background-size: contain;
		    background-size: contain;
		}
		</style>
		";
	}

	/*
	Change login logo link to site home url
	*/
	add_filter('login_headerurl', 'wpc_url_login');
	function wpc_url_login(){
	    return home_url();
	}


	
?>