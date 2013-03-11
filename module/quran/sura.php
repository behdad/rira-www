<?php

class quran__sura extends __rira_default_obj {
  var $name = "سوره";
  var $parent = "home";
  var $child = "verse";
  var $title_field = "sura_title";
  var $nocascade = true;
  var $searchable = true;
  var $searchindexed = true;

  function body_begin_format ()
  {
    return '<div class="sura">'."\n";
  }

  function body_row_format ($contents, $row_num = '')
  {
    $s = $contents;
    if ($row_num)
      $s .= '&#x06DD;'.localized_number($row_num);
    $s .= "\n";
    return $s;
  }

  function body_end_format ()
  {
    return '</div>'."\n";
  }

}

?>
