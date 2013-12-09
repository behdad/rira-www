#!/usr/bin/php -f
<?php
  $base = dirname (__FILE__);
  include_once "$base/../script/config.php";
  include_once "$base/../script/include.php";
  include_once "$base/../backend/sqldb/sqldb.php";

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
      echo "password is empty.  you may want to run this script as user $username.\n";
    }
  }
  if (!$sqldb) {
    echo "connection failed\n";
    exit;
  }

function dump_table ($to, $from) {
  global $sqldb, $log;
  echo "creating dump table for $from\n";
  $query = "select * into dump.$to from $from";
  $res = $sqldb->query($query);
  if (DB::isError($res)) {
    echo "failed creating dump table for $from\n";
  }
  $res = $sqldb->commit();
}

  $query = "drop schema dump cascade";
  $res = $sqldb->query($query);
  $res = $sqldb->commit();
  $query = "create schema dump";
  $res = $sqldb->query($query);
  $res = $sqldb->commit();

  dump_table ('public__module_header', 'public.module_header');

  $query = "select module_id from module";
  $res = $sqldb->query($query);
  if (DB::isError($res)) {
    echo "query failed\n";
    echo $log;
    exit;
  }
  while ($module = $res->fetchRow()) {
    $mod = $module['module_id']; 
    $modbase = BASE."module/$mod/";
    $obj = 'home';
    include_once "$modbase$obj.php";
    $class_name = "${mod}__${obj}";
    if (!class_exists($class_name))
      continue;
    $o = new $class_name;
    while ($o = $o->create_child()) {
      if ($o->searchindexed) {
        $obj = $o->me;
        $view_name = "${mod}__${obj}";
        $table_name = "${mod}.${obj}";
	$view = '_header';
        dump_table ($view_name.$view, $table_name.$view);
	$view = '_contents';
        dump_table ($view_name.$view, $table_name.$view);
      }
    }
  }

  sleep (2);
  echo "Dumping...\n";

  `pg_dump --inserts --clean --no-privileges --no-owner --schema dump --host "$hostname" --username "$username" $dbname | grep -v '^SET' | grep -v 'SCHEMA' | sed 's/dump[.]//g' | bzip2 --best > rira-web.mysql.bz2`;

  $query = "drop schema dump cascade";
  $res = $sqldb->query($query);
  $res = $sqldb->commit();
?>
