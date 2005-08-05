<?php

$log = '';

function substr_utf8 ($str, $start, $length = '') {
  $utf8char = '(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]*)';
  $pat = "/^$utf8char{0,$start}($utf8char{0,$length})/";
  preg_match ($pat, $str, $match);
  return $match[1];
}

function myempty($x) {
  return (!isset($x)) || ($x === '') || ($x === false);
}

function error($s) {
  global $log, $cfg;
  if ($cfg['debug'])
    echo $log;
  die($s);
}

function sqlspecialchars($s) {
  $s = str_replace("'", "''", $s);
  return myempty($s) ? "NULL" : "'$s'";
}

function short_title($s, $len_factor = -1) {
  global $cfg;
  if ($len_factor < 0)
    $len = (int)((-$len_factor) * $cfg['short_title_len']);
  else
    $len = $len_factor;
  $sub = substr_utf8($s, 0, $len);
  if ($sub != $s)
    $sub .= $cfg['ellipsis'];
  return $sub;
}

function site_js ($s) {
  $rel = "script/js/$s.js";
  if (file_exists(BASE.$rel))
    return "  <script type=\"text/javascript\" src=\"$rel\" defer=\"defer\"></script>\n";
  return '';
}

function site_css ($s) {
  $rel = "style/$s.css";
  if (file_exists(BASE.$rel))
    return "  <link rel=\"stylesheet\" href=\"$rel\"/>\n";
  return '';
}

function module_js ($s) {
  global $moddir, $modbase;
  $rel = "script/js/$s.js";
  if (file_exists($modbase.$rel))
    return "  <script type=\"text/javascript\" src=\"$moddir$rel\" defer=\"defer\"></script>\n";
  return '';
}

function module_css ($s) {
  global $moddir, $modbase;
  $rel = "style/$s.css";
  if (file_exists($modbase.$rel))
    return "  <link rel=\"stylesheet\" href=\"$moddir$rel\"/>\n";
  return '';
}

function www_js ($s) {
  if (!empty($s))
    return "  <script type=\"text/javascript\" src=\"$s\" defer=\"defer\"></script>\n";
  return '';
}

function www_css ($s) {
  if (!empty($s))
    return "  <link rel=\"stylesheet\" href=\"$s\"/>\n";
  return '';
}

?>
