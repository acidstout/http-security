<?php
/**
 * WP settings page of plugin
 *
 * @author nrekow
 */


/**
 * The actual options page of the plugin.
 */
function http_security_options_page_html() {
	global $httpSecurity;
	global $httpSecurityOptions;
	
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.', HTTP_SECURITY_PLUGIN_NAME));
	}
	
	$active_tab = 'general-options';
	$is_multisite = is_multisite();
	
	$tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general-options';
	switch($tab) {
		case 'general-options':
			$active_tab = 'general-options';
			break;
		case 'csp-options':
			$active_tab = 'csp-options';
			break;
		case 'htaccess':
			$active_tab = 'htaccess';
			break;
		default:
			$active_tab = 'general-options';
			break;
	}?>
	<div class="wrap">
		<h1><?php _e(HTTP_SECURITY_PLUGIN_TITLE . ' Options', HTTP_SECURITY_PLUGIN_NAME);?></h1>
		<p><?php _e('The HTTP protocol provides various header instructions allowing simple improvement of your web site security. As usual, make sure to run full tests on your web site as some options may result in some features stop working.', HTTP_SECURITY_PLUGIN_NAME);?></p>
		<form method="post" action="options.php"><?php
	
			if ($active_tab == 'general-options') {
				
				settings_fields(HTTP_SECURITY_PLUGIN_NAME);
				
				?><h2 class="nav-tab-wrapper">
					<a href="?page=<?php echo HTTP_SECURITY_PLUGIN_NAME;?>&tab=general-options" class="nav-tab nav-tab-active"><?php _e('General options', HTTP_SECURITY_PLUGIN_NAME);?></a><?php 
					if (!$is_multisite) {?>
						<a href="?page=<?php echo HTTP_SECURITY_PLUGIN_NAME;?>&tab=csp-options" class="nav-tab"><?php _e('CSP options', HTTP_SECURITY_PLUGIN_NAME);?></a>
						<a href="?page=<?php echo HTTP_SECURITY_PLUGIN_NAME;?>&tab=htaccess" class="nav-tab"><?php _e('.htaccess', HTTP_SECURITY_PLUGIN_NAME);?></a><?php 
					}?>
				</h2><?php
			
				if (!$httpSecurity->isSecure()) {
					?><p class="http-security-warning"><span class="dashicons dashicons-warning"></span> <strong><?php _e('You are not running HTTPS.', HTTP_SECURITY_PLUGIN_NAME);?></strong></p><?php
				}
				
				foreach ($httpSecurityOptions['general'] as $key => $value) {
					$i = 1;
					if ( ( !$is_multisite && ((in_array($key, array('HSTS', 'Public-Key-Pinning', 'Expect-CT')) && $httpSecurity->isSecure()) || !in_array($key, array('HSTS', 'Public-Key-Pinning', 'Expect-CT'))) )
							|| ($is_multisite && in_array($key, array('HSTS', 'Expect-CT', 'Other options'))) ) {
						?><h3><?php _e($key, HTTP_SECURITY_PLUGIN_NAME);?></h3><?php 
						
						$class = '';
						if (isset($value['type'])) {
							$class = ' class="text"';
							?><table class="http-security-options"><?php 
						}
						
						// Show section label
						if (isset($value['label'])) {?>
							<tr><td<?php echo $class;?>><label for="<?php echo $value['flag'];?>" title="<?php _e($value['description'], HTTP_SECURITY_PLUGIN_NAME);?>"><?php 
								if (!isset($value['type'])) {
									?><input class="flag" name="<?php echo $value['flag'];?>" type="checkbox" id="<?php echo $value['flag'];?>" value="1" <?php echo checked(1, $httpSecurity->getOption($value['flag']), false);?>/><?php
								}
								_e($value['label'], HTTP_SECURITY_PLUGIN_NAME);?>
							</label>
							<?php if (isset($value['extended_description']) && !empty($value['extended_description'])) {
								?><p><?php _e($value['extended_description']);?></p><?php
							}?>
							</td><?php
						}
						
						// Plain checkbox lists and select boxes
						if (isset($value['type'])) {
							switch ($value['type']) {
								case 'list':
									foreach ($value['options'] as $entry) {
										if (isset($entry['type']) && $entry['type'] !== 'checkbox') {
											?></tr><tr><td>
												<table class="nested"><tr><td><label for="<?php echo $entry['id'];?>" title="<?php _e($entry['description'], HTTP_SECURITY_PLUGIN_NAME);?>"><?php _e($entry['label'], HTTP_SECURITY_PLUGIN_NAME); ?></label></td><td><?php
												switch ($entry['type']) {
													case 'text':
														?><input name="<?php echo $entry['id'];?>" type="text" id="<?php echo $entry['id'];?>" value="<?php echo $httpSecurity->getOption($entry['id']);?>"/><?php
														break;
													case 'textarea':
														?><textarea name="<?php echo $entry['id'];?>" id="<?php echo $entry['id'];?>"><?php echo $httpSecurity->getOption($entry['id']);?></textarea><?php
														break;
												}
												?></td></tr></table><?php												
											?></td></tr><?php
										} else {
											?></tr><tr><td><label for="<?php echo $entry['id'];?>" title="<?php _e($entry['description'], HTTP_SECURITY_PLUGIN_NAME);?>"><input name="<?php echo $entry['id'];?>" type="checkbox" id="<?php echo $entry['id'];?>" value="1" <?php echo checked(1, $httpSecurity->getOption($entry['id']), false);?>/><?php _e($entry['label'], HTTP_SECURITY_PLUGIN_NAME);?></label></td></tr><?php
										}
									}
									break;
									
								case 'select':
									?><td><select id="<?php echo $value['id'];?>" name="<?php echo $value['id'];?>"><?php
									foreach ($value['options'] as $entry) {
										?><option value="<?php echo $entry;?>" <?php echo selected($entry, $httpSecurity->getOption($value['id']), false);?>><?php echo $entry;?></option><?php
									}
									?></select></td></tr><?php
									break;
							}
							?></table><br/><?php
						} else {
							// Blockquoted checkboxes, text input fields and radio buttons
							if (!isset($value['extended_description']) || empty($value['extended_description'])) {
								?><br/><?php 
							}?>
							<blockquote id="<?php echo $value['id'];?>_options">
								<table class="http-security-options"><?php 
								foreach ($value['options'] as $entry_key => $entry_value) {
									$input_attr = '';
									$input_value = $httpSecurity->getOption($entry_key);
									$input_pre_label =  __($entry_value['label'], HTTP_SECURITY_PLUGIN_NAME) . ' ';
									$input_post_label = '';
									$input_placeholder = '';
									$input_size = '';
									$input_name = $entry_key;

									if (isset($entry_value['name']) && !empty($entry_value['name'])) {
										$input_name = $entry_value['name'];
									}
									
									if (isset($entry_value['post_label']) && !empty($entry_value['post_label'])) {
										$input_post_label = $entry_value['post_label'];
									}
									
									if (isset($entry_value['placeholder']) && !empty($entry_value['placeholder'])) {
										$input_placeholder = $entry_value['placeholder'];
									}
									
									if (isset($entry_value['size']) && !empty($entry_value['size'])) {
										$input_size = 'size="' . $entry_value['size'] . '"';
									}
									
									if ($entry_value['type'] == 'checkbox') {
										$input_attr = checked(1, $httpSecurity->getOption($entry_key), false);
										$input_value = '1';
										$input_pre_label = '';
										$input_post_label = ' ' . __($entry_value['label'], HTTP_SECURITY_PLUGIN_NAME) . $input_post_label;
									}
									
									if ($entry_value['type'] == 'radio') {
										$input_attr = checked($i, $httpSecurity->getOption($input_name), false);
										$input_value = $i;
										$input_pre_label = '';
										$input_post_label = ' ' . __($entry_value['label'], HTTP_SECURITY_PLUGIN_NAME);
									}
									?><tr><?php
									$colspan = '';
									$class = '';
									switch ($entry_value['type']) {
										case 'textarea':
											?><td class="textarea"><label for="<?php echo $entry_key;?>" title="<?php _e($entry_value['description'], HTTP_SECURITY_PLUGIN_NAME);?>"><?php echo $input_pre_label;?></label></td><td><textarea id="<?php echo $entry_key;?>" name="<?php echo $input_name;?>"><?php echo $input_value;?></textarea><?php
											break;
										case 'age':
											$class=' class="age"';
											$entry_value['type'] = 'text';
										case 'text':
											?><td class="text"><label for="<?php echo $entry_key;?>" title="<?php _e($entry_value['description'], HTTP_SECURITY_PLUGIN_NAME);?>"><?php echo $input_pre_label;?></label></td><td><input<?php echo $class;?> name="<?php echo $input_name;?>" id="<?php echo $entry_key;?>" type="<?php echo $entry_value['type'];?>" value="<?php echo $input_value;?>" placeholder="<?php echo $input_placeholder;?>" <?php echo $input_size. ' ' . $input_attr;?>/><?php echo $input_post_label;?><?php
											break;
										case 'checkbox':
											$colspan = ' colspan="2"';
										default:
											?><td<?php echo $colspan;?>><label for="<?php echo $entry_key;?>" title="<?php _e($entry_value['description'], HTTP_SECURITY_PLUGIN_NAME);?>"><?php echo $input_pre_label;?><input name="<?php echo $input_name;?>" id="<?php echo $entry_key;?>" type="<?php echo $entry_value['type'];?>" value="<?php echo $input_value;?>" placeholder="<?php echo $input_placeholder;?>" <?php echo $input_size. ' ' . $input_attr;?>/><?php echo $input_post_label;?></label><?php
											if ($colspan == '') {
												?></td><td><?php
											}
											break;
									}
									?></td></tr><?php
									$i++;
									
								}?>
								</table>
							</blockquote><?php
						}
					}
				}
			}
		
			if ($active_tab == 'csp-options') {
		
				settings_fields(HTTP_SECURITY_PLUGIN_NAME . '-csp');
				
				?><h2 class="nav-tab-wrapper"><a href="?page=<?php echo HTTP_SECURITY_PLUGIN_NAME;?>&tab=general-options" class="nav-tab"><?php _e('General options', HTTP_SECURITY_PLUGIN_NAME);?></a> <a href="?page=<?php echo HTTP_SECURITY_PLUGIN_NAME;?>&tab=csp-options" class="nav-tab nav-tab-active"><?php _e('CSP options', HTTP_SECURITY_PLUGIN_NAME);?></a> <a href="?page=<?php echo HTTP_SECURITY_PLUGIN_NAME;?>&tab=htaccess" class="nav-tab"><?php _e('.htaccess', HTTP_SECURITY_PLUGIN_NAME);?></a></h2>
				<p><?php _e('For a complete description of these parameters, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy" target="_blank" rel="noopener">Content-Security-Policy</a> on the Mozilla Developer Network.', HTTP_SECURITY_PLUGIN_NAME);?></p><?php 
				
				foreach ($httpSecurityOptions['csp']['flags'] as $entry) {
					?><label for="<?php echo $entry['id']?>" title="<?php _e($entry['description'],HTTP_SECURITY_PLUGIN_NAME);?>"><input class="flag" name="<?php echo $entry['id']?>" type="checkbox" id="<?php echo $entry['id']?>" value="1" <?php echo checked(1, $httpSecurity->getOption($entry['id']), false);?>/><?php _e($entry['label'], HTTP_SECURITY_PLUGIN_NAME);?></label> <?php
				}
				
				?><blockquote id="http_security_csp_options">
					<table class="http-security-options"><?php 
					foreach ($httpSecurityOptions['csp']['directives'] as $key => $value) {
							?><tr><td colspan="2"><h3><?php _e(ucfirst($key) . ' directives', HTTP_SECURITY_PLUGIN_NAME);?></h3></td></tr><?php
							foreach ($value as $csp_directive) {
								$input_type = 'text';
								$input_value = $httpSecurity->getOption($csp_directive['id']);
								$input_attr = '';
								
								if (isset($csp_directive['type']) && !empty($csp_directive['type'])) {
									$input_type = $csp_directive['type'];
									$input_value = 1;
									$input_attr = checked(1, $httpSecurity->getOption($csp_directive['id']), false);
								}
								
								?><tr class="http_security_csp_options" title="<?php _e($csp_directive['description'], HTTP_SECURITY_PLUGIN_NAME);?>">
									<td><label for="<?php echo $csp_directive['id'];?>"><?php _e($csp_directive['label'], HTTP_SECURITY_PLUGIN_NAME);?></label></td>
									<td><input name="<?php echo $csp_directive['id'];?>" id="<?php echo $csp_directive['id'];?>" type="<?php echo $input_type;?>" value="<?php echo $input_value;?>" <?php echo $input_attr;?> size="80"/></td>
								</tr><?php
							}
						}?>
					</table>
				</blockquote><?php
			}
		
			if ($active_tab == 'htaccess') {
			
				settings_fields(HTTP_SECURITY_PLUGIN_NAME . '-htaccess');
				
				// Get status of htaccess flag and enable/disable textarea according to that status.
				$checked = checked(1, $httpSecurity->getOption('http_security_htaccess_flag'), false);
				$disabled = '';
				
				if (empty($checked)) {
					$disabled = 'disabled';
				}
				
				$header_string = '# ' . HTTP_SECURITY_PLUGIN_TITLE . ' settings start' . "\n";
				
				
				// HSTS
				if ($httpSecurity->getOption('http_security_sts_flag')) {
					$header_string .= 'Header set Strict-Transport-Security: "';
					
					if ($httpSecurity->getOption('http_security_sts_max_age')) {
						$header_string .= 'max-age=' . $httpSecurity->getOption('http_security_sts_max_age') . ';';
					}
					
					if ($httpSecurity->getOption('http_security_sts_subdomains_flag')) {
						$header_string .= ' includeSubDomains;';
					}
					
					if ($httpSecurity->getOption('http_security_sts_preload_flag')) {
						$header_string .= ' preload;';
					}
					
					$header_string = $httpSecurity->removeTrail($header_string, ';');
					$header_string .= '"' . "\n";
				}
				
				
				// Public-Key-Pinning
				if ($httpSecurity->getOption('http_security_pkp_flag')) {
					if ($httpSecurity->getOption('http_security_pkp_keys')) {
						$header_string .= 'Header always set Public-Key-Pins';
					
						if ($httpSecurity->getOption('http_security_pkp_reportonly_flag')) {
							$header_string .= '-Report-Only';
						}
					
						$header_string .= ': "';
					
						$header_string .= $httpSecurity->getOption('http_security_pkp_keys');
						
						if ($httpSecurity->getOption('http_security_pkp_maxage')) {
							$header_string .= ' max-age=' . $httpSecurity->getOption('http_security_pkp_maxage') . ';';
						}
						if ($httpSecurity->getOption('http_security_pkp_subdomains_flag')) {
							$header_string .= ' includeSubDomains;';
						}
						
						if ($httpSecurity->getOption('http_security_pkp_reporturi')) {
							$header_string .= ' report-uri=\"' . $httpSecurity->getOption('http_security_pkp_reporturi') . '\";';
						}
						
						$header_string = $httpSecurity->removeTrail($header_string, ';');
						$header_string .= '"' . "\n";
					}
				}
				
				
				// Expect-CT
				if ($httpSecurity->getOption('http_security_expect_ct_flag')) {
					$header_string .= 'Header set Expect-CT: "';
					
					if ($httpSecurity->getOption('http_security_expect_ct_enforce_flag')) {
						$header_string .= 'enforce; ';
					}
					
					$header_string .= ($httpSecurity->getOption('http_security_expect_ct_max_age'))		? 'max-age=' . $httpSecurity->getOption('http_security_expect_ct_max_age') . '; ' : null;
					$header_string .= ($httpSecurity->getOption('http_security_expect_ct_report_uri'))	? 'report-uri="' . $httpSecurity->getOption('http_security_expect_ct_report_uri') . '";' : null;
					
					$header_string = $httpSecurity->removeTrail($header_string, ';');
					$header_string .= '"' . "\n";
				}
				
				
				// Content Security Policy
				if ($httpSecurity->getOption('http_security_csp_flag')) {
					$header_string .= 'Header set Content-Security-Policy';
					
					if ($httpSecurity->getOption('http_security_csp_reportonly_flag')) {
						$header_string .= '-Report-Only';
					}
					
					$header_string .= ': "';

					// Evaluate options.
					$header_string .= $httpSecurity->getCSPDirectives();

					$header_string = $httpSecurity->removeTrail($header_string, ';');
					$header_string .= '"' . "\n";
				}
				
				// Feature-Policy
				if ($httpSecurity->getOption('http_security_feature_policy_flag')) {
					$feature_string = 'Feature-Policy: "';
					
					foreach ($httpSecurityOptions['general']['Feature-Policy']['options'] as $key => $values) {
						if (strpos($key, 'http_security_feature_policy_') !== false) {
							$value = trim($httpSecurity->getOption($key));
							if (!empty($value)) {
								$directive = substr($key, 29);
								$directive = str_replace('_', '-', $directive);
								$feature_string .= $directive . ' ' . $value . '; ';
							}
						}
					}
					
					$feature_string = $httpSecurity->removeTrail($feature_string, '; ');
					
					if ($feature_string !== 'Feature-Policy: "') {
						$header_string .= 'Header set ' . $feature_string . '"' . "\n";
					}
				}
				
				// X-Frame-Options
				if ($httpSecurity->getOption('http_security_x_frame_flag')) {
					switch ($httpSecurity->getOption('http_security_x_frame_options')) {
						case 1:
							$header_string .= 'Header set X-Frame-Options: DENY' . "\n";
							break;
						case 2:
							$header_string .= 'Header set X-Frame-Options: SAMEORIGIN' . "\n";
							break;
						case 3:
							$header_string .= 'Header set X-Frame-Options: ALLOW-FROM ' . $httpSecurity->getOption('http_security_x_frame_origin') . "\n";
							break;
					}
				}
				
				
				// Referrer
				if ($httpSecurity->getOption('http_security_referrer_policy')) {
					$header_string .= 'Header set Referrer-Policy: ' . $httpSecurity->getOption('http_security_referrer_policy') . "\n";
				}
				
				
				// XSS Protection
				if ($httpSecurity->getOption('http_security_x_xss_protection')) {
					$header_string .= 'Header set X-XSS-Protection: "1; mode=block"' . "\n";
				}
				
				
				// X-Content-Type-Options
				if ($httpSecurity->getOption('http_security_x_content_type_options')) {
					$header_string .= 'Header set X-Content-Type-Options: nosniff' . "\n";
				}
				
				$header_string .= '# ' . HTTP_SECURITY_PLUGIN_TITLE . ' settings end';
				
				?><h2 class="nav-tab-wrapper"><a href="?page=<?php echo HTTP_SECURITY_PLUGIN_NAME;?>&tab=general-options" class="nav-tab"><?php _e('General options', HTTP_SECURITY_PLUGIN_NAME);?></a> <a href="?page=<?php echo HTTP_SECURITY_PLUGIN_NAME;?>&tab=csp-options" class="nav-tab"><?php _e('CSP options', HTTP_SECURITY_PLUGIN_NAME);?></a> <a href="?page=<?php echo HTTP_SECURITY_PLUGIN_NAME;?>&tab=htaccess" class="nav-tab nav-tab-active"><?php _e('.htaccess', HTTP_SECURITY_PLUGIN_NAME);?></a></h2>
				<p><?php _e('Some cache plug-ins (e.g. <a href="https://wordpress.org/plugins/cache-enabler/">Cache Enabler</a>) rewrite the HTTP headers. In this case, you may need to have to insert the following content in your .htaccess file. If so, please disable the rewriting of the HTTP headers.', HTTP_SECURITY_PLUGIN_NAME);?></p>
				<p><?php _e('Make sure to save the settings for the latest version.', HTTP_SECURITY_PLUGIN_NAME);?></p>
				<label for="<?php echo $httpSecurityOptions['htaccess']['id'];?>" title="<?php _e($httpSecurityOptions['htaccess']['description'], HTTP_SECURITY_PLUGIN_NAME);?>"><input name="<?php echo $httpSecurityOptions['htaccess']['id'];?>" type="checkbox" id="<?php echo $httpSecurityOptions['htaccess']['id'];?>" value="1" <?php echo $checked;?>/><?php _e($httpSecurityOptions['htaccess']['label'], HTTP_SECURITY_PLUGIN_NAME);?></label><br/>
				<blockquote><textarea name="htaccess" id="htaccess" rows="15" cols="80" readonly <?php echo $disabled;?>><?php echo $header_string;?></textarea></blockquote><?php
			}
		
			submit_button();?>
		</form><?php
	
		if (username_exists('admin')) {
			?><p class="http-security-warning"><span class="dashicons dashicons-warning"></span> <?php _e('You still have an administrator account with the user name security <strong>admin</strong>. This is a major security flaw, you should consider renaming this account.', HTTP_SECURITY_PLUGIN_NAME);?></p><?php
		}?>
	</div>
	<link type="text/css" rel="stylesheet" href="<?php echo HTTP_SECURITY_PLUGIN_DIR;?>/css/style<?php echo (defined('WP_DEBUG') && WP_DEBUG === true) ? '' : '.min';?>.css?ver=<?php echo time();?>"/>
	<script type="text/javascript" src="<?php echo HTTP_SECURITY_PLUGIN_DIR;?>/js/options<?php echo (defined('WP_DEBUG') && WP_DEBUG === true) ? '' : '.min';?>.js?ver=<?php echo time();?>"></script><?php
}
