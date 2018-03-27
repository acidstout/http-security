#Description

This plugin helps setting up the various header instructions included in the HTTP protocol allowing for easy improvement of your website's security.

This plugin provides enabling of the following measures:

* HSTS (Strict-Transport-Security)
* PKP (Public-Key-Pinning)
* CSP (Content-Security-Policy)
* Clickjacking mitigation (X-Frame-Options in main site)
* XSS protection (X-XSS-Protection)
* Disable content sniffing (X-Content-Type-Options)
* Referrer policy
* Expect-CT
* Remove PHP version information from HTTP header
* Remove WordPress version information from HTML header

[securityheaders.io](https://securityheaders.io/) is a useful resource for evaluating your website's security.

As usual, make sure to understand the meaning of these options and to run full tests on your website as some options may result in some features stop working.


&nbsp;
#Installation

1. Unpack the plugin archive to the `/wp-content/plugins/` folder, or manually upload its content to the `/wp-content/plugins/http-security/` folder.
1. Activate the plugin through the "Plugins" screen in WordPress.
1. Use "Settings --> HTTP Security" to configure the plugin.


&nbsp;
#Frequently Asked Questions

How can I test the plugin runs effectively?

Check the HTTP headers of your website using the developer tools of your browser. Keep in mind that it depends on your browser to respect the sent HTTP headers. Old browsers do not understand those headers and simply ignore them. That's nothing this plugin can magically fix.
