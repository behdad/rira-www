#!/usr/bin/php -f
<?php
  include_once "script/config.php";
  $cfg['db_debug'] = false;
  include_once "script/include.php";

  if (!empty($SERVER_ADDR))
    error('accessdenied');

  preg_match("|.*//([^:@]*):?([^@]*)@(.*)|", $cfg['dsn'], $p);
  echo $p[3]."\n"; // hostname/db
  echo $p[1]."\n"; // user
  echo $p[2]."\n"; // password

  $query = "select module_id from module";
  $res = $db->query($query);
  if (DB::isError($res))
    exit;
  while ($module = $res->fetchRow()) {
    $mod = $module['module_id']; 
    $modbase = BASE."module/$mod/";
    if (@!schemadb($mod))
      continue;
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
