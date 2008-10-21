<?php

include_once BASE.'backend/sqldb/sqldb.php';

class __rira_sqldb_obj extends __rira_obj {

  function __rira_sqldb_obj () {
    $this->db = sqldb_backend_factory::get_sqldb();
    parent::__rira_obj();
  }

  function get_table ($table = '', $obj = false, $mod = false) {
    if (!$mod)
      $mod = $this->module;
    if (!$obj)
      $obj = $this->me;
    $table = ($table == '' || $table[0] == '_') ? $obj.$table : $table;
    return $this->db->has_schema ? $mod.".".$table : $mod."__".$table;
  }

  function get_id_by_ord ($ord, $p_id = 0) {
    $me = &$this->me;
    $h = &$this->get_header_data();
    $query = "select * from ".$this->get_table('_header')." where ${me}_ord=".sqlspecialchars($ord);
    if (isset($this->parent)) {
      if (empty($p_id) && isset($h[$this->parent."_id"]))
        $p_id = $h[$this->parent."_id"];
      if (!empty($p_id))
        $query .= " and $this->parent"."_id=".sqlspecialchars($p_id);
    }
    $query .= " limit 1";
    $res = $this->db->query($query);
    if (DB::isError($res) || $res->numRows() < 1)
      return false;
    $row = &$res->fetchRow();
    $this->db->freeResult($res);
    return $row["${me}_id"];
  }

  function get_children_count ($p_id = 0) {
    $me = &$this->me;
    $h = &$this->get_header_data();
    $query = "select count(1) as count from ".$this->get_table();
    $moreq = '';
    if (isset($this->parent)) {
      if (empty($p_id) && isset($h[$this->parent."_id"]))
        $p_id = $h[$this->parent."_id"];
      if (!empty($p_id))
        $moreq .= " where $this->parent"."_id=".sqlspecialchars($p_id);
    }
    $res = &$this->db->query("$query $moreq");
    if (DB::isError($res)) {
      $query = "select count(1) as count from ".$this->get_table('_header');
      $res = &$this->db->query("$query $moreq");
    }
    if (DB::isError($res))
      return 0;
    $row = &$res->fetchRow();
    $this->db->freeResult($res);
    return $row['count'];
  }

  function &get_header_data ($q = '') {
    global $obj, $id;
    if (isset($this->header))
      return $this->header;
    $me = &$this->me;
    if (empty($q)) {
      $query = "select * from ".$this->get_table('_header', $obj);
      if ($id)
	$query .= " where ${obj}_id=".sqlspecialchars($id);
    } else
      $query = $q;
    $moreq = "limit 1";
    $res = $this->db->query("$query $moreq");
    if (DB::isError($res)) {
      if (empty($q)) {
        $query = "select * from ".$this->get_table('', $obj);
        if ($id)
	  $query .= " where ${obj}_id=".sqlspecialchars($id);
        $res = &$this->db->query("$query $moreq");
      }
      if (DB::isError($res)) {
        $this->header = array();
	return $this->header;
      }
    }
    if ($res->numRows() < 1)
    {
      if ($this->db->debug) {
        global $log;
        $log .= "Expected non-empty result set.<br>\n";
      }
      error("notfound1");
    }
    $row = &$res->fetchRow();
    $this->db->freeResult($res);
    $this->set_header($row);

    // prev and next objects exist?
    if (isset($row["${me}_ord"])) {
      $ord = $row["${me}_ord"];
      $x = $this->get_id_by_ord($ord - 1);
      $this->prev_obj = $x ? array('id'=>$x) : false;
      $x = $this->get_id_by_ord($ord + 1);
      $this->next_obj = $x ? array('id'=>$x) : false;
    } else {
      $this->prev_obj = false;
      $this->next_obj = false;
    }

    return $this->header;
  }

  function &get_contents_iterator ($q = '') {
    global $obj, $id, $lim;

    $this->create_child();
    $limit = $this->get_limit();
    $pageno = $this->get_pageno();

    if (empty($q)) {
      $query = "select * from ".$this->get_table('_contents', $obj);
      if ($id)
	$query .= " where ${obj}_id=".sqlspecialchars($id);
    } else
      $query = $q;
    $moreq = " limit ".($limit+1)." offset ".(($pageno-1)*$limit);
    $res = $this->db->query("$query $moreq");
    if (DB::isError($res)) {
      $this->num_rows = -1;
      $this->iterator = null;
      return $this->iterator;
    }
    if ($res->numRows() < 1) {
      if ($this->db->debug) {
        global $log;
        $log .= "Expected non-empty result set.<br>\n";
      }
      error("notfound2");
    }
    $rows = $res->numRows();

    // prev and next pages exist?
    $this->prev_page = $pageno > 1 ? array('pageno'=>$pageno-1, 'q'=>$GLOBALS['q']) : false;
    $this->next_page = $rows > $limit ? array('pageno'=>$pageno+1, 'q'=>$GLOBALS['q']) : false;

    $rows = $rows > $limit ? $limit : $rows;
    $this->num_rows = $rows;
    $this->iterator = $res;
    return $this->iterator;
  }

  function free_contents_iterator () {
    $this->db->freeResult($this->iterator); 
    parent::free_contents_iterator();
  }

  function search ($q, $limit=0) {
    $q = get_search_query($q);
    $idnq = $this->get_idn_query();
    include_once BASE.'backend/luceneindex/luceneindex.php';
    $luceneindex = luceneindex_backend_factory::get_luceneindex (false);
    return $luceneindex->query ($q, $idnq, $limit);
  }
}

?>
