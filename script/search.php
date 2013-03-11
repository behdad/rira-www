<?php

define('utf8char', '(?:[\x00-\x7f]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF][\x80-\xBF])');

function get_search_query ($q = '') {
  empty($q) && $q = $GLOBALS['q'];
  if (empty($q))
    return $q;
  static $from = array ('/ and /i', '/ or /i', '/ not /i', '/ و /', '/ یا /', '/ بدون /', '/×/', '/؟/', '/«/', '/»/');
  static $to   = array (' AND '   , ' OR '   , ' NOT '   , ' AND ', ' OR '  , ' NOT '   , '*'  , '?'  , '"'  , '"'  );
  $q = " $q \"\"";
  $q = preg_replace('/(.*?)(?:"|«)(.*?)(?:"|»)/e', 'preg_replace($from, $to, \'\1\').\'"\2"\'', $q);
  $q = substr($q, 0, -2);
  return $q;
}

function get_query_pregexp ($q = '', $sel = '') {
  static $last_q = '';
  static $last_qr;
  static $last_sel;
  $q = get_search_query($q);
  if (empty($q))
    return $q;
  $from = array ('/ AND /', '/ OR /', '/ NOT /', '/[()\/]/', '/\*/'         , '/\?/'  , '/(?<= )([-+]?[^" ]+?|[-+]?".+?") +/', '/"/', '/^ +\|*(.*?)\|* *$/', '/\|\+(.*?)(?=\|)/', '/\|\-(.*?)(?=\|)/', '/^ *\|? *(.*?) *\|? *$/i');
  $to   = array (' '      , ' '     , ' '      , ''        , '[^[:space:]]*', utf8char, '\1|'                                , ''   , '|\1|'               , ($sel=='-'?'':'|\1')    , ($sel=='+'?'':'|\1')    , '\1');
  if ($q != $last_q || $sel != $last_sel) {
    $last_qr = '(«|‌|[-+"\s])('.preg_replace($from, $to, ' '.preg_replace('/  +/', ' ', $q).' ').')()(?=‌|»|[-+"\s])';
    $last_q = $q;
    $last_sel = $sel;
  }
  return $last_qr;
}

function found ($s, $q = '', $sel = '') {
  empty($q) && $q = $GLOBALS['q'];
  if (empty($q))
    return $s;
  return trim(preg_replace("/".get_query_pregexp($q, $sel)."/i", '\1<span class="found">\2</span>\3', "    $s    "));
}

function notfound ($s, $q = '', $sel = '-') {
  empty($q) && $q = $GLOBALS['q'];
  if (empty($q))
    return $s;
  return trim(preg_replace("/".get_query_pregexp($q, $sel)."/i", '\1<span class="notfound">\2</span>\3', "    $s    "));
}

function snippet ($s, $q = '') {
  global $cfg;
  $l = $cfg['snippet_affix_len'] * 2;
  $qr = get_query_pregexp($q);
  $t = '';
  $query = '/(?:\s[^|]{0,'.$l.'}?)?'.$qr.'(.{0,'.($l).'}'.$qr.')*(?:[^|]{0,'.$l.'}\s)?/i';
  preg_match_all($query, "       $s        ", $res, PREG_SET_ORDER);
  $i = 0;
  foreach ($res as $match) {
    $i++;
    if ($i > $cfg['search_snippets_num'])
      break;
    $t .= (empty($t) ? '' : ' ... ').$match[0];
  }
  if ($i > $cfg['search_snippets_num'])
    $t .= " ...";
  return $t;
}

?>
