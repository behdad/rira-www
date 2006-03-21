<?php

include_once "DB.php";

class sqldb_backend {

  var $has_schema;

  function sqldb_backend ($silent = false) {
    global $cfg;

    $this->debug = $cfg['db_debug'];
    $this->db = &DB::connect($cfg['dsn'], array('debug'=>$this->debug, 'persistent'=>$cfg['db_persistent']));
    if (DB::isError($this->db)) {
      if ($silent)
	return;
      else
        die (DB::errorMessage($this->db->getCode()));
    }
    $this->db->setFetchMode(DB_FETCHMODE_ASSOC);
    $this->db->autoCommit(false);
    $this->has_schema = !DB::isError($this->query("set search_path to public"));

    // postgresql: client encoding
    $this->query("set client_encoding to 'UNICODE'");
    // mysql 4: client encoding
    $this->query("set character set 'utf8'");

    // postgresql: Try to set locale on server, needs pgbe extension installed.
    @$this->freeResult($this->query("select locale ('LC_COLLATE', ".sqlspecialchars($cfg['locale']).")"));


  }

  function query($q) {
    $res = &$this->db->query ($q);

    if ($this->debug) {
      $s = $q;
      if (DB::isError($res)) {
        $s = '<code style="color: red;">'.$s."; --</code>";
        $s .= ' '.DB::errorMessage($res->getCode());
        if (isset($this->db->errorNative))
	  $s .= "<blockquote>-- ".$this->db->errorNative()."</blockquote>\n";
	else
	  $s .= "<br>";
	$s .= "\n";
        $this->rollback ();
      } else {
        $s = '<code style="color: green;">'.$s.'; --</code>';
	$s .= ' <span style="color: blue;">'.($this->db->numRows($res))."</span>";
	$s .= "<br>\n";
      }
      global $log;
      $log .= $s;
    }

    return $res;
  }

  function freeResult($res) {
    return $this->db->freeResult ($res);
  }

  function rollback() {
    return $this->db->rollback();
  }

  function commit() {
    return $this->db->commit();
  }
}

class sqldb_backend_factory {
  function get_sqldb () {
    static $sqldb = NULL;
    if (!$sqldb) {
      $sqldb = new sqldb_backend(true);
      if (DB::isError($sqldb->db)) {
        echo DB::errorMessage($sqldb->db->getCode())."\n";
        $sqldb = NULL;
      }
    }
    return $sqldb;
  }
}

?>
