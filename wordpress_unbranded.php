<?php

	/*
		Plugin Name: WordPress Unbranded
		Plugin URI: http://wernull.com/2013/04/wordpress-unbranded-simple-admin-plugin/
		Description: Remove features of the WordPress admin bar
		Version: 0.3.0
		Author: Kyle Werner @wernull
		Author URI: http://wernull.com

	    This program is free software; you can redistribute it and/or modify
	    it under the terms of the GNU General Public License version 2, 
	    as published by the Free Software Foundation.

	    This program is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU General Public License for more details.
	*/

	$wrnl_wp_unbranded_options = wrnl_get_options();

	if($wrnl_wp_unbranded_options['remove_howdy']){
		add_action( 'wp_before_admin_bar_render', 'wrnl_remove_howdy' );
	}
	if($wrnl_wp_unbranded_options['custom_logout']){
		add_action( 'wp_before_admin_bar_render', 'wrnl_custom_logout_link' );
	}
	if($wrnl_wp_unbranded_options['hide_wp_logo']){
		add_action( 'wp_before_admin_bar_render', 'wrnl_dashboard_tweaks' );
	}
	if($wrnl_wp_unbranded_options['footer_custom']){
		add_filter('admin_footer_text', 'wrnl_footer_text' );
	}
	if($wrnl_wp_unbranded_options['custom_login']){
		add_action("login_head", "wrnl_login_head");
		add_filter('login_headerurl', 'wrnl_url_login');
	}

    add_action( 'admin_init', 'wrnl_admin_init' );
	add_action( 'admin_menu', 'wrnl_sa_menu' );

	function wrnl_admin_init() {
		register_setting( 'wrnl-wp-unbranded', 'wrnl_wp_unbranded', 'settings_validate' );
	}

	function wrnl_sa_menu() {
		add_options_page( 'WordPress Unbranded', 'WP Unbranded', 'manage_options', 'wrnl-wp-unbranded', 'wrnl_sa_options' );
	}

	function wrnl_sa_options() {
		?>
    	<div class="wrap">
    		<div id="icon-options-general" class="icon32"></div>
			<h2>WordPress Unbranded Settings</h2>
			<form action="options.php" method="post">
				<?php 
					settings_fields('wrnl-wp-unbranded');
					setting_option_fields(); 
				?>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"></p>
			</form>
		</div>
    	<?php
	}

	function setting_option_fields()
    {
        $options = wrnl_get_options();
		
        echo '<br /><input type="hidden" name="wrnl_wp_unbranded[remove_howdy]" value="0" />
        <label><input type="checkbox" name="wrnl_wp_unbranded[remove_howdy]" value="1"'. (($options['remove_howdy']) ? ' checked="checked"' : '') .' /> 
        Remove "Howdy"</label><br />';
        echo '<br /><input type="hidden" name="wrnl_wp_unbranded[custom_logout]" value="0" />
        <label><input type="checkbox" name="wrnl_wp_unbranded[custom_logout]" value="1"'. (($options['custom_logout']) ? ' checked="checked"' : '') .' /> 
        Remove User Dropdown and replace with log out button</label><br />';
        echo '<input type="hidden" name="wrnl_wp_unbranded[custom_logout_text]" value="0" />
        <label>Log Out Button Text: <input type="text" name="wrnl_wp_unbranded[custom_logout_text]" value="'. $options['custom_logout_text'] . '" /> 
        </label><br /><br />';
        echo '<input type="hidden" name="wrnl_wp_unbranded[hide_wp_logo]" value="0" />
        <label><input type="checkbox" name="wrnl_wp_unbranded[hide_wp_logo]" value="1"'. (($options['hide_wp_logo']) ? ' checked="checked"' : '') .' /> 
        Remove WordPress Logo and DropDown</label><br /><br />';
        echo '<input type="hidden" name="wrnl_wp_unbranded[footer_custom]" value="0" />
        <label><input type="checkbox" name="wrnl_wp_unbranded[footer_custom]" value="1"'. (($options['footer_custom']) ? ' checked="checked"' : '') .' /> 
        Custom Footer Message</label><br />';
        echo '<input type="hidden" name="wrnl_wp_unbranded[footer_text]" value="0" />
        <label>Footer Text: <input type="text" name="wrnl_wp_unbranded[footer_text]" value="'. $options['footer_text'] . '" /> 
        </label><br /><br />';
        echo '<input type="hidden" name="wrnl_wp_unbranded[custom_login]" value="0" />
        <label><input type="checkbox" name="wrnl_wp_unbranded[custom_login]" value="1"'. (($options['custom_login']) ? ' checked="checked"' : '') .' /> 
        Custom Login Logo</label><br/><br/>';
        echo '<img id="logo_url_img" src="'. esc_url( $options['custom_login_image'] ) .'"/><br/>
        <input id="logo_url_text" style="width:45em; max-width:80%;" type="text" name="wrnl_wp_unbranded[custom_login_image]" value="'. esc_url( $options['custom_login_image'] ) .'" />  
        <br/><input id="upload_logo_button" type="button" class="button" value="Update Image" />';
    }

    function wrnl_options_enqueue_scripts() {
		wp_register_script( 'wrnl-upload', plugins_url('js/wrnl-upload.js', __FILE__ ), array('jquery','media-upload','thickbox') );

		if ( 'settings_page_wrnl-wp-unbranded' == get_current_screen() -> id ) {
			wp_enqueue_script('jquery');

			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');

			wp_enqueue_script('media-upload');
			wp_enqueue_script('wrnl-upload');

		}

	}
	add_action('admin_enqueue_scripts', 'wrnl_options_enqueue_scripts');


    function settings_validate( $input ) { return $input; }

	/*
	Remove Howdy user name drop down and replace it with just a logout button
	*/
	function wrnl_custom_logout_link() {
		global $wp_admin_bar;
		$option = wrnl_get_options();

		$wp_admin_bar->add_menu( array(
			'id'    => 'wp-custom-logout',
			'title' => $option['custom_logout_text'],
			'parent'=> 'top-secondary',
			'href'  => wp_logout_url()
		) );
		$wp_admin_bar->remove_menu('my-account');

	}

	/*
	Remove Howdy 
	*/
	function wrnl_remove_howdy() {
		global $wp_admin_bar;
		$my_account=$wp_admin_bar->get_node('my-account');
			$newtitle = str_replace( 'Howdy,', '', $my_account->title );
			$wp_admin_bar->add_node( array(
				'id' => 'my-account',
				'title' => $newtitle,
			));
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
	Change or Remove WordPress admin footer text
	*/
	function wrnl_footer_text() {
		global $wrnl_wp_unbranded_options;
		echo $wrnl_wp_unbranded_options['footer_text'];
	}

	/*
	Change login image to custom logo. This needs to have a settings page built for dynamic updating 
	*/
	function wrnl_login_head() {
		global $wrnl_wp_unbranded_options;
		echo "
		<style>
		body.login #login h1 a {
			background: url('".$wrnl_wp_unbranded_options['custom_login_image']."') no-repeat scroll center top transparent;
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
		global $wrnl_wp_unbranded_options;
	    return $wrnl_wp_unbranded_options['custom_login_path'];
	}

	/*
	plugin options and defaults
	*/
	function wrnl_get_options(){
		$option = get_option('wrnl_wp_unbranded');
		
		if(!is_array($option)) {
			$option = array();
		} 

		$option_default = array();
		$option_default['remove_howdy'] = 1;
		$option_default['custom_logout'] = 1;
		$option_default['custom_logout_text'] = 'Log Out';
		$option_default['hide_wp_logo'] = 1;
		$option_default['footer_custom'] = 1;
		$option_default['footer_text'] = '';
		$option_default['custom_login'] = 1;
		$option_default['custom_login_path'] = home_url();
		$option_default['custom_login_image_default'] = plugins_url( 'images/default.png' , __FILE__ );
		$option_default['custom_login_image'] = $option_default['custom_login_image_default'];

		$option = array_merge($option_default, $option);

		return $option;
	}



	
?>