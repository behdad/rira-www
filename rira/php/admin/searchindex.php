#!/usr/bin/php -f
<?php
  $base = dirname (__FILE__)."/..";
  include_once "$base/script/config.php";
  $cfg['db_debug'] = false;
  include_once "$base/script/include.php";
  include_once "$base/backend/sqldb/sqldb.php";

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

  echo $hostname."\n";
  echo $dbname."\n";
  echo $username."\n";
  echo $password."\n";

  $query = "select module_id from module";
  $res = $sqldb->db->query($query);
  if (DB::isError($res)) {
    echo "query failed\n";
    exit;
  }
  while ($module = $res->fetchRow()) {
    $mod = $module['module_id']; 
    $modbase = BASE."module/$mod/";
    echo "module $mod\n";
    $obj = 'home';
    include_once "$modbase$obj.php";
    $class_name = "${mod}__${obj}";
    if (!class_exists($class_name))
      continue;
    $o = &new $class_name;
    do {
      if ($o->searchindexed)
        echo "object $o->me\n";
    } while ($o = $o->create_child());
  }

?>
