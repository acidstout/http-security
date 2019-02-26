<?php
/**
 * httpSecurity class
 * 
 * Provides functions to get configured options and modify the header respectively.
 * Also generates preview of required chnages to the .htaccess file if header modification
 * is not desired.
 * 
 * @author nrekow
 * 
 */


namespace httpSecurity;

class WPAddActionProxy {
	private $_class = null;
	private $_func = null;
	private $_tag = null;
	
	
	/**
	 * Constructor
	 * 
	 * @param object $class
	 * @param string $tag
	 * @param string $func
	 */
	public function __construct($class, $tag, $func) {
		$this->_class = $class;
		$this->_func = $func;
		$this->_tag = $tag;

		if (method_exists($this, $this->_func) && !empty($this->_tag)) {
			// WP add_action() with parameters.
			add_action($this->_tag, array(&$this, $this->_func));
		}
	}
	
	
	/**
	 * Adds security settings to HTTP header by calling the referenced
	 * method of the supplied class.
	 * 
	 * @return void
	 */
	public function _add_header() {
		$this->_class->addSecurityHeader();
	}
	
	
	/**
	 * Copy security options of main site to a new WPMU based site.
	 * Not all options are copied in order to improve compatibility
	 * with the new site. Those should be configured individually.
	 *
	 * @return void
	 */
	public function _copy_main_site_options() {
		global $httpSecurityOptions;
		
		$multisite_options = array();
		
		foreach ($httpSecurityOptions['general'] as $option) {
			if (isset($option['multisite_compatible']) && $option['multisite_compatible'] === true) {
				$multisite_options[$option['flag']] = $this->_class->getOption($option['flag']);
			}
			
			if (isset($option['options'])) {
				foreach ($option['options'] as $key => $value) {
					if (isset($value['multisite_compatible']) && $value['multisite_compatible'] === true) {
						$idx = $key;
						
						if (isset($option['type']) && $option['type'] == 'list' && isset($value['id'])) {
							$idx = $value['id'];
						}
						
						$multisite_options[$idx] = $this->_class->getOption($idx);
					}
				}
			}
		}
		
		$sites = get_sites();
		foreach ($sites as $site) {
			switch_to_blog($site->blog_id);
			
			foreach ($multisite_options as $key => $value) {
				update_option($key, $value);
			}
			
			restore_current_blog();
		}
	}
	
	
	/**
	 * Initialize the plugin's options page.
	 * 
	 * @return void
	 */
	public function _options_page() {
		$count = 0;
		$menu_entry = HTTP_SECURITY_PLUGIN_TITLE;
		
		if (!$this->_class->isSecure()) {
			$count++;
		}
		
		if (username_exists('admin')) {
			$count++;
		}
		
		if ($count > 0) {
			$menu_entry .= ' <span class="update-plugins count-1"><span class="update-count">' . $count . '</span></span>';
		}
		
		// Decide which capability to set in relation to the WP install type.
		$capability = 'manage_options';
		if (defined('WP_NETWORK_ADMIN') && WP_NETWORK_ADMIN && is_multisite()) {
			$capability = 'manage_network_options';
		}
		
		add_options_page(__(HTTP_SECURITY_PLUGIN_TITLE . ' Options', HTTP_SECURITY_PLUGIN_NAME), $menu_entry, $capability, HTTP_SECURITY_PLUGIN_NAME, 'http_security_options_page_html');
	}
	
	
	/**
	 * Register settings and set configured values to httpSecurity class object.
	 *
	 * If the options.inc.php file gets extended new settings will be registered
	 * automatically if the format of the $options array is preserved.
	 *
	 * The option group ($entry) is used by settings_fields() function to assign posted
	 * settings to the actual options group.
	 *
	 * @return void
	 */
	public function _register_settings() {
		// Generate a list of all available plugin options.
		$registerOptions = $this->_class->getRegisterOptions();
		
		// Register settings
		foreach ($registerOptions as $entry => $option) {
			($entry != 'general') ? $entry = HTTP_SECURITY_PLUGIN_NAME . '-' . $entry : $entry = HTTP_SECURITY_PLUGIN_NAME;
			//if (is_array($option)) {
				foreach ($option as $value) {
					//error_log($entry . ' => ' . $value);
					
					register_setting($entry, $value);
					$this->_class->setOption(str_replace('-', '_', $value));
				}
			/*
			} else {
				register_setting($entry, $value);
				$this->_class->setOption(str_replace('-', '_', $value));
			}
			*/
		}
	}
}


class httpSecurity {
	
	private $_options = array();
	private $_registerOptions = array();
	private $_header_string = false;
	private $_httpSecurityOptions = array();
	
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->_initSettings();
	}
	
	
	/**
	 * Returns the value of an option
	 * 
	 * @param string $option
	 * @return string|boolean
	 */
	public function getOption($option) {
		if (isset($this->_options[$option])) {
			return $this->_options[$option];
		}
		
		return false;
	}
	
	
	/**
	 * Stores a configured value in options (e.g. fetches value of option and caches it).
	 * 
	 * @param string $option
	 * @return void
	 */
	public function setOption($option) {
		$this->_options[$option] = get_option($option);
	}
	
	
	/**
	 * Returns an array of all available option flags.
	 * 
	 * @return array
	 */
	public function getRegisterOptions() {
		return $this->_registerOptions;
	}
	
	
	/**
	 * Gets a list of all options of this plugin.
	 *
	 * @return array
	 */
	public function getPluginOptions() {
		// General
		foreach ($this->_httpSecurityOptions['general'] as $key => $value) {
			if (isset($value['flag'])) {
				$this->_registerOptions['general'][] = $value['flag'];
				
				if (isset($value['options'])) {
					foreach ($value['options'] as $entry_key => $entry) {
						if (!isset($value['type'])) {
							$entry = (isset($entry['name'])) ? $entry['name'] : $entry_key;
							
							if (!in_array($entry, $this->_registerOptions)) {
								$this->_registerOptions['general'][] = $entry;
							}
						}
					}
				}
			} else {
				// Special evaluation of list type.
				if ($value['type'] == 'list') {
					foreach ($value['options'] as $entry_key => $entry) {
						if (!in_array($entry['id'], $this->_registerOptions)) {
							$this->_registerOptions['general'][] = $entry['id'];
						}
					}
				}
			}
		}
		
		// Content Security Policy
		foreach ($this->_httpSecurityOptions['csp'] as $option) {
			foreach ($option as $entry) {
				if (isset($entry['id'])) {
					if (!in_array($entry['id'], $this->_registerOptions)) {
						$this->_registerOptions['csp'][] = $entry['id'];
					}
				} else if(is_array($entry)) {
					foreach ($entry as $value) {
						if (isset($value['id']) && !in_array($value['id'], $this->_registerOptions)) {
							$this->_registerOptions['csp'][] = $value['id'];
						}
					}
				}
			}
		}
		
		// htaccess
		$this->_registerOptions['htaccess'][] = $this->_httpSecurityOptions['htaccess']['id'];
		
		return $this->_registerOptions;
	}
	
	
	/**
	 * Get Content Security Policy directives.
	 *
	 * @param array $httpSecurityOptions
	 * @return string
	 */
	public function getCSPDirectives() {
		$header_string = '';
		
		foreach ($this->_httpSecurityOptions['csp']['directives'] as $key => $csp_directives) {
			foreach ($csp_directives as $csp_directive) {
				$value = $this->getOption($csp_directive['id']);
				
				if (!empty($value)) {
					$value = $csp_directive['label'] . ' ' . $value . '; ';
				}
				
				if (isset($csp_directive['type']) && $csp_directive['type'] == 'checkbox') {
					$value = $csp_directive['label'] . '; ';
				}
				
				$header_string .= $value;
			}
		}
		
		return $header_string;
	}
	
	
	/**
	 * Sends the modified header.
	 * 
	 * @return void
	 */
	public function addSecurityHeader() {
		if (!get_option('http_security_htaccess_flag')) {
			$this->_header_string = $this->_getSecurityHeader();
		}
		
		if ($this->_header_string) {
			// Split header into array ...
			$headers = explode('|', $this->_header_string);
			
			// ... to send all defined header responses. 
			foreach ($headers as $header) {
				if (!empty($header)) {
					header($header);
				}
			}
		}
		
		if ($this->_options['http_security_remove_php_version']) {
			header_remove('X-Powered-By');
		}
		
		if ($this->_options['http_security_remove_wordpress_version']) {
			remove_action('wp_head', 'wp_generator');
		}
	}
	
	
	/**
	 * Checks if the connection is secured by HTTPS.
	 * 
	 * @return boolean
	 */
	public function isSecure() {
		return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
	}
	
	
	/**
	 * Removes trailing char(s) from string.
	 *
	 * @param string $str
	 * @param string $chr
	 * @return string
	 */
	public function removeTrail($str, $chr) {
		$tmp = strrpos($str, $chr);
		if ($tmp !== false) {
			return substr(trim($str), 0, $tmp);
		}
		
		return $str;
	}
	
	
	/**
	 * Initializes plugin settings.
	 *
	 * @return void
	 */
	private function _initSettings() {
		if (!isset($this->_httpSecurityOptions) || !isset($this->httpSecurityOptions['general'])) {
			require 'options.inc.php';
			$this->_httpSecurityOptions = $httpSecurityOptions;
		}
		
		// Generate a list of all available plugin options.
		$options = $this->getPluginOptions();
		
		// Register settings
		foreach ($options as $entry => $option) {
			($entry != 'general') ? $entry = HTTP_SECURITY_PLUGIN_NAME . '-' . $entry : $entry = HTTP_SECURITY_PLUGIN_NAME;
			if (is_array($option)) {
				foreach ($option as $value) {
					$this->setOption(str_replace('-', '_', $value));
				}
			} else {
				$this->setOption(str_replace('-', '_', $value));
			}
		}
	}
	
	
	/**
	 * Generate header string in relation to configured options.
	 *
	 * @return string
	 */
	private function _getSecurityHeader() {
		$header_string = '';
		
		// HSTS options
		if ($this->isSecure() && $this->_options['http_security_sts_flag']) {
			$header_string .= 'Strict-Transport-Security:';
			
			if ($this->_options['http_security_sts_max_age']) {
				$header_string .= ' max-age=' . $this->_options['http_security_sts_max_age'] . ';';
			}
			
			if ($this->_options['http_security_sts_subdomains_flag']) {
				$header_string .= ' includeSubDomains;';
			}
			
			if ($this->_options['http_security_sts_preload_flag']) {
				$header_string .= ' preload;';
			}
			
			$header_string = $this->removeTrail($header_string, ';');
			
			$header_string .= '|';
		}
		
		
		// Public-Key-Pinning
		if ($this->_options['http_security_pkp_flag']) {
			if ($this->_options['http_security_pkp_keys']) {
				$header_string .= 'Public-Key-Pins';
				
				if ($this->_options['http_security_pkp_reportonly_flag']) {
					$header_string .= '-Report-Only';
				}
				
				$header_string .= ':';
				
				$header_string .= $this->_options['http_security_pkp_keys'];
				
				if ($this->_options['http_security_pkp_maxage']) {
					$header_string .= ' max-age=' . $this->_options['http_security_pkp_maxage'] . ';';
				}
				if ($this->_options['http_security_pkp_subdomains_flag']) {
					$header_string .= ' includeSubDomains;';
				}
				
				if ($this->_options['http_security_pkp_reporturi']) {
					$header_string .= ' report-uri=\"' . $this->_options['http_security_pkp_reporturi'] . '\";';
				}
				
				$header_string = $this->removeTrail($header_string, ';');
			
				$header_string .= '|';
			}
		}
		
		
		// Expect-CT options
		if ($this->isSecure() && $this->_options['http_security_expect_ct_flag']) {
			$header_string .= 'Expect-CT:';
			if ($this->_options['http_security_expect_ct_enforce_flag']) {
				$header_string .= ' enforce;';
			}
			
			if ($this->_options['http_security_expect_ct_max_age']) {
				$header_string .= ' max-age=' .  $this->_options['http_security_expect_ct_max_age'] . ';';
			}
			
			if ($this->_options['http_security_expect_ct_report_uri']) {
				$header_string .= ' report-uri=' . $this->_options['http_security_expect_ct_report_uri'] . ';';
			}
		
			$header_string = $this->removeTrail($header_string, ';');
		
			$header_string .= '|';
		}
		
		
		// Content-Security-Policy
		if ($this->_options['http_security_csp_flag']) {
			$header_string .= 'Content-Security-Policy';
			if ($this->_options['http_security_csp_reportonly_flag']) {
				$header_string .= '-Report-Only';
			}
			$header_string .= ': ';
			
			$header_string .= $this->getCSPDirectives();			
			
			$header_string = $this->removeTrail($header_string, ';');
		
			$header_string .= '|';
		}
		
		
		
		// Feature-Policy
		if ($this->_options['http_security_feature_policy_flag']) {
			$header_string .= 'Feature-Policy:';
			
			if ($this->_options['http_security_feature_policy_autoplay']) {
				$header_string .= ' autoplay ' . $this->_options['http_security_feature_policy_autoplay'] . ';';
			}

			if ($this->_options['http_security_feature_policy_camera']) {
				$header_string .= ' camera ' . $this->_options['http_security_feature_policy_camera'] . ';';
			}
			
			if ($this->_options['http_security_feature_policy_document_domain']) {
				$header_string .= ' document-domain ' . $this->_options['http_security_feature_policy_document_domain'] . ';';
			}
			
			if ($this->_options['http_security_feature_policy_encrypted_media']) {
				$header_string .= ' encrypted-media ' . $this->_options['http_security_feature_policy_encrypted_media'] . ';';
			}
			
			if ($this->_options['http_security_feature_policy_fullscreen']) {
				$header_string .= ' fullscreen ' . $this->_options['http_security_feature_policy_fullscreen'] . ';';
			}
			
			if ($this->_options['http_security_feature_policy_geolocation']) {
				$header_string .= ' geolocation ' . $this->_options['http_security_feature_policy_geolocation'] . ';';
			}
			
			if ($this->_options['http_security_feature_policy_microphone']) {
				$header_string .= ' microphone ' . $this->_options['http_security_feature_policy_microphone'] . ';';
			}
			
			if ($this->_options['http_security_feature_policy_midi']) {
				$header_string .= ' midi ' . $this->_options['http_security_feature_policy_midi'] . ';';
			}
			
			if ($this->_options['http_security_feature_policy_payment']) {
				$header_string .= ' payment ' . $this->_options['http_security_feature_policy_payment'] . ';';
			}
			
			if ($this->_options['http_security_feature_policy_vr']) {
				$header_string .= ' vr ' . $this->_options['http_security_feature_policy_vr'] . ';';
			}
			
			$header_string = $this->removeTrail($header_string, ';');
		
			$header_string .= '|';
		}

		
		// X-Frame options
		if ($this->_options['http_security_x_frame_flag']) {
			switch ($this->_options['http_security_x_frame_options']) {
				case 1:
					$header_string .= 'X-Frame-Options: DENY';
					break;
				case 2:
					$header_string .= 'X-Frame-Options: SAMEORIGIN';
					break;
				case 3:
					$header_string .= 'X-Frame-Options: ALLOW-FROM ' . $this->_options['http_security_x_frame_origin'];
					break;
			}
			
			$header_string .= '|';
		}
		
		
		if ($this->_options['http_security_referrer_policy']) {
			$header_string .= 'Referrer-Policy: ' . $this->_options['http_security_referrer_policy'] . '';
			$header_string .= '|';
		}
		
		
		if ($this->_options['http_security_x_xss_protection']) {
			$header_string .= 'X-XSS-Protection: 1; mode=block';
			$header_string .= '|';
		}
		
		if ($this->_options['http_security_x_content_type_options']) {
			$header_string .= 'X-Content-Type-Options: nosniff';
		}
		
		
		if (defined('WP_DEBUG') && WP_DEBUG && !empty($header_string)) {
			error_log($header_string);
		}
		
		return $header_string;
	}
}
