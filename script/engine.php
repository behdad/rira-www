<?php

function get_page() {
  /* These are the only variables allowed in page/*.php */

  global $cfg;
  /* Query string vars */
  global $page, $mod, $obj, $id, $pageno, $lim, $ord, $q, $html_q, $rid, $onedoc;
  /* Outputs */
  global $o, $modules, $moddir, $modbase;
  global $cascade, $nocascade;
  global $title, $long_title, $header_title, $header, $onload, $implicit_body, $body, $pre_body;

  $round = 0;
  do {
    $round++;
    $o = null;

    $moddir  = "module/$mod/";
    $modbase = BASE.$moddir;

    if (!isset($mod) || !$mod || !preg_match('/^[a-z_]+$/', $mod) || !file_exists(BASE."module/$mod"))
      $mod = "public";
    if (!isset($page) || !$page || !preg_match('/^[a-z_]+$/', $page) || !file_exists(BASE."page/$page.php"))
      $page = "view";
    if (!isset($obj) || !$obj || !preg_match('/^[a-z_]+$/', $obj))
      $obj = "home";
    if (!isset($id) || !preg_match('/^[a-z_0-9]+$/', $id))
      $id = 0;


    $module = &$modules[$mod];
    @include_once $modbase."init.php";

    $title = $long_title = $header_title = $header = $onload = $implicit_body = $body = $pre_body = '';
    unset($cascade); unset($nocascade);

    ob_start();
    $o = new_rira_obj($obj);
    if (!$o || !(false === array_search ($page, $o->deny_page))) {
      $o = new_rira_obj();
      $o->me = $obj;
    }
    include BASE."page/$page.php";
    $implicit_body = ob_get_contents();
    ob_end_clean();
    if (!empty($noaccess))
      error('accessdenied');
  } while (cascade() && $round < $cfg['max_redirect']);
  $header .= site_css ('main', custom);
  $header .= site_js ('main', custom);
}

function cascade () {
  global $nocascade, $cascade, $o;
  if (!empty($nocascade) || !empty($o->nocascade))
    return false;
  if (!empty($cascade)) {
    follow_link($cascade);
    return true;
  }
  if (!empty($o->cascade)) {
    follow_link($o->cascade);
    return true;
  }
  return false;
}

?>
