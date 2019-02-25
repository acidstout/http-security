## Description

This plugin helps setting up the various header instructions included in the HTTP protocol allowing for easy improvement of your website's security.

This plugin provides enabling of the following measures:

* HSTS (Strict-Transport-Security)
* PKP (Public-Key-Pinning)
* CSP (Content-Security-Policy)
* Feature policy
* Clickjacking mitigation (X-Frame-Options in main site)
* XSS protection (X-XSS-Protection)
* Disable content sniffing (X-Content-Type-Options)
* Referrer policy
* Expect-CT
* Remove PHP version information from HTTP header
* Remove WordPress version information from HTML header
* and some more ...

To evaluate your website's security check out [securityheaders.io](https://securityheaders.io/) and [Qualys SSL Labs](https://ssllabs.com/ssltest/).

As usual, make sure to understand the meaning of these options and to run full tests on your website as some options may result in some features stop working.


&nbsp;
## Installation

1. Unpack the plugin archive to the `/wp-content/plugins/` folder.
1. Activate the plugin through the "Plugins" screen in WordPress.
1. Use "Settings --> HTTP Security Lite" to configure the plugin.


&nbsp;
## Known issues

The translations available for this plugin are a complete mess. I recommend you stick with the built-in English translation or create your own translation files for this plugin. I renamed the plugin in order to not get any messed up translations. You may rename the plugin back to its original name by renaming the plugin's folder to "http-security". Not need to change the code for that. Keep in mind that this might show you a notice of an available update once Carl Conrad's plugin reaches a higher version number than this plugin.


&nbsp;
## Frequently Asked Questions

How can I test the plugin runs effectively?

Check the HTTP headers of your website using the developer tools of your browser. Keep in mind that it depends on your browser to respect the sent HTTP headers. Old browsers do not understand those headers and simply ignore them. That's nothing this plugin can magically fix.


&nbsp;
## Links

Find the original plugin in the [WordPress plugin directory](https://wordpress.org/plugins/http-security/).


&nbsp;
## Thanks and some personal notes
I would like to thank Carl Conrad for the idea of the plugin. To my mind this thing is a valuable addition to the security of WordPress and should be part of the basic setup.

This plugin is loosely based on his version 2.4.1. In fact I had to rewrite most of the functions in order to make them work the way I want or make them work at all. For example I wanted an easy way to be able to add new options without the need to modify any HTML and/or CSS code. Also Public Key Pinning features were missing, the uninstall routine did not work properly and the overall performance was not great. That has all been fixed, so enjoy this version and fork it if you like. 
