<?php

  if ($o->random && isset($rid)) {
    $id = $o->get_random_id($rid);
    unset($rid);
    unset($o->header);
  }
  
  $header_title = $o->get_header_string(1);
  $title = $o->canonical_title;
  $long_title = found($o->canonical_long_title);
  $header = $o->get_html_header();
  
  $iterator = $o->get_contents_iterator();
  
  if (isset($o->c) && $o->c->random) {
    $header_title .= ' '.make_button('فال', array('obj'=>$o->child, 'rid'=>$id));
  }
    
  if ($o->searchable) {
    $header_title = make_form("$header_title ".
      '<span class="search">'."\n".
      '<input type="text" accesskey="s" size="30" name="q" value="'.$html_q.'" onkeypress="PersianKeyPress(event)" onkeydown="PersianKeyDown(event)"/>'."\n".
      '<input type="submit" class="button" value="'."پیدا کن".'"/>'."\n".
      '</span>',
      array('page'=>'search'));
  }

  unset($o->next_content);
  switch ($o->num_rows) {
    case -1: {
      break;
    }
    case 0: {
      die("nothing found");
      break;
    }
    case 1: {
      if ($pageno == 1 && $o->create_child() && isset($o->c->child)) {
        $o->next_content = $row = $o->iterator->fetchRow();
        $o->cascade = array('obj'=>$o->child, 'id'=>$row[$o->child."_id"]);
      }
    }
    default: {
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
    }
  }
  unset($o->next_content);
  $o->free_contents_iterator();
 
?>
