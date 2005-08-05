<?php

include_once "DB.php";

class sqldb_backend {

  var $db = null;

  function sqldb_backend () {
    global $cfg;

    $this->db = &DB::connect($cfg['dsn'], array('debug'=>$cfg['db_debug'], 'persistent'=>$cfg['db_persistent']));
    if (DB::isError($this->db)) {
	die ($this->db->getMessage());
    }
    $this->db->setFetchMode(DB_FETCHMODE_ASSOC);
    $this->db->autoCommit(false);
    @$this->db->freeResult($this->db->query("set client_encoding to 'UNICODE'"));
    // Try to set locale on server, needs pgbe extension installed.
    @$this->db->freeResult($this->db->query("select locale ('LC_COLLATE', ".$cfg['locale'].")"));
  }

  function set_schema ($s) {
    $res = $this->db->query("set search_path to $s");
    $ret = !DB::isError($res);
    @$this->db->freeResult($res);
    return $ret;
  }

  function mes($res) {
    global $cfg;
    if (DB::isError($res)) {
      if ($cfg['db_debug'] && isset($this->db)) {
	echo "<strong>" . $this->db->errorNative() . "</strong><br/>\n";
      }
      rollbackDB();
      return 'dberror'.$res->getCode()." ";
    }
    return false;
  }

  function diedb($res) {
    if (DB::isError($res)) {
      error($this->mes($res));
    }
    return false;
  }

  function rollbackdb() {
    if (isset($this->db)) {
      $rb = $this->db->rollback();
      if (DB::isError($rb)) {
	  echo ($rb->getMessage($rb->getCode()));
      }
    }
  }
}

$sqldb = new sqldb_backend();

?>
