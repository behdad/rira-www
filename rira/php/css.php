<?php

define('BASE', dirname(__FILE__).'/');

$input_vars = array ('mod', 'bg');
foreach ($input_vars as $input_var)
  $$input_var = isset($_REQUEST[$input_var])?$_REQUEST[$input_var]:null;

if (!isset($mod) || !$mod || !preg_match('/^[a-z_]+$/', $mod) || !file_exists(BASE."module/$mod"))
  $mod = 'public';

if (is_string ($bg) && strlen ($bg) == 3) {
  $bg = $bg[0].$bg[0].$bg[1].$bg[1].$bg[2].$bg[2];
}

if (!is_string ($bg) || strlen ($bg) != 6)
  unset ($bg);


$moddir = "module/$mod/";
$modbase = BASE.$moddir;

header ("Content-Type: text/css");
include 'template/background.css.php';

?>
