<?php

class public__home extends __rira_sqldb_home {
  var $default_title = "ری‌را";
  var $default_long_title = "ری‌را &#8212; کتاب‌خانه‌ی آزادِ فارسی";
  var $child = 'module';
  var $searchable = true;
  var $parent = false;

  function get_idn_query ($leaf_obj = '') {
    return '';
  }
}

?>
