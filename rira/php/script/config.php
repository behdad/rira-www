<?php

/* General */
$cfg['app'] = "rira"; // used as session/cookie prefix
$cfg['debug'] = true;

/* PHP */
$cfg['php_debug'] = $cfg['debug'];

/* Localization */
$cfg['lang'] = 'fa';
$cfg['locale'] = 'fa_IR';
$cfg['dir'] = 'rtl';

/* Sql backend */
// format: 'backend://username[:password]@host/dbname'
$cfg['dsn'] = 'pgsql://apache@localhost/rira';
$cfg['db_persistent'] = true;
$cfg['db_debug'] = $cfg['debug'];

/* Object engine */
$cfg['max_redirect'] = 5;

/* View and search */
$cfg['rows_per_page'] = 20;
$cfg['rows_per_page_max'] = 10000;
$cfg['short_title_len'] = 200;
$cfg['snippet_affix_len'] = 20;
$cfg['search_snippets_num'] = 5;

/* Presentation */
$cfg['missing_title'] = '&#9633;'; // U+25A1 WHITE SQUARE
$cfg['ellipsis'] = '&#8230;'; // U+2026 HORIZONTAL ELLIPSIS
$cfg['separator'] = ' > ';
$cfg['prev'] = '';
$cfg['next'] = '';
/* Some alternatives: */
//$cfg['missing_title'] = '* * *';
//$cfg['ellipsis'] = '...';
//$cfg['separator'] = ' : '; // MacOS X style ;-)
//$cfg['prev'] = '< ';
//$cfg['next'] = ' >';
?>
