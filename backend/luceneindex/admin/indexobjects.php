#!/usr/bin/php -f
<?php

$base = dirname (__FILE__)."/../../..";

global $base;
include_once "$base/script/config.php";
include_once "$base/script/include.php";
include_once "$base/backend/sqldb/sqldb.php";
include_once "$base/backend/luceneindex/luceneindex.php";

function &init_db()
{
  global $SERVER_ADDR, $cfg;
  if (!empty($SERVER_ADDR))
    error('accessdenied');

  preg_match("|.*//([^:@]*):?([^@]*)@([^/]*)/(.*)|", $cfg['dsn'], $p);
  $dbname = $p[4];
  $hostname = $p[3];
  $username = $p[1];
  $password = $p[2];


  $sqldb = sqldb_backend_factory::get_sqldb ();
  if (!$sqldb) {
    # not connected.

    # if password is not set, assume ident authentication
    if ($password === '') {
      echo "password is empty.\n".
           "you may want to run this script as user $username.\n";
    }
  }
  if (!$sqldb) {
    echo "connection failed\n";
    exit;
  }

  return $sqldb;
}

function finish_db($sqldb)
{
}

function &init_index()
{
  global $base;

  $luceneindex = luceneindex_backend_factory::get_luceneindex (true);
  if (!$luceneindex) {
    echo "creating index failed\n";
    exit;
  }

  $luceneindex->index->setMaxBufferedDocs (1000);

  return $luceneindex;
}

function finish_index($luceneindex)
{
  echo "optimizing index\n";
  $luceneindex->optimize();
  $luceneindex->commit();
}

function index_object ($luceneindex, $sqldb, $mod, $obj)
{
  $query = "select idn, text from ".$obj."_index";
  $res = $sqldb->query($query);
  if (DB::isError($res)) {
    echo "query failed on object $obj, skipping\n";
    echo "failed query: $query\n";
    return;
  }

  $i = 0;
  while ($row = $res->fetchRow()) {
    $idn = "$mod ".$row['idn']; 
    $luceneindex->add_document($idn, $row['text']);

    if (++$i % 100 == 0) {
      echo $i;
    }
    echo '.';
  }
  echo "\n";

  $sqldb->freeresult($res);
}

function index_module ($luceneindex, $sqldb, $mod)
{
  $modbase = BASE."module/$mod/";

  echo "module $mod\n";
  $query = "set search_path to $mod, public";
  $modres = $sqldb->query($query);
  if (DB::isError($modres)) {
    echo "query failed on module $mod, skipping module\n";
    continue;
  }
  $sqldb->freeresult($modres);

  $obj = 'home';
  include_once "$modbase$obj.php";
  $class_name = "${mod}__${obj}";
  if (!class_exists($class_name))
    continue;
  $o = new $class_name;
  do {
    if ($o->searchindexed) {
      echo "object $o->me\n";
	index_object($luceneindex, $sqldb, $mod, $o->me);
    }
  } while ($o = $o->create_child());
}

function index_db ($luceneindex, $sqldb)
{
  $query = "select module_id from module";
  $res = $sqldb->query($query);
  if (DB::isError($res)) {
    echo "query failed\n";
    exit;
  }
  while ($module = $res->fetchRow()) {
    $mod = $module['module_id']; 

    index_module ($luceneindex, $sqldb, $mod);
  }
  $sqldb->freeresult($res);
}

ini_set ('memory_limit', -1);

$sqldb = init_db();
$luceneindex = init_index();

index_db ($luceneindex, $sqldb);

finish_index($luceneindex);
finish_db($sqldb);

echo "done\n";

?>
