<?php
/*
    Plugin Name: Force User Login
    Plugin URI: https://github.com/herrbischoff/force-user-login
    Description: A really small plugin that forces a user to login before being able to view any blog content.
    Version: 1.4
    Author: The Integer Group Development Team
    Contributors: Marcel Bischoff
    Author URI: http://www.integer.com

    Copyright 2009 The Integer Group Dev Team  (email : development@integerdenver.com)
    Made working again and maintained by Marcel Bischoff <marcel@herrbischoff.com>

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

include_once('updater.php');

if ( is_admin() ) {
    $config = array(
        'slug' => plugin_basename( __FILE__ ),
        'proper_folder_name' => 'force-user-login',
        'api_url' => 'https://github.com/herrbischoff/force-user-login.git',
        'raw_url' => 'https://raw.githubusercontent.com/herrbischoff/force-user-login/master',
        'github_url' => 'https://github.com/herrbischoff/force-user-login',
        'zip_url' => 'https://github.com/herrbischoff/force-user-login/archive/master.zip',
        'sslverify' => true,
        'requires' => '4.0',
        'tested' => '4.3.1',
        'readme' => 'README.md'
    );
    new WP_GitHub_Updater( $config );
}

add_action( 'template_redirect', 'force_login' );

function force_login() {

    // Change this line to define where logging in redirects the user to, i.e. '/', '/wp-admin', etc.
    // Default is the request address which should work fine for most uses.
    $redirect_to = $_SERVER['REQUEST_URI'];

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
            }
        }
        else
        {
            header( 'Location: ' . get_site_url() . '/wp-login.php?redirect_to=' . $redirect_to );
            die();
        }
    }
}
