<?php
/**
 * Plugin Name: HTTP Security Lite
 * Description: Use HTTP headers or htaccess file to improve security of a website. Based on the http-security plugin 2.4 by Carl Conrad.
 * Tags: security, HTTP headers, htaccess, HSTS, PKP, HTTPS, CSP, Referrer, X-Frame-Options
 * Version: 2.5.3.1
 * Author: Nils Rekow
 * Author URI: https://rekow.ch
 * License: GPL3
 */

if (!defined('ABSPATH')) {
    exit;
}


// Define plugin title
define('HTTP_SECURITY_PLUGIN_TITLE', 'HTTP Security Lite');

// Define debug-mode of this plugin. If enabled, the generated header string will be logged.
define('HSL_DEBUG', false);

// Use plugin folder as plugin name. Fallback to hard-coded default.
$lastslashpos = strrpos(__DIR__, DIRECTORY_SEPARATOR);
$plugin_name = ($lastslashpos !== false) ? substr(__DIR__, $lastslashpos + 1) : 'http-security-lite';

// Defines global plugin name.
define('HTTP_SECURITY_PLUGIN_NAME', $plugin_name);

// Used to provide proper links to stylesheet and javascript files of the plugin's settings page.
define('HTTP_SECURITY_PLUGIN_DIR', plugins_url('', __FILE__));


use httpSecurity\WPAddActionProxy;
use httpSecurity\httpSecurity;

require_once 'include/http-security.class.php';

// Init httpSecurity class
$httpSecurity = new httpSecurity();


// Init plugin only if we currently don't uninstall it.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	
	// Add security options to header
	new WPAddActionProxy($httpSecurity, 'send_headers', '_add_header');
	
	
	/**
	 * Add actions via proxy if user is in admin panel. 
	 */
	if (is_admin()) {
		require_once 'include/settings.inc.php';
		
		// Add options page
		new WPAddActionProxy($httpSecurity, 'admin_menu', '_options_page');
		
		// Register settings
		new WPAddActionProxy($httpSecurity, 'admin_init', '_register_settings');
		
		// Copy options of main site to new multisite
		if (is_multisite()) {
			new WPAddActionProxy($httpSecurity, 'wpmu_new_blog', '_copy_main_site_options');
		}
	}
}