<?php

class __rira_sqldb_home extends __rira_sqldb_obj {

  var $name = "همه";
  var $title = "خانه";
  var $parent = 'public__home';
  var $limit_factor = 0;
  var $title_field = '_title';
  var $long_title_field = '_long_title';
  var $order_field = '_ord';

  function &get_contents_iterator ($query = '') {
    if (empty($query)) {
      $query = 'select * from '.$this->get_table($this->child.'_header');
      if ($this->order_field) {
	$field = $this->get_field($this->order_field, $this->child);
	$query .= " order by $field";
      }
    }
    return parent::get_contents_iterator($query);
  }

  function &get_header_data () {
    return __rira_obj::get_header_data();
  }

}

?>
