<?php

  if (!$o->searchable)
    error('accessdenied');

  include_once BASE."script/search.php";

  $header_title = "جستجو در ".$o->get_header_string(1, 0, array('all_q'=>$q));
  $title = "جستجو";
  $long_title = "عبارتِ جستجو را وارد کنید.";

  $header_title .= ' '.make_button('نمایش', array('page'=>'view'));

  $header = "";
  $nocascade = true;
  $header_title = make_form("$header_title ".
    '<span class="search">'."\n".
    '<input type="text" accesskey="s" size="30" name="q" value="'.$html_q.'" onkeypress="PersianKeyPress(event)" onkeydown="PersianKeyDown(event)"/>'."\n".
    '<input type="submit" class="button" value="'."پیدا کن".'"/>'."\n".
    '</span>',
    array('page'=>'search'));

  if (!empty($q)) {
    $new_q = get_search_query($q);
  
    $idnq = $o->get_idn_query();
    $start = (($pageno-1)*$lim);
    $cmd = "echo ".escapeshellarg(($lim+1)."\n$start\n$idnq\n$new_q")
         . " | ".BASE."../lib/search/search";
    $res = explode("\n", `$cmd`);
    $clean_q = $res[0];

    $title = "جستجو برای ${q}&rlm;";
    $long_title = "نتایجِ جستجو برای ".notfound(found($html_q, $clean_q, '+'), $clean_q, '-');

    $o->total_rows = $res[1];
    $o->num_rows = $res[2];
    if ($o->num_rows > $lim && $o->num_rows != $o->total_rows) {
      $o->num_rows = $lim;
      $o->next_page = true;
    } else 
      $o->next_page = false;

    $body .= '<div class="searchresults">';
    $body .= "یافته‌های ".localized_number($start+1)." تا ".localized_number($start+$o->num_rows)." از ".localized_number($o->total_rows)." یافته<br/>";
    $body .= '</div>';

    $old_page = $page;
    $page = 'view';
    $old_id = $id;
    $old_obj = $obj;
    $d = &new __rira_obj;
    $body .= $d->body_begin();
    for ($i = 0; $i < $o->num_rows; $i++) {
      $d->row_num = $start + $i + 1;
      $idn = &$res[$i*2+3]; unset($res[($i+1)*2]);
      $text = &$res[$i*2+4]; unset($res[($i+1)*2+1]);
      
      if (preg_match("/x ([^ ]+) .* ([0-9]+) ([^ ]+) +x *$/", $idn, $fields)) {
        $newmod = $fields[1];
        $id = $fields[2];
        $obj = $fields[3];

	$f = new_rira_obj ($obj);
	
        $row_text = "<strong>".$f->get_header_string(2, 0, array('valid_id'=>true, 'all_q'=>$q, 'mod'=>$newmod))."</strong>";
	if (!empty($text)) {
	  $row_text .= "<blockquote class=\"snippet\"><small>";
	  $row_text .= found(htmlspecialchars(snippet($text)));
	  $row_text .= "</small></blockquote>";
	}

        $body .= $d->body_row($row_text);
        unset($f);
      }
      unset($text);
      unset($row_text);
    }
    $body .= $d->body_end();
    unset($fields);
    unset($d);
    if ($o->total_rows == 1) {
      $nocascade = false;
      $o->nocascade = false;
      $o->cascade = false;
      $cascade = array('id'=>$id, 'obj'=>$obj, 'page'=>$page, 'mod'=>$newmod);
    } else if ($o->total_rows == 0) {
      $long_title = "یافت نشد.";
      $body = "";
    }
    $id = $old_id; unset($old_id);
    $obj = $old_obj; unset($old_obj);
    $page = $old_page; unset($old_page);
      

    $o->prev_page = $pageno > 1 ? array('q'=>$q, 'pageno'=>$pageno-1) : false;
    $o->next_page = $o->next_page ? array('q'=>$q, 'pageno'=>$pageno+1) : false;

    $o->prev_obj = false;
    $o->next_obj = false;
  }
 
?>
