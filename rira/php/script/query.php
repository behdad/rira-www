<?php

$input_vars = array ('page', 'mod', 'obj', 'id', 'pageno', 'lim', 'ord', 'q', 'rid', 'onedoc');
foreach ($input_vars as $input_var)
  $$input_var = isset($_REQUEST[$input_var])?$_REQUEST[$input_var]:null;

if (isset($rid)) {
  $rid = (int)$rid;
  $id = 0;
}

if (!isset($q))
  $q = '';
else {
  $q = substr_utf8("$q ", 0, 250);
  $q = str_replace("[\x00-\x1f]*", "", $q);
  if ($q[strlen($q)-1] == " ")
    $q = substr($q, 0, -1);
}

$input_vars[] = 'html_q';
$html_q = htmlspecialchars($q);

if (!isset($onedoc))
  $onedoc = false;

$rppm = $cfg['rows_per_page_max'];
if (!isset($lim) || (int)$lim == 0)
  $lim = $cfg['rows_per_page'];
else if ((int)$lim < 0 || $onedoc)
  $lim = $rppm;
if ($lim > $rppm)
  $lim = $rppm;
unset($rppm);

if (!isset($pageno) || (int)$pageno < 1 || $pageno > 1000000)
  $pageno = 1;

if (isset($ord)) {
  unset($pageno);
  $ord = (int)$ord;
  $ord = $ord < 0 ? 0 : $ord;
}

if (!isset($cfg['locale']))
  $cfg['locale'] = str_replace('-', '_', $cfg['lang']);

ini_set ('display_errors', $cfg['php_debug'] ? 1 : 0);

setlocale(LC_ALL, $cfg['locale']);

?>
