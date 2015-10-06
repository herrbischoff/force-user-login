Description
===========

This is a very small plugin that forces users to login before viewing any content. This is done by checking if the user is logged in, and if not, redirecting them to the login page. Users attempting to view blog content via RSS are also authenticated via HTTP Auth.

Installation
============

1. Run `git clone --recursive https://github.com/herrbischoff/force-user-login.git` in the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

Frequently Asked Questions
==========================

Can I change where the user is redirected after logging in?
-----------------------------------------------------------

Yes! A variable called $redirect_to (line 33) is currently set to redirect the user to the page they were trying to access. If you changed that line from

`$redirect_to = $_SERVER['REQUEST_URI'];`

to

`$redirect_to = '/';`

it would redirect the user to the home page.

Screenshots
===========

None taken. Just a login screen.

Changelog
=========

1.4.1
-----
* Change installation instructions.

1.4.0
-----
* Add [WordPress GitHub Plugin Updater](https://github.com/radishconcepts/WordPress-GitHub-Plugin-Updater) class to enable easier updates.

1.3.3
-----
* Minor cleanup and move to new home.

1.3.2
-----
* Code now conforms to PSR-2 coding standards. Deleted readme.txt.

1.3.1
-----
* Fixed URI redirection error due to deprecated `WP_SITEURL` constant.

1.3
---
* Adapted for all kinds of sites, regardless of the URL structure. Validated functionality.

1.2
---
* Last version by Integer Development. Was broken for sites not located in the domain root. Not updated in years.
