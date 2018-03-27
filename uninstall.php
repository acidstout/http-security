<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
unregister_setting( 'http-security', 'http_security_remove_php_version' );
unregister_setting( 'http-security', 'http_security_remove_wordpress_version' );
unregister_setting( 'http-security', 'http_security_sts_flag' );
unregister_setting( 'http-security', 'http_security_sts_subdomains_flag' );
unregister_setting( 'http-security', 'http_security_sts_preload_flag' );
unregister_setting( 'http-security', 'http_security_sts_max_age' );
unregister_setting( 'http-security', 'http_security_expect_ct_flag' );
unregister_setting( 'http-security', 'http_security_expect_ct_max_age' );
unregister_setting( 'http-security', 'http_security_expect_ct_enforce_flag' );
unregister_setting( 'http-security', 'http_security_expect_ct_report_uri' );
unregister_setting( 'http-security', 'http_security_pkp_flag' );
unregister_setting( 'http-security', 'http_security_pkp_keys' );
unregister_setting( 'http-security', 'http_security_pkp_subdomains_flag' );
unregister_setting( 'http-security', 'http_security_pkp_reportonly_flag' );
unregister_setting( 'http-security', 'http_security_reeferrer-policy' );
unregister_setting( 'http-security', 'http_security_x_frame_flag' );
unregister_setting( 'http-security', 'http_security_x_frame_options' );
unregister_setting( 'http-security', 'http_security_x_frame_origin' );
unregister_setting( 'http-security', 'http_security_x_xss_protection' );
unregister_setting( 'http-security', 'http_security_x_content_type_options' );
unregister_setting( 'http-security-csp', 'http_security_csp_flag' );
unregister_setting( 'http-security-csp', 'http_security_csp_reportonly_flag' );
unregister_setting( 'http-security-csp', 'http_security_csp_child' );
unregister_setting( 'http-security-csp', 'http_security_csp_connect' );
unregister_setting( 'http-security-csp', 'http_security_csp_default' );
unregister_setting( 'http-security-csp', 'http_security_csp_font' );
unregister_setting( 'http-security-csp', 'http_security_csp_frame' );
unregister_setting( 'http-security-csp', 'http_security_csp_img' );
unregister_setting( 'http-security-csp', 'http_security_csp_manifest' );
unregister_setting( 'http-security-csp', 'http_security_csp_media' );
unregister_setting( 'http-security-csp', 'http_security_csp_object' );
unregister_setting( 'http-security-csp', 'http_security_csp_script' );
unregister_setting( 'http-security-csp', 'http_security_csp_style' );
unregister_setting( 'http-security-csp', 'http_security_csp_worker' );
unregister_setting( 'http-security-csp', 'http_security_csp_base_uri' );
unregister_setting( 'http-security-csp', 'http_security_csp_plugin_types' );
unregister_setting( 'http-security-csp', 'http_security_csp_sandbox' );
unregister_setting( 'http-security-csp', 'http_security_csp_form_action' );
unregister_setting( 'http-security-csp', 'http_security_csp_frame_ancestors' );
unregister_setting( 'http-security-csp', 'http_security_csp_block_all_mixed_content' );
unregister_setting( 'http-security-csp', 'http_security_csp_require_sri_for' );
unregister_setting( 'http-security-csp', 'http_security_csp_upgrade_insecure_requests' );