<?php
  require_once "script/include.php";

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
    $s .= www_css("css.php?mod=$mod&bg=".@$modules[$mod]['bg_color']);
    #$s .= www_js("http://behdad.org/js/isiri2901.js");
    $s .= www_js("style/isiri2901.js");
    $header = "$s$header";
  }

  $onload .= $o->get_onload();

  include 'template/main.php';
?>
