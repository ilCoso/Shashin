<?php
/*
Plugin Name: Shashin (v3 alpha)
Plugin URI: http://www.toppa.com/shashin-wordpress-plugin/
Description: A plugin for integrating Picasa, Twitpic, and Flickr photos in WordPress.
Author: Michael Toppa
Version: 3.0
Author URI: http://www.toppa.com
*/

require_once(dirname(__FILE__) . '/../toppa-libs/ToppaAutoLoaderWp.php');
require_once(dirname(__FILE__) . '/../toppa-libs/ToppaFunctionsFacadeWp.php');
$shashinAutoLoader = new ToppaAutoLoaderWp('/shashin3alpha');
require_once('ShashinWp.php');
$shashin = new ShashinWp($shashinAutoLoader);
$shashin->run();

register_activation_hook(__FILE__, 'shashinActivate');

function shashinActivate() {
    $shashinAutoLoader = new ToppaAutoLoaderWp('/shashin3alpha');
    $shashin = new ShashinWp($shashinAutoLoader);
    $shashin->install();
}
