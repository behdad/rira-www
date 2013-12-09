<?php

if (!isset($cfg['locale']))
  $cfg['locale'] = str_replace('-', '_', $cfg['lang']);

// We like to do E_STRICT here, but DB.php is not clean
error_reporting ($cfg['debug'] ? E_ALL & ~E_STRICT : 0);
ini_set ('display_errors', $cfg['php_debug'] ? 1 : 0);

setlocale(LC_ALL, $cfg['locale']);

/* compress body */
if (!$cfg["debug"] && !ini_get("zlib.output_compression") && ini_get("output_handler") != "ob_gzhandler")
  ob_start("ob_gzhandler");

?>
