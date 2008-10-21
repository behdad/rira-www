#!/usr/bin/php -f
<?php

$base = dirname (__FILE__)."/../../..";

global $base;
include_once "$base/script/config.php";
include_once "$base/script/include.php";
include_once "$base/backend/luceneindex/luceneindex.php";

function &init_index()
{
  global $base;

  $luceneindex = luceneindex_backend_factory::get_luceneindex (false);
  if (!$luceneindex) {
    echo "creating index failed\n";
    exit;
  }

  return $luceneindex;
}

$luceneindex = init_index();

$hits = $luceneindex->query("صبح");
foreach ($hits as $hit) {
  $idn = $hit->idn;
  $text = $hit->contents;
  echo "$idn	$text\n";
}

?>
