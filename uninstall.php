<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

require_once 'http-security.php';

foreach ($httpSecurity->getRegisterOptions() as $key => $value) {
	foreach ($value as $entry) {
		delete_option( $entry );
	}
}
