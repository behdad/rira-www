<?php

class classicpoems__home extends __rira_sqldb_home {
  var $default_title = "شاعران کهن";
  var $child = 'poet';
  var $nocascade = true;
  var $searchable = true;
  var $order_field = '_fame';
}

?>
