<?php
/*
	Plugin Name: Force User Login
	Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
	Description: A really small plugin that forces a user to login before being able to view any blog content.
	Version: 1.2
	Author: The Integer Group Development Team
	Author URI: http://www.integer.com



	Copyright 2009 The Integer Group Dev Team  (email : development@integerdenver.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/	
	
	add_action( 'template_redirect', 'force_login' );
	
	//setup the admin page	
	add_action( 'admin_menu', 'force_login_admin_add_page');
	function force_login_admin_add_page() {
		add_options_page('Force Login Page', 'Force Login Menu', 'manage_options', 'force_login', 'force_login_options_page');
	}

	function force_login_options_page() {
		echo "<div>";
		echo "<h2>Force Login Settings</h2>";
		echo "<form action=\"options.php\" method=\"post\">";
		settings_fields('force_login_options');
		do_settings_sections('force_login');
		 
		echo "<input name=\"Submit\" type=\"submit\" value=\"Save Changes\" />";
		echo "</form></div>";
	}
	
	//setup the options array and functions to add to the admin page
	add_action('admin_init', 'force_login_admin_init');
	function force_login_admin_init(){
		register_setting( 'force_login_options', 'force_login_options', 'force_login_options_validate' );
		add_settings_section('force_login_main', 'Settings', 'force_login_section_text', 'force_login');
		add_settings_field('force_login_redirect', 'Custom Redirect', 'force_login_redirect_render', 'force_login', 'force_login_main');
		add_settings_field('force_login_whitelist', 'Whitelisted Subnets', 'force_login_whitelist_render', 'force_login', 'force_login_main');
	}
	function force_login_redirect_render() {
		$options = get_option('force_login_options');
		echo "<input id='force_login_redirect' name='force_login_options[redirect]' size='40' type='text' value='" . urldecode($options['redirect']) . "' />";
	}
	function force_login_whitelist_render() {
		$options = get_option('force_login_options');
		echo "<input id='force_login_whitelist' name='force_login_options[whitelist]' size='40' type='text' value='{$options['whitelist']}' />";
	}

	function force_login_section_text() {
		echo '<h3>Custom Redirect</h3>';
		echo "<p>Leave blank to use the last requested uri, or specify something like '/' or '/wp-admin'.</p>";
		echo '<h3>Whitelist</h3>';
		echo 'Valid entry is a comma separated list of <a href="http://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing#CIDR_blocks" target="_blank">CIDR blocks</a>.  Eg: <pre>4.2.2.1/24,192.168.1.15/32</pre>';
	}

	//this function will take a comma separated list of cidr blocks and validate them, deleting the ones that don't match
	function force_login_options_validate($input) {
		$newinput['whitelist'] = preg_replace('/\s+/', '', $input['whitelist']);
		$newinput['redirect'] = trim($input['redirect']);
		$ranges = explode(',',$newinput['whitelist']);
		foreach ($ranges as $key=>$range) {
			if(!preg_match('/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/(\d|[1-2]\d|3[0-2]))$/i', $range)) {
				unset($ranges[$key]);
			}
		}
		$newinput['whitelist'] = implode(',',$ranges);
		$newinput['redirect'] = htmlspecialchars($newinput['redirect']);
		return $newinput;
	}

	// check if ip fits in cidr block
	function force_login_cidr_match($ip, $cidr) {
		list($subnet, $mask) = explode('/', $cidr);

		if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1) ) == ip2long($subnet)) { 
			return true;
		}

		return false;
	}

	function force_login()
	{
		$force_login_options = get_option('force_login_options');
		$redirect_to = ($force_login_options['redirect'] !== '') ? $force_login_options['redirect'] : $_SERVER['REQUEST_URI'];

		//check REMOTE_ADDR against our whitelisted ip range
		$whitelisted_cidrs = explode(',',$force_login_options['whitelist']);
		foreach($whitelisted_cidrs as $whitelisted_cidr) {
			if(force_login_cidr_match($_SERVER['REMOTE_ADDR'],$whitelisted_cidr)) {
				return;
			}
		}

		if ( ! is_user_logged_in() )
		{
			if ( is_feed() )
			{
				$credentials = array();
				$credentials['user_login'] = $_SERVER['PHP_AUTH_USER'];
				$credentials['user_password'] = $_SERVER['PHP_AUTH_PW'];

				$user = wp_signon( $credentials );

				if ( is_wp_error( $user ) )
				{
					header( 'WWW-Authenticate: Basic realm="' . $_SERVER['SERVER_NAME'] . '"' );
					header( 'HTTP/1.0 401 Unauthorized' );
					die();
					
				} // if

			} // if
			
			else
			{

		  		header( 'Location: /wp-login.php?redirect_to=' . $redirect_to );
				die();

			} // else

	  	} // if

	} // force_login

?>
