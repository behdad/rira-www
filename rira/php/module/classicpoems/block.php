<?php

class classicpoems__block extends __rira_default_obj {
  var $name = "قسمت";
  var $parent = "poem";
  var $child = "verse";
  var $title_field = "block_title";
  var $nocascade = true;

  function get_html_header() {
    return site_js("poem_justify").
           site_css("poem_justify").
	   "<!--[if IE]>".
	   site_css("poem_justify_ie").
	   "<![endif]-->".
	   module_css("block");
  }

  function get_onload() {
    return "justify_poems('block')";
  }

  function get_limit_quantum() {
    return $this->get_verse_quantum();
  }

  function get_limit_factor() {
    return 2;
  }

  function get_verse_quantum() {
    $p = $this->create_parent();
    return $p->get_verse_quantum();
  }

  function body_begin () {
    parent::body_begin();
    $this->bogusfill = '<span class="b"></span>';
    $this->quantum = $this->get_verse_quantum();
    $this->block_n = 0;
    return '<table id="block'.$this->next_content[$this->me."_id"].'" class="block" cellspacing="0" cellpadding="0">'."\n";
  }

  function body_row () {
    $quantum = $this->quantum;
    if ($this->block_n % $quantum == 0)
      $this->block_n = 0;
    $n = $this->block_n;
    if ($n%2 == 0 && $n+1 != $quantum && isset($this->next_content['block_id'])
        && $this->content['block_id'] == $this->next_content['block_id']) $align = 'r';
    else if ($n%2 == 1) $align = 'l';
    else $align = 'c';
      
    $s = '';
    if ($align != 'l')
      $s .= '<tr>'."\n";
    else
      $s .= '<td class="vsp"></td>'."\n";
    if ($align == 'c')
      $s .= '<td colspan=3 class="center"><table class="center"><tr>'."\n";
    $s .= '<td class="v">'."\n";
    $s .= '<span class="verse">'.found($this->content['verse']).'</span>'."\n";
    $s .= ' '.$this->bogusfill."\n";
    $s .= '</td>'."\n";
    if ($align == 'c')
      $s .= '</tr></table></td>'."\n";
    if ($align != 'r')
      $s .= '</tr>'."\n";
    $this->block_n++;
    $this->effective_row_num++;
    return $s;
  }

  function body_end () {
    parent::body_end();
    $s = '</table>'."\n";
    return $s;
  }

}

?>
