<?php

class __rira_obj {

  var $deny_page = array();
  var $random = false;
  var $searchable = false;
  var $searchindexed = false;

  function __rira_obj () {
    $m = get_class($this);
    $i = strpos($m, '__');
    if ($i === false) {
      $this->module = '';
      $this->me = $m;
    } else {
      $this->module = substr ($m, 0, $i);
      $this->me = substr($m, $i+2);
    }
    if (!isset($this->name))
      $this->name = $this->me;
  }

  function get_field ($field, $obj = false) {
    if (!$obj)
      $obj = $this->me;
    return $field[0] == '_' ? $obj.$field : $field;
  }

  function &create_parent () {
    if (isset ($this->p))
      return $this->p;
    if (empty($this->parent)) {
      $this->p = NULL;
      return $this->p;
    }

    if (!($this->p = &new_rira_obj($this->parent, $this->module)))
      return false;
    if (isset($this->header))
      $this->p->set_header($this->header);
    if (!empty($this->p->child) && $this->p->child == $this->me) {
      $this->p->c = &$this;
    }

    $h = $this->header;
    $p_id = $this->p->me."_id";
    $this->up_obj = array('mod'=>$this->p->module, 'obj'=>$this->p->me, 'id'=>isset($h[$p_id])?$h[$p_id]:0);

    return $this->p;
  }

  function &create_child () {
    if (isset ($this->c))
      return $this->c;
    if (empty($this->child)) {
      $this->c = NULL;
      return $this->c;
    }

    if (!($this->c = &new_rira_obj($this->child, $this->module)))
      return false;
    if (isset($this->content))
      $this->c->set_content($this->content);
    if (!empty($this->c->parent) && $this->c->parent == $this->me) {
      $this->c->p = &$this;
    }
    return $this->c;
  }

  function get_children_count ($p_id = 0) {
    return 0;
  }

  function set_header (&$h) {
    $this->header = &$h;
    if (isset($this->p))
      $this->p->set_header($h);
  }

  function set_content (&$r) {
    $this->content = &$r;
    if (isset($this->c)) {
      $this->c->set_content($r);
    }
  }

  function set_next_content (&$r) {
    $this->next_content = &$r;
    if (isset($this->c)) {
      $this->c->set_next_content($r);
    }
  }

  function get_id_by_ord ($ord, $p_id = 0) {
    return false;
  }

  function get_random_id ($p_id = 0) {
    $n = $this->get_children_count($p_id);
    if (!$n)
      return 0;
    return $this->get_id_by_ord(rand(1, $n), $p_id);
  }

  function &get_header_data () {
    if (isset ($this->header))
      return $this->header;

    $this->prev_obj = false;
    $this->next_obj = false;
    
    $this->header = array();

    return $this->header;
  }

  function get_title () {
    $me = &$this->me;
    $this->title = isset($this->default_title) ? $this->default_title : '';
    if (isset($this->title_field)) {
      if (is_array($this->title_field))
        $fields = $this->title_field;
      else
        $fields = array($this->title_field);
      foreach ($fields as $field) {
	$field = $this->get_field($field);
        if (isset($this->header)) {
          $h = $this->header;
          if (isset($h[$field]))
            $this->title = $h[$field];
        }
        if (isset($this->content)) {
          $h = $this->content;
          if (isset($h[$field]))
            $this->title = $h[$field];
        }
	if (!empty($this->title))
	  break;
      }
    }
    return $this->title;
  }

  function get_long_title () {
    $me = &$this->me;
    $this->long_title = isset($this->default_long_title) ? $this->default_long_title : '';
    if (isset($this->long_title_field)) {
      if (is_array($this->long_title_field))
        $fields = $this->long_title_field;
      else
        $fields = array($this->long_title_field);
      foreach ($fields as $field) {
	$field = $this->get_field($field);
        if (isset($this->header)) {
          $h = $this->header;
          if (isset($h[$field]))
            $this->long_title = $h[$field];
        }
        if (isset($this->content)) {
          $h = $this->content;
          if (isset($h[$field]))
            $this->long_title = $h[$field];
        }
	if (!empty($this->long_title))
	  break;
      }
    }
    if (empty($this->long_title))
      $this->long_title = $this->get_title();
    return $this->long_title;
  }

  function get_header_string ($linked = 1, $ord = 0, $flag = array()) {
    global $page, $cfg;
    $me = &$this->me;
    $h = &$this->get_header_data();
    $this->get_title();
    $this->get_long_title();
    if (isset($flag['q'])) {
      $q = $flag['q'];
      unset($flag['q']);
    } else
      $q = isset($flag['all_q']) ? $flag['all_q'] : false;
    if ((empty($flag['valid_id'])
         || (!empty($this->parent) && isset($h[$this->parent."_id"])))
	&& $this->create_parent()) {
      if ($linked && isset($h["${me}_ord"]))
        $p_ord = $h["${me}_ord"];
      else
        $p_ord = 0;
      $p_h = $this->p->get_header_string($linked+1, $p_ord, $flag);
      $p_t = $this->p->title;
      $p_lt = $this->p->long_title;
    } else
      $p_h = $p_t = '';
    $title = short_title($this->title);
    if (!empty($title)) {
      $fl = array('mod'=>$this->module, 'obj'=>$me, 'id'=>isset($h["${me}_id"])?$h["${me}_id"]:0, 'ord'=>$ord);
      if (!empty($q))
        $fl['q'] = $q;
      else
        if ((isset($fl['page']) && $fl['page'] == 'search') || $page == 'search')
	  $fl['page'] = 'view';
      $title = make_breadcrumb($title, $fl, $linked == 1 ? 'nohighlight' : '');
      unset($fl);
    }
    $this->header_string[$linked] = $p_h . $title;
    $this->canonical_title = empty($this->title) ? $p_t : $this->title;
    $this->canonical_long_title = empty($this->long_title) ? $p_t : $this->long_title;
    return $this->header_string[$linked];
  }

  function get_html_header() {
    return '';
  }

  function get_onload() {
    return '';
  }

  function get_limit_factor () {
    return isset($this->limit_factor) ? $this->limit_factor : 1;
  }

  function get_limit_quantum () {
    return isset($this->limit_quantum) ? $this->limit_quantum : 1;
  }

  function get_limit () {
    global $lim, $cfg;

    $limit = $lim;
    $factor = $this->get_limit_factor();
    if ($factor)
      $limit =(int)($limit*$this->get_limit_factor());
    else
      $limit = $cfg['rows_per_page_max'];

    $quantum = $this->get_limit_quantum();
    if ($quantum > 1) {
      $limit = ((int)(($limit - 1) / $quantum) + 1) * $quantum;
    }
    return $limit;
  }

  function get_pageno () {
    global $ord;
    if (!isset($GLOBALS['pageno']))
      $GLOBALS['pageno'] = (int)(($ord-1) / $this->get_limit()) + 1;
    return $GLOBALS['pageno'];
  }
  
  function &get_contents_iterator ($q = '') {
    if (isset ($this->iterator))
      return $this->iterator;

    $this->prev_page = false;
    $this->next_page = false;
    $this->num_rows = 0;
    $this->iterator = null;
    return $this->iterator;
  }

  function free_contents_iterator () {
  }

  function body_begin () {
    $this->last_child_id = -1;
    $this->effective_row_num = 0;
    return '<ul class="contents">'."\n";
  }
  
  function body_row ($default = '', $recurse = 'noX') {
    global $cfg, $onedoc;

    if ($recurse == 'noX')
      $recurse = $onedoc;
    $s = '<li>';
    if (isset($this->row_num))
      $s .= localized_number($this->row_num).'. ';
    if (isset($this->c)) {
      $t = $this->c->get_long_title();
      if (isset($this->c->child))
        $t = short_title($t);
      if (empty($t))
        $t = $cfg['missing_title'];
      $t = found($t);
      if (isset($this->c->child))
        $t = make_link($t, array('obj'=>$this->child, 'id'=>$this->content[$this->child."_id"]));
      else
        $t = make_link($t, array('ord'=>$this->content[$this->child."_ord"]), "nohighlight");
      $s .= $t;
      if ($this->last_child_id == $this->content[$this->child."_id"])
        return '';
      $this->effective_row_num++;
      $this->last_child_id = $this->content[$this->child."_id"];
    } else
      $s .= empty($default) ? $cfg['missing_title'] : $default;

    if ($recurse && isset($this->c)) {
      $o = $this->c;
      $iterator = $o->get_contents_iterator();
      if (!isset($o->next_content))
        $o->set_next_content($o->iterator->fetchRow());
      $o->row_num = ($pageno - 1) * $o->get_limit();
      $body .= $o->body_begin();
      for ($i = 0; $i < $o->num_rows; $i++) {
        $o->set_content($o->next_content);
        $o->set_next_content($o->iterator->fetchRow());
	$o->row_num++;
        $body .= $o->body_row();
      }
      $body .= $o->body_end();
      unset($o->content);
      $s .= $o->body_begin();
      $s .= $o->body_row();
      $s .= $o->body_end();
    }

    $s .= "</li>\n";
    return $s;
  }
  
  function body_end () {
    //if ($this->effective_row_num == 1)
    //  $this->cascade = array('obj'=>$this->child, 'id'=>$this->content[$this->child."_id"]);
    return '</ul>'."\n";
  }

  function get_idn_query ($leaf_obj = '') {
    global $mod;
    $me = &$this->me;
    $h = &$this->header;
    $s = "+\"x $mod\"";
    if (!empty($h["${me}_id"]))
      $s .= " +\"".$h["${me}_id"]." $me\"";
    if (!empty($leaf_obj))
      $s .= " +\"$leaf_obj x\"";
    return $s;
  }

}




function &new_rira_obj ($object = '__rira_obj', $module = '') {
    global $mod;

    if (!$module)
      $module = $mod;

    $i = strpos($object, '__');
    if ($i === false) {
      $klass = "${module}__${object}";
    } else {
      $klass = $object;
      $module = substr ($object, 0, $i);
      $object = substr($object, $i+2);
    }

    @include_once BASE."module/$module/$object.php";
    if (class_exists($klass))
      $instance = new $klass;
    else
      $instance = null;

    return $instance;
}

?>
