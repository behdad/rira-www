<?php
  include_once "script/include.php";

  get_page();

  {
    $s = '';
    foreach (array ('css', 'js') as $what)
      foreach (array ('site', 'module') as $where)
        foreach (array ('main', $page) as $which)
	{
          $f = "${where}_${what}";
          $s .= $f($which);
        }
    $s .= www_js("http://behdad.org/js/isiri2901.js");
    $header = "$s$header";
  }

  $onload .= $o->get_onload();

  if (!ini_get("zlib.output_compression") && ini_get("output_handler") != "ob_gzhandler")
    ob_start("ob_gzhandler");    

  include 'template/main.php';
?>
