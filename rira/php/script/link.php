<?php

function link_get_prop($p, $x) {
  return isset($p[$x]) ? $p[$x] : $GLOBALS[$x];
}

function link_set_prop($p, $x) {
  if (isset($p[$x]))
    $GLOBALS[$x] = $p[$x];
}

function make_url ($p) {
  global $cfg;
  $lim = link_get_prop($p, 'lim');
  $page = link_get_prop($p, 'page');
  $mod = link_get_prop($p, 'mod');
  $obj = link_get_prop($p, 'obj');
  $id = link_get_prop($p, 'id');
  $q = link_get_prop($p, 'q');
  $t = "?page=$page";
  !empty($mod) && $t .= "&amp;mod=$mod";
  !empty($obj) && $t .= "&amp;obj=$obj";
  isset($p['rid']) && $t .= "&amp;rid=$p[rid]";
  (isset($p['id']) || (!isset($p['rid']) && !empty($id) && !isset($p['obj']))) && $t .= "&amp;id=$id";
  ($lim!=$cfg['rows_per_page'] || (isset($p['pageno']) && $p['pageno']>1)) && $t .= "&amp;lim=".($lim>=$cfg['rows_per_page_max']?-1:$lim);
  (isset($p['pageno']) && $p['pageno']>1) && $t .= "&amp;pageno=$p[pageno]";
  (isset($p['ord']) && $p['ord']>0) && $t .= "&amp;ord=$p[ord]";
  (isset($p['q']) && !empty($q)) && $t .= "&amp;q=".urlencode($q);
  return $t;
}

function make_link ($s, $p, $class = '') {
  global $cfg;
  $lim = link_get_prop($p, 'lim');
  $page = link_get_prop($p, 'page');
  $mod = link_get_prop($p, 'mod');
  $obj = link_get_prop($p, 'obj');
  $id = link_get_prop($p, 'id');
  $q = link_get_prop($p, 'q');
  $t = '<a href="'.make_url($p).'"';
  if ($class)
    $t .= ' class="'.$class.'"';
  $t .= ">$s</a>";
  return $t;
}

function make_button ($s, $p, $class = '') {
  return '<span class="button">&nbsp;&nbsp;'.make_link($s, $p, $class).'&nbsp;&nbsp;</span>';
}

function make_audio_button ($s, $url, $class = '') {
  return '<span class="button">&nbsp;&nbsp;'
	.'صدا '
        .'<embed class="'.$class.'" autoplay="no" autostart="no" hidden="no" width="25" height="25" src="'.$url.'"></embed>'
	.'&nbsp;&nbsp;</span>';
}

function hidden_input($c, $v) {
  return "  <input type=\"hidden\" name=\"$c\" value=\"$v\"/>\n";
}

function make_form ($s, $p) {
  global $cfg;
  $lim = link_get_prop($p, 'lim');
  $page = link_get_prop($p, 'page');
  $mod = link_get_prop($p, 'mod');
  $obj = link_get_prop($p, 'obj');
  $id = link_get_prop($p, 'id');
  $q = link_get_prop($p, 'q');
  $t = "<form method=\"get\" action=\"\"><p>\n";
  $t .= hidden_input('page', $page);
  !empty($mod) && $t .= hidden_input('mod', $mod);
  !empty($obj) && $t .= hidden_input('obj', $obj);
  isset($p['rid']) && $t .= hidden_input('rid', $rid);
  (isset($p['id']) || (!isset($p['rid']) && !empty($id) && !isset($p['obj']))) && $t .= hidden_input('id', $id);
  ($lim!=$cfg['rows_per_page']) && $t .= hidden_input('lim', $lim);
  (isset($p['pageno']) && $p['pageno']>1) && $t .= hidden_input('pageno', $pageno);
  (isset($p['ord']) && $p['ord']>0) && $t .= hidden_input('ord', $ord);
  (isset($p['q'])) && $t .= hidden_input('q', $q);
  $t .= "$s\n</p></form>\n";
  return $t;
}

function follow_link ($p) {
  $oldobj = $GLOBALS['obj'];
  link_set_prop($p, 'lim');
  link_set_prop($p, 'page');
  link_set_prop($p, 'mod');
  link_set_prop($p, 'obj');
  link_set_prop($p, 'id');
  link_set_prop($p, 'q');
  if (isset($p['obj']) && !isset($p['id']) && $p['obj'] != $oldobj)
    $GLOBALS['id'] = 0;
  if (isset($p['rid'])) {
    $GLOBALS['id'] = 0;
    $GLOBALS['rid'] = $p['rid'];
  }
  if (isset($p['pageno']))
    link_set_prop($p, 'pageno');
  elseif (isset($p['ord'])) {
    link_set_prop($p, 'ord');
    unset($pageno);
  } else
    $GLOBALS['pageno'] = 1;
}

?>
