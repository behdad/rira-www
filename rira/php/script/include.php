<?php

define('BASE', dirname(__FILE__).'/../');
$custom_path = dirname (__FILE__).'/custom/';
set_include_path($custom_path.PATH_SEPARATOR.get_include_path());
function include_custom ($file) {
  global $custom_path;
  $custom_file = "$custom_path/$file";
  if (file_exists ($custom_file))
    include_once $custom_file;
}

include_once BASE."script/config.php";
include_once BASE."script/init.php";
include_once BASE."script/tools.php";
include_once BASE."script/persian.php";
include_once BASE."script/link.php";
include_once BASE."script/engine.php";
include_once BASE."script/search.php";
include_once BASE."script/query.php";
include_once BASE."script/rira_objs.php";

include_custom ("include.php");
?>
