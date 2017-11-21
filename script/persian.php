<?php

function localized_number($str) {
  return preg_replace_callback('/([0-9])/', function ($m) { return chr(0xdb).chr(0xb0+$m[0]); }, $str);
}

// This function is bogus, as it adds YEH to all words
// ending in HEH. Test case: Paadeshaah.
function ezafi_form($s) {
  if (substr($s, -2) == "\xd9\x87") // HEH
    return $s."\xe2\x80\x8c\xdb\x8c"; // ZWNJ+YEH
  else
    return $s;
}

?>
