<?php
//define constants
$_REAL_SCRIPT_DIR = realpath(dirname($_SERVER['SCRIPT_FILENAME'])); // filesystem path of this page's directory (page.php)
$_REAL_BASE_DIR = realpath(dirname(__FILE__)); // filesystem path of this file's directory (config.php)
$_MY_PATH_PART = substr( $_REAL_SCRIPT_DIR, strlen($_REAL_BASE_DIR)); // just the subfolder part between <installation_path> and the page

$INSTALLATION_PATH = $_MY_PATH_PART
    ? substr( dirname($_SERVER['SCRIPT_NAME']), 0, -strlen($_MY_PATH_PART) )
    : dirname($_SERVER['SCRIPT_NAME'])
; // we subtract the subfolder part from the end of <installation_path>, leaving us with just <installation_path> :)

define( 'PATH', $_SERVER['DOCUMENT_ROOT'].$INSTALLATION_PATH.'/');
define( 'INSTALL_FILE', PATH.'install.php');
define( 'FORBIDDEN_SERVICES', PATH.'config/config_forbidden_keywords.txt');
define( 'DEFAULT_SERVICES_TEXT', PATH.'config/default_services_text.txt');
?>