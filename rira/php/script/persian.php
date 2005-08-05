<?php

function localized_number($str) {
  return preg_replace('/([0-9])/e', 'chr(0xdb).chr(0xb0+$1)', $str);
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
