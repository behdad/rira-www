<?php
  // Input: $mod, $modbase, $moddir, $bg.

  // $bg if set, should be a six-digit hex color

  $rel = "style/background.png";
  $url = $moddir.$rel;
  if (file_exists ($modbase.$rel)) {
    echo "
body {
  filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='$url',sizingMethod='image');
}
html>body {
  background-image: url(\"$url\");
}
";
  }

  if (isset ($bg)) {

    // compute a highlight background color
    $hlbg = '';
    foreach (array (0, 2, 4) as $offset) {
      $color = hexdec (substr ($bg, $offset, 2));
      $color = min (255, (int) ($color * 1.5));
      $color = str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
      $hlbg .= $color;
    }

  echo "
body {
  background-color: #$bg;
}
a.nohighlight:hover {
  background-color: #$hlbg;
}
";
  }
?>
