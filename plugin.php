<?php

/**
 * @wordpress-plugin
 * Plugin Name:       GeoRedirect
 * Plugin URI:        
 * Description:       GeoIp Redirect
 * Version:           0.1.0
 * Author:            Leon Harvey
 * Author URI:        http://fusio.net
 * License:           MIT
 */

if (function_exists('session_status')) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
} else {
    if(session_id() == '') {
        session_start();
    }
}

$start = microtime(true);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/getherbert/framework/bootstrap/autoload.php';


$overhead = microtime(true) - $start;
