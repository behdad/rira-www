<?php
  // Write out the navigation panel.
  // Input: $cfg, $o.
  {
    $navempty = true;
    $navpanel = '<div class="navpanel"><hr /><table><tbody>'."\n";
    if (!empty($o->prev_page) || !empty($o->next_page)) {
      $navempty = false;
      $navpanel .= '<tr>'."\n".'<td class="right">';
      if (!empty($o->prev_page))
	$navpanel .= make_link($cfg['prev']."صفحه‌ی قبل", $o->prev_page);
      $navpanel .= "</td>\n<td>صفحه‌ی ".localized_number($pageno)."</td>\n<td class=\"left\">";
      if (!empty($o->next_page))
	$navpanel .= make_link("صفحه‌ی بعد".$cfg['next'], $o->next_page);
      $navpanel .= "</td>\n</tr>\n";
    }
    $navpanel .= "<tr>\n<td class=\"right\">";
    if (!empty($o->prev_obj)) {
      $navpanel .= make_link($cfg['prev'].ezafi_form($o->name)." قبل", $o->prev_obj);
      $navempty = false;
    }
    $navpanel .= "</td>\n<td><a href=\"http://behdad.org/\" title=\"بهداد اسفبهد\">b</a></td>\n<td class=\"left\">";
    if (!empty($o->next_obj)) {
      $navpanel .= make_link(ezafi_form($o->name)." بعد".$cfg['next'], $o->next_obj);
      $navempty = false;
    }
    $navpanel .= "</td>\n</tr>\n";
    $navpanel .= "</tbody></table></div>\n";
    if (!$navempty)
      echo $navpanel;
  }
?>
