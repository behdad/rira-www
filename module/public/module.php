<?php

class public__module extends __rira_default_obj {
  var $name = "وادی";
  var $parent = "home";
  var $child = "";
  var $title_field = "module_name";
  var $searchable = false;
  var $searchindexed = false;

  function public__module () {
    parent::__construct();
    global $id;
    $this->cascade = array('mod'=>$id, 'obj'=>false, 'id'=>false);
  }

  function &get_header_data () {
    $ret = array();
    return $ret;
  }
}

?>
