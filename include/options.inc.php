<?php
/**
 * Plugin options. Includes complete set of ids, labels, descriptions etc. of all options. Extend at will. 
 * 
 * @author nrekow
 * 
 * @var array $options
 */


$options = array(
		
		/* General */
		'general' => array(
				'HSTS' => array(
						'id' => 'http_security_sts',
						'flag' => 'http_security_sts_flag',
						'label' => 'Force HTTPS protocol',
						'description' => 'The HTTP Strict-Transport-Security response header (often abbreviated as HSTS)  lets a web site tell browsers that it should only be accessed using HTTPS, instead of using HTTP.',
						'multisite_compatible' => true,
						'options' => array(
								'http_security_sts_subdomains_flag' => array(
										'label' => 'Include subdomains',
										'description' => 'If this optional parameter is specified, this rule applies to all of the site\'s subdomains as well.',
										'type' => 'checkbox',
										'multisite_compatible' => true
								),
								'http_security_sts_preload_flag' => array(
										'label' => 'Preload',
										'description' => 'Google maintains an HSTS preload service. By following the guidelines and successfully submitting your domain, browsers will never connect to your domain using an insecure connection. While the service is hosted by Google, all browsers have stated an intent to use (or actually started using) the preload list. However, it is not part of the HSTS specification and should not be treated as official.',
										'post_label' => ' (see <a href="https://hstspreload.org/" target="_blank" rel="noopener">HSTS Preload List Submission</a> for details).',
										'type' => 'checkbox',
										'multisite_compatible' => true
								),
								'http_security_sts_max_age' => array(
										'label' => 'Max age:',
										'description' => 'The time, in seconds, that the browser should remember that a site is only to be accessed using HTTPS.',
										'post_label' => '&nbsp;' . __('seconds', HTTP_SECURITY_PLUGIN_NAME) . ' (86400 = ' . __('one day', HTTP_SECURITY_PLUGIN_NAME) . ', 2592000 = ' . __('one month', HTTP_SECURITY_PLUGIN_NAME) . ', 5184000 = ' . __('two months', HTTP_SECURITY_PLUGIN_NAME) . ', 31536000 = ' . __('one year', HTTP_SECURITY_PLUGIN_NAME) . ' (' . __('recommended', HTTP_SECURITY_PLUGIN_NAME) . '))',
										'type' => 'age',
										'multisite_compatible' => true
								)
						)
				),
				'Public-Key-Pinning' => array(
						'id' => 'http_security_pkp',
						'flag' => 'http_security_pkp_flag',
						'label' => 'Enable Public-Key-Pinning',
						'description' => 'The Public Key Pinning Extension for HTML5 (HPKP) is a security feature that tells a web client to associate a specific cryptographic public key with a certain web server to decrease the risk of MITM attacks with forged certificates.',
						'options' => array(
								'http_security_pkp_reportonly_flag' => array(
										'label' => 'Report only',
										'description' => 'Instead of using a Public-Key-Pins header you can also use a Public-Key-Pins-Report-Only header. This header only sends reports to the report-uri specified in the header and does still allow browsers to connect to the webserver even if the pinning is violated.',
										'type' => 'checkbox'
								),
								'http_security_pkp_subdomains_flag' => array(
										'label' => 'Include subdomains',
										'description' => 'If this optional parameter is specified, this rule applies to all of the site\'s subdomains as well.',
										'type' => 'checkbox'
								),
								'http_security_pkp_keys' => array(
										'label' => 'SPKI fingerprints:',
										'description' => 'This is the Base64 encoded Subject Public Key Information (SPKI) fingerprint. It is possible to specify multiple pins for different public keys. Add fingerprints in the form of pin-sha256=&quot;cmVwbGFjZSB3aXRoIHJlYWwgZmluZ2VycHJpbnQ=&quot;; and mind the trailing semicolon. Some browsers might allow other hashing algorithms than SHA-256 in the future.',
										'type' => 'textarea'
								),
								'http_security_pkp_maxage' => array(
										'label' => 'Max age:',
										'description' => 'The time, in seconds, that the browser should remember that this site is only to be accessed using one of the defined keys.',
										'type' => 'age',
										'post_label' => '&nbsp;' . __('seconds', HTTP_SECURITY_PLUGIN_NAME) . ' (86400 = ' . __('one day', HTTP_SECURITY_PLUGIN_NAME) . ', 2592000 = ' . __('one month', HTTP_SECURITY_PLUGIN_NAME) . ', 5184000 = ' . __('two months', HTTP_SECURITY_PLUGIN_NAME) . ' (' . __('recommended', HTTP_SECURITY_PLUGIN_NAME) . ')' . ', 31536000 = ' . __('one year', HTTP_SECURITY_PLUGIN_NAME) . ')',
										'size' => 10
								),
								'http_security_pkp_reporturi' => array(
										'label' => 'Report URI:',
										'description' => 'If this optional parameter is specified, pin validation failures are reported to the given URL.',
										'type' => 'text',
										'size' => 80
								),
						)
				),
				'Expect-CT' => array(
						'id' => 'http_security_expect_ct',
						'flag' => 'http_security_expect_ct_flag',
						'label' => 'Enable Expect-CT',
						'description' => 'The Expect-CT header allows sites to opt in to reporting and/or enforcement of Certificate Transparency requirements, which prevents the use of misissued certificates for that site from going unnoticed. When a site enables the Expect-CT header, they are requesting that the browser check that any certificate for that site appears in public CT logs.',
						'options' => array(
								'http_security_expect_ct_enforce_flag' => array(
										'label' => 'Enforce',
										'description' => 'Signals to the user agent that compliance with the Certificate Transparency policy should be enforced (rather than only reporting compliance) and that the user agent should refuse future connections that violate its Certificate Transparency policy.',
										'type' => 'checkbox'
								),
								'http_security_expect_ct_max_age' => array(
										'label' => 'Max age:',
										'description' => 'Specifies the number of seconds after reception of the Expect-CT header field during which the user agent should regard the host from whom the message was received as a known Expect-CT host. If a cache receives a value greater than it can represent, or if any of its subsequent calculations overflows, the cache will consider the value to be either 2147483648 (2^31) or the greatest positive integer it can conveniently represent.',
										'type' => 'age',
										'post_label' => '&nbsp;' . __('seconds', HTTP_SECURITY_PLUGIN_NAME) . ' (86400 = ' . __('one day', HTTP_SECURITY_PLUGIN_NAME) . ', 2592000 = ' . __('one month', HTTP_SECURITY_PLUGIN_NAME) . ' (' . __('recommended', HTTP_SECURITY_PLUGIN_NAME) . ')' . ', 5184000 = ' . __('two months', HTTP_SECURITY_PLUGIN_NAME) . ', 31536000 = ' . __('one year', HTTP_SECURITY_PLUGIN_NAME) . ')',
										'size' => 10
								),
								'http_security_expect_ct_report_uri' => array(
										'label' => 'Report URI:',
										'description' => 'Specifies the URI to which the user agent should report Expect-CT failures.',
										'type' => 'text',
										'size' => 80
								)
						)
				),
				'X-frame-options' => array(
						'id' => 'http_security_x_frame',
						'flag' => 'http_security_x_frame_flag',
						'label' => 'Manage display in remote frames',
						'description' => 'The X-Frame-Options HTTP response header can be used to indicate whether or not a browser should be allowed to render a page in a <frame>, <iframe> or <object> . Sites can use this to avoid clickjacking attacks, by ensuring that their content is not embedded into other sites. The added security is only provided if the user accessing the document is using a browser supporting X-Frame-Options.',
						'options' => array(
								'http_security_x_frame_deny' => array(
										'name' => 'http_security_x_frame_options',
										'label' => 'DENY',
										'description' => 'The page cannot be displayed in a frame, regardless of the site attempting to do so.',
										'type' => 'radio'
								),
								'http_security_x_frame_sameorigin' => array(
										'name' => 'http_security_x_frame_options',
										'label' => 'SAMEORIGIN',
										'description' => 'The page can only be displayed in a frame on the same origin as the page itself. The spec leaves it up to browser vendors to decide whether this option applies to the top level, the parent, or the whole chain, although it is argued that the option is not very useful unless all ancestors are also in the same origin.',
										'type' => 'radio'
								),
								'http_security_x_frame_allow_from' => array(
										'name' => 'http_security_x_frame_options',
										'label' => 'ALLOW-FROM',
										'description' => 'The page can only be displayed in a frame on the specified origin. Note that in Firefox this still suffers from the same problem as SAMEORIGIN did — it doesn\'t check the frame ancestors to see if they are in the same origin.',
										'type' => 'radio'
								),
								'http_security_x_frame_origin' => array(
										'label' => 'Allow from:',
										'description' => '',
										'type' => 'text',
										'size' => 80
								)
						)
				),
				'Referrer policy' => array(
						'id' => 'http_security_referrer_policy',
						'flag' => 'http_security_referrer_policy',
						'label' => 'Referrer policy:',
						'description' => 'The Referrer-Policy HTTP header governs which referrer information, sent in the Referer header, should be included with requests made.',
						'type' => 'select',
						'options' => array(
								'',
								'no-referrer',
								'no-referrer-when-downgrade',
								'same-origin',
								'origin',
								'strict-origin',
								'origin-when-cross-origin',
								'strict-origin-when-cross-origin',
								'unsafe-url'
						)
				),
				'Other options' => array(
						'type' => 'list',
						'options' => array(
								array(
										'id' => 'http_security_x_xss_protection',
										'description' => 'The HTTP X-XSS-Protection response header is a feature of Internet Explorer, Chrome and Safari that stops pages from loading when they detect reflected cross-site scripting (XSS) attacks. Although these protections are largely unnecessary in modern browsers when sites implement a strong Content-Security-Policy that disables the use of inline JavaScript (\'unsafe-inline\'), they can still provide protections for users of older web browsers that don\'t yet support CSP.',
										'label' => 'Force XSS protection',
										'multisite_compatible' => true
								),
								array(
										'id' => 'http_security_x_content_type_options',
										'description' => 'The X-Content-Type-Options response HTTP header is a marker used by the server to indicate that the MIME types advertised in the Content-Type headers should not be changed and be followed. This allows to opt-out of MIME type sniffing, or, in other words, it is a way to say that the webmasters knew what they were doing.',
										'label' => 'Disable content sniffing',
										'multisite_compatible' => true
								),
								array(
										'id' => 'http_security_remove_php_version',
										'description' => 'Removes the PHP version information from the HTTP header.',
										'label' => 'Remove PHP version information from HTTP header',
										'multisite_compatible' => true
								),
								array(
										'id' => 'http_security_remove_wordpress_version',
										'description' => 'Removes the WordPress version information from the HTML head section.',
										'label' => 'Remove WordPress version information from header',
										'multisite_compatible' => true
								)
						)
				)
		),
		
		/* Content Security Policy */
		'csp' => array(
				'flags' => array(
						array(
								'id' => 'http_security_csp_flag',
								'description' => 'The HTTP Content-Security-Policy response header allows web site administrators to control resources the user agent is allowed to load for a given page. With a few exceptions, policies mostly involve specifying server origins and script endpoints. This helps guard against cross-site scripting attacks (XSS).',
								'label' => 'Enable Content Security Policy'
						),
						array(
								'id' => 'http_security_csp_reportonly_flag',
								'description' => 'The HTTP Content-Security-Policy-Report-Only response header allows web developers to experiment with policies by monitoring (but not enforcing) their effects. These violation reports consist of JSON documents sent via an HTTP POST request to the specified URI.',
								'label' => 'Report only'
						)
				),
				
				'directives' => array(
						'fetch' => array(
								array('id' => 'http_security_csp_child',						'label' => 'child-src',					'description' => 'The deprecated child-src directive defines the valid sources for web workers and nested browsing contexts loaded using elements such as <frame> and <iframe>. For workers, non-compliant requests are treated as fatal network errors by the user agent.'),
								array('id' => 'http_security_csp_connect',						'label' => 'connect-src',				'description' => 'The connect-src directive restricts the URLs which can be loaded using script interfaces.'),
								array('id' => 'http_security_csp_default',						'label' => 'default-src',				'description' => 'The default-src directive serves as a fallback for the other CSP fetch directives.'),
								array('id' => 'http_security_csp_font',							'label' => 'font-src',					'description' => 'The font-src directive specifies valid sources for fonts loaded using @font-face.'),
								array('id' => 'http_security_csp_frame',						'label' => 'frame-src',					'description' => 'The frame-src directive specifies valid sources for nested browsing contexts loading using elements such as <frame> and <iframe>.'),
								array('id' => 'http_security_csp_img',							'label' => 'img-src',					'description' => 'The img-src directive specifies valid sources of images and favicons.'),
								array('id' => 'http_security_csp_manifest',						'label' => 'manifest-src',				'description' => 'The manifest-src directive specifies which manifest can be applied to the resource.'),
								array('id' => 'http_security_csp_media',						'label' => 'media-src',					'description' => 'The media-src directive specifies valid sources for loading media using the <audio> and <video> elements.'),
								array('id' => 'http_security_csp_object',						'label' => 'object-src',				'description' => 'The object-src directive specifies valid sources for the <object>, <embed>, and <applet> elements.'),
								array('id' => 'http_security_csp_script',						'label' => 'script-src',				'description' => 'The script-src directive specifies valid sources for JavaScript. This includes not only URLs loaded directly into <script> elements, but also things like inline script event handlers (onclick) and XSLT stylesheets which can trigger script execution.'),
								array('id' => 'http_security_csp_style',						'label' => 'style-src',					'description' => 'The style-src directive specifies valid sources for sources for stylesheets.'),
								array('id' => 'http_security_csp_worker',						'label' => 'worker-src',				'description' => 'The worker-src directive specifies valid sources for Worker, SharedWorker, or ServiceWorker scripts.')
						),
						'document' => array(
								array('id' => 'http_security_csp_base_uri',						'label' => 'base-uri',					'description' => 'The base-uri directive restricts the URLs which can be used in a document\'s <base> element. If this value is absent, then any URI is allowed. If this directive is absent, the user agent will use the value in the <base> element.'),
								array('id' => 'http_security_csp_plugin_types',					'label' => 'plugin-types',				'description' => 'The plugin-types directive restricts the set of plugins that can be embedded into a document by limiting the types of resources which can be loaded.'),
								array('id' => 'http_security_csp_sandbox',						'label' => 'sandbox',					'description' => 'The sandbox directive enables a sandbox for the requested resource similar to the <iframe> sandbox attribute. It applies restrictions to a page\'s actions including preventing popups, preventing the execution of plugins and scripts, and enforcing a same-origin policy.')
						),
						'navigation' => array(
								array('id' => 'http_security_csp_form_action',					'label' => 'form-action',				'description' => 'The form-action directive restricts the URLs which can be used as the target of a form submissions from a given context.'),
								array('id' => 'http_security_csp_frame_ancestors',				'label' => 'frame-ancestors',			'description' => 'The frame-ancestors directive specifies valid parents that may embed a page using <frame>, <iframe>, <object>, <embed>, or <applet>.'),
						),
						'other' => array(
								array('id' => 'http_security_csp_block_all_mixed_content',		'label' => 'block-all-mixed-content',	'description' => 'The block-all-mixed-content directive prevents loading any assets using HTTP when the page is loaded using HTTPS. All mixed content resource requests are blocked, including both active and passive mixed content. This also applies to <iframe> documents, ensuring the entire page is mixed content free. The upgrade-insecure-requests directive is evaluated before block-all-mixed-content and If the former is set, the latter is effectively a no-op. It is recommended to set one directive or the other – not both.',	'type' => 'checkbox'),
								array('id' => 'http_security_csp_require_sri_for',				'label' => 'require-sri-for',			'description' => 'The require-sri-for directive instructs the client to require the use of Subresource Integrity for scripts or styles on the page.'),
								array('id' => 'http_security_csp_upgrade_insecure_requests',	'label' => 'upgrade-insecure-requests',	'description' => 'The upgrade-insecure-requests directive instructs user agents to treat all of a site\'s insecure URLs (those served over HTTP) as though they have been replaced with secure URLs (those served over HTTPS). This directive is intended for web sites with large numbers of insecure legacy URLs that need to be rewritten. The upgrade-insecure-requests directive is evaluated before block-all-mixed-content and if it is set, the latter is effectively a no-op. It is recommended to set one directive or the other, but not both. The upgrade-insecure-requests directive will not ensure that users visiting your site via links on third-party sites will be upgraded to HTTPS for the top-level navigation and thus does not replace the Strict-Transport-Security (HSTS) header, which should still be set with an appropriate max-age to ensure that users are not subject to SSL stripping attacks.',	'type' => 'checkbox'),
						)
				)
		),
		
		/* .htaccess */
		'htaccess' => array(
				'id' => 'http_security_htaccess_flag',
				'description' => 'If enabled the header will not be rewritten. Instead you need to add these lines to your .htaccess file.',
				'label' => 'Disable header rewriting and use .htaccess file instead'
		)
);
