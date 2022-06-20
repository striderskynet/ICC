<?php
define("_LOCAL", $_SERVER['DOCUMENT_ROOT'] . "/");

define("_VERSION", "0.1.1-alpha");
define("_COMMIT", "2022-06-08T04:51:59Z");
define("_DEBUG", true);


$config['title'] = "Endirecto";
$_ADDRESS = "http://192.168.10.2:85/";
$_INSTALLED = true;


$config['db']['host'] = "localhost";
$config['db']['user'] = "id19135990_os_nesty";
$config['db']['pass'] = ">%_g/2I6\d\!wiw/";
$config['db']['data'] = "id19135990_iccs";

$config['misc']['pagination'] = 10;


// DONT TOUCH BELOW THIS LINE
require_once(_LOCAL . "core/misc.php");
require_once(_LOCAL . "core/debug.php");
require_once(_LOCAL . "core/class/mysql.php");
