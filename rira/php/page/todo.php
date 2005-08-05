<?php /*

	 - Move poem tables and stuff to CSS.
	 - Better error handling.  Use samples from php.net, to email on
	   errors.   Also see the one on 1and1.  Also use PHP error
	   handling functions for user errors.
	 - implement a real singleton pattern for sqldb backend.
	 - quran sura view center navpal.  generalize?
	 - do unicode-bidi:embed for fields, like header, items, etc.
	 - do nohighlight in span instead of <a>, simplifies CSS. should we do
	   that?  humm, link the numbers, not the text.  what about poetry?
	 - add navigation links and icon too.
	 - add more meta: title should be page title, not "RiRa".  set
	   meta for module, object, id, page?, ...
	   dynamic "keywords" that features all header (haafez, ghazaliaat...!)
	 - public/module and othermodules/home should share [long_]title.
	 - classicpoems__home should get deeper.
	 - unify <h1>, <h2> generation...?
	 - revise poem/body to benefit from linked items in rira_obj.
	 - non-integer $ord, use $ord as enumerate text.
	 - descending sort order
	 - list pages google style
	 - link rel copyright!  link rel contents, chapter, section?... rev?


	 - move include_once's down the code, when needed.
	 - rework random to pass id instead of rid. faal is broken, shows multiple poems!
	 - hightlight item if $ord is set.  introduce $hilite, with vals 0, 1, 2?
	 - add search box for poems too.
	 - allow "faal" everywhere.
	 - finish view the whole book thing.
	 - increase limit.
	 - add print media to CSS, no background-image, colors, no navpanel,
	   but page no.

* Add RiRa to wikipedia, also link from Project Gutenberg.
* Discover different poem types and normalize poems within each type (disblockify) and format them in view.
* Put htmlspecialchars all around the code.
* Move object properties to a table in public schema?
* Audio and image support.
* Advanced search support.


* Testaments module
* Dictionaries module
* ContemporaryPoems module

* Waw-Hamza doesn't appear in the data. Fix the convertor and reimport the data.
*/ ?>
