## Changes
2.5.3.4-test
* Changed way to get configured options.

2.5.3.3
* Fixed call to undefined function upon very first plugin activation. 

2.5.3.2
* Fixed issue where settings page would not load.

2.5.3.1
* TESTING: Changed the way options.in.php is included. This should fix the undefined index error around line 220, finally.

2.5.3
* Added Access-Control-Allow-Origin (CORS) directive.
* Added X-Permitted-Cross-Domain-Policies directive.
* Added missing Feature-Policy directives (e.g. accelerometer, gyroscope, ...).
* Improved header and .htaccess generation when using feature policies. 

2.5.2.1
* TESTING: Changed order of requirements. This should fix the undefined index error around line 220.

2.5.2
* Fixed bug where links stopped working if plugin has been renamed.
* Fixed uninstall routine.
* Don't log empty headers if debug-mode is enabled.
* Tested with WordPress 5.1

2.5.1
* Fixed bug where multiple headers where served as one response.
* Fixed missing Public-Key-Pinning policy when not using .htaccess file.
* Added support for Feature-Policy directive.
* Tested with WordPress 5.0.3

2.5.0
* Complete rewrite of Carl Conrad's plugin from scratch.
* Separated code from design where applicable.
* Fixed numerous bugs and issues.
* Added proper support for WordPress MU.
* Added proper initialization of the plugin.
* Added options for Public Key Pinning.
* Added easily extendable options.
* Reduced the default load if not on settings page.
* Cleaned up English translation.
* Cleaned up style.
* Tested with WordPress 4.9.4

2.4.1
* Latest version by Carl Conrad

2.4
* Added .htaccess instructions

2.3.2
* Tested with WordPress 4.9

2.3
* Added support for Expect-CT
* Cleaned up the interface

2.2
* Switched to languages packs

2.1
* Added support for Referrer-Policy directive
* Added uninstall database cleanup

2.0
* Added support for all Content-Security-Policy directives
* Reworked the user interface

1.11
* Added setting the mode for x-frame-options

1.10.7
* Removed HSTS header when connected in HTTP

1.10.6
* Improved ergonomics

1.10.3
* Fixed HSTS syntax warning

1.10
* Added support for Content-Security-Policy

1.9
* Added critical issues notifications

1.8
* Included localization support

1.7.5
* Added max-age option to HSTS setting

1.7.3
* File name change to comply with Wordpress guidelines

1.7
* Minor fixes and code cleaning

1.6
* Added option to remove WordPress version information from the header

1.5
* Added option to remove PHP version information from the HTTP header

1.4
* Included link to submit site preload to browsers
* Reduced HSTS max-age to one year

1.3
* Added X-Frame-Options protection.
* Added X-Content-Type-Options protection.
* Added HSTS options.

1.2
* Repository fix.

1.1
* Added XSS protection option.

1.0
* First stable version providing basic HSTS support.