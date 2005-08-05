<?php

class classicpoems__poem extends __rira_default_obj {
  var $name = "شعر";
  var $parent = "part";
  var $child = "block";
  var $title_field = array("poem_title", "verse", "block_title");
  var $nocascade = true;
  var $random = true;
  var $searchindexed = true;

  function get_html_header() {
    $c = $this->create_child();
    return $c->get_html_header();
  }

  function get_onload() {
    $c = $this->create_child();
    return $c->get_onload();
  }

  function get_limit_factor() {
    $c = $this->create_child();
    return $c->get_limit_factor();
  }

  function get_limit_quantum() {
    return $this->get_verse_quantum();
  }

  function get_verse_quantum() {
    $h = &$this->get_header_data();
    $this->verse_quantum = empty($h["poem_type_quantum"]) ? 2 : $h["poem_type_quantum"];
    return $this->verse_quantum;
  }

  function body_begin () {
    parent::body_begin();
    $this->poem_n = 0;
    return '<div id="poem'.$this->next_content[$this->me."_id"].'" class="poem">';
  }

  function body_row () {
    global $cfg;
    $this->poem_n++;
    $n = $this->poem_n;
    $s = '';
    if ($n != 1 && $this->last_child_id != $this->content[$this->child."_id"]) {
      $s .= $this->c->body_end();
    }
    if ($n == 1 || $this->last_child_id != $this->content[$this->child."_id"]) {
      $this->c->get_long_title();
      if (!empty($this->c->long_title))
        $s .= '<h2>'.$this->c->long_title.'</h2>';
      else
        if ($n > 1)
	  $s .= "<h2>$cfg[missing_title]</h2>";
      $s .= $this->c->body_begin();
    }
    $s .= $this->c->body_row();
    if ($n == $this->num_rows)
      $s .= $this->c->body_end();
    $this->last_child_id = $this->content[$this->child."_id"];
    $this->effective_row_num++;
    return $s;
  }

  function body_end () {
    parent::body_end();
    return '</div>'."\n";
  }

}

?>
