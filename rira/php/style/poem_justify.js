function justify_poem (verse_class, verse_tag, root_obj) {
	var width = -1;

	if (verse_class == undefined)
		verse_class = "v";
	if (verse_tag == undefined)
		verse_tag = "td";
	if (root_obj == undefined)
		root_obj = document.body;

        var verse_objs = root_obj.getElementsByTagName(verse_tag);

       	for (var i = 0;  i < verse_objs.length; i++) {
		verse_obj = verse_objs[i];
		if (verse_obj.className == verse_class)
			width = Math.max(width, verse_obj.scrollWidth);
	}

	// No verses found.
	if (width == -1)
		return;

	// A little stretch helps in certain situations, like resizing.
	//width *= 1.01;

	// A function to return the current font size of an object in pixels (hopefully)
	if (document.defaultView && document.defaultView.getComputedStyle)
		getComputedStyle = document.defaultView.getComputedStyle;
	var get_font_size = function(obj) {
		if (window.getComputedStyle) // Gecko
			// in px
			return parseInt(getComputedStyle(obj, null).getPropertyValue("font-size"));
		else if (obj.currentStyle) // IE
			// in pt, assume 96dpi.  Replace if you find some way to get dpi.
			return parseInt(obj.currentStyle.fontSize) * 96/72;
		else
			return "0";
	};
	

       	for (var i = 0;  i < verse_objs.length; i++) {
		verse_obj = verse_objs[i];

		if (verse_obj.className == verse_class) {
			var fontsize = get_font_size(verse_obj);

			// We prefer to set the width in 'em's, such that resizing doesn't fuck it up.
			if (!width) // Set to 20em if we cannot get width (IE)
				verse_obj.style.width = '20em';
			else if (fontsize)
				verse_obj.style.width = (width / fontsize) + 'em';
			else
				verse_obj.style.width = width + 'px';
		}
	}
}

function justify_poems (poem_class, poem_tag, verse_class, verse_tag, root_obj) {

	if (poem_class == undefined)
		poem_class = "poem";
	if (poem_tag == undefined)
		poem_tag = "table";
	if (root_obj == undefined)
		root_obj = document.body;

        var poem_objs = root_obj.getElementsByTagName(poem_tag);

       	for (var i = 0;  i < poem_objs.length; i++) {
		poem_obj = poem_objs[i];
		if (poem_obj.className == poem_class)
			justify_poem(verse_class, verse_tag, poem_obj);
	}
}

