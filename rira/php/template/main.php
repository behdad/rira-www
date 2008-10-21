<<?php echo '?'; ?>xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $cfg['dir']; ?>" xml:lang="<?php echo $cfg['lang']; ?>">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta http-equiv="Content-Style-Type" content="text/css"/>
  <meta http-equiv="Content-Script-Type" content="text/javascript"/>
  <meta http-equiv="Content-Language" content="<?php echo $cfg['lang']; ?>"/>
  <meta xml:lang="en" name="author" content="Behdad Esfahbod"/>
  <meta xml:lang="en" name="copyright" content="2003--2008  Behdad Esfahbod"/>
  <meta name="webmaster" content="http://behdad.org/"/>
  <meta xml:lang="en" name="description"
   content="RiRa is a Persian Digital Library.  Currently it contains the Quran and
   many books of classical-style Persian poetry of various poets."/>
  <meta name="keywords" content="Persian,Farsi,Poetry,Literature,Hafez,Shams,Molavi,Saadi,Khayam"/>
  <meta name="rira_object" content="<?php echo "$mod:$obj$id"; ?>"/>
  <link rel="icon" type="image/png" href="style/favicon.png">
  <link rel="copyright" content="http://creativecommons.org/licenses/publicdomain/"/>
<?php
  $link_prev = !empty($o->prev_page) ? $o->prev_page : !empty($o->prev_obj) ? $o->prev_obj : false;
  if (!empty ($link_prev))
    echo '  <link rel="prev" href="'.make_url($link_prev).'"/>'."\n";
  $link_next = !empty($o->next_page) ? $o->next_page : !empty($o->next_obj) ? $o->next_obj : false;
  if (!empty ($link_next))
    echo '  <link rel="next" href="'.make_url($link_next).'"/>'."\n";
  $link_up = !empty($o->up_obj) ? $o->up_obj : false;
  if (!empty ($link_up))
    echo '  <link rel="up" href="'.make_url($link_up).'"/>'."\n";
?>
  <title><?php echo $title; ?></title>
  <?php echo $header; ?>
</head>
<body onload="<?php echo $onload; ?>">
<div class="<?php echo "main mod_$mod obj_$obj"; ?>">
<?php
  echo $pre_body;
  unset($pre_body);

  if (!empty($header_title))
    echo "<div class=\"header_title\">$header_title</div>\n";
  if (!empty($long_title))
    echo "<div class=\"title\"><h1>".make_link($long_title, array(), 'nohighlight')."</h1></div>\n";

  if (!empty($body)) {
    echo '<div class="contents">';
    echo $implicit_body;
    unset($implicit_body);
    echo $body;
    unset($body);
    echo "</div>\n";
    
  }
  include 'navpanel.php';
  
  @include 'custom/footer.php';
?>
</div>
</body>
</html>
