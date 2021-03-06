<?php

define('BASE', realpath (dirname (__FILE__).'/../').'/');
define('custom', 'script/custom');
$script_path = realpath (dirname (__FILE__));
$custom_path = "$script_path/custom/";
set_include_path($custom_path.PATH_SEPARATOR.$script_path.PATH_SEPARATOR.get_include_path());

require_once BASE."script/config.php";
require_once BASE."script/init.php";
require_once BASE."script/tools.php";
require_once BASE."script/persian.php";
require_once BASE."script/link.php";
require_once BASE."script/engine.php";
require_once BASE."script/search.php";
require_once BASE."script/query.php";
require_once BASE."script/rira_objs.php";

@include "custom/include.php";
?>
