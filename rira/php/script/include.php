<?php

define('BASE', dirname(__FILE__).'/../');
$custom_path = dirname (__FILE__).'/custom/';
set_include_path($custom_path.PATH_SEPARATOR.get_include_path());

include_once BASE."script/config.php";
$custom_config = "$custom_path/config.php";
if (file_exists ($custom_config))
  include_once $custom_config;

include_once BASE."script/init.php";
include_once BASE."script/tools.php";
include_once BASE."script/persian.php";
include_once BASE."script/link.php";
include_once BASE."script/engine.php";
include_once BASE."script/search.php";
include_once BASE."script/query.php";
include_once BASE."script/rira_objs.php";

$custom_include = "$custom_path/include.php";
if (file_exists ($custom_include))
  include_once $custom_include;

?>
