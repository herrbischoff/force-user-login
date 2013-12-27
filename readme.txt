=== Force User Login ===
Contributors: Integer Development, Marcel Bischoff, Jeff Vogt
Donate Link: Don't worry about it
Tags: force user login, login, password, privacy
Requires at least: 2.0.2
Tested up to: 3.4.2
Stable tag: 1.4

Very small plugin that forces users to login to view blog content.

== Description ==

This is a very small plugin that forces users to login before viewing any content. This is done by checking if the user is logged in, and if not, redirecting them to the login page. Users attempting to view blog content via RSS are also authenticated via HTTP Auth. 

== Installation ==

1. Upload `force-login.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set a custom redirect page or whitelisted IP ranges through the 'Settings->Force Login Menu' page

== Frequently Asked Questions ==

= Can I change where the user is redirected after logging in? =

Yes! Typically it will redirect to $_SERVER['REQUEST_URI'];` but you can override this on the 'Settings->Force Login Menu' page.

== Screenshots ==

None taken.. Just a login screen.

== Changelog ==

= 1.4 =
* Added a settings page and the ability to skip the login process for whitelisted IP ranges

= 1.3 =
* Adapted for all kinds of sites, regardless of the URL structure. Validated functionality.

= 1.2 =
* Last version by Integer Development. Was broken for sites not located in the domain root. Not updated in years.