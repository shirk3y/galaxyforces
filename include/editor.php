<?php

// ===========================================================================
// Editor {editor.php)
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.0
//	Created:	2005-05-13
//	Modified:	2005-05-13
//	Author(s):	zoltarx@o2.pl
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of phpSynapse project. See documentation for details.
// ===========================================================================

if (! defined('__EDITOR_PHP__')) {
	locale('editor');

	function editor($name = 'text', $cols = 60, $rows = 20, $align = 'center') {
		global $Lang;

		echo "\t<table id=\"editor\" cellspacing=\"0\" cellpadding=\"0\">\n";

		echo "\t<tr><td align=\"$align\"><textarea name=\"$name\" cols=\"$cols\" rows=\"$rows\" onselect=\"storeCaret(this);\" onclick=\"storeCaret(this);\" onkeyup=\"storeCaret(this);\"></textarea></td></tr>\n";

//		echo "\t<tr><td>&nbsp;</td></tr>\n";

		echo "\t<tr><td><table align=\"$align\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>";

		foreach (array(
			'p'=>'[p]',
			'br'=>'[br]',
			'title'=>'[title][/title]',
			'code'=>'[code][/code]',
			'b'=>'[b][/b]',
			'i'=>'[i][/i]',
			'u'=>'[u][/u]',
			'sup'=>'[sup][/sup]',
			'sub'=>'[sub][/sub]',
//			''=>'',
			'center'=>'[center][/center]',
			'left'=>'[left][/left]',
			'right'=>'[right][/right]',
			'justify'=>'[justify][/justify]',
			'img'=>'[img][/img]',
			'link'=>'[link=adres url][/link]',
		) as $tag => $text) echo $tag == '|' ? '</tr><tr>' : ($tag ? "<td id=\"button\"><input class=\"button\" type=\"button\" value=\"$tag\" onclick=\"addtext('$text')\" onmouseout=\"self.status=''; return true\" onmouseover=\"self.status='".$Lang['Tags[]'][$tag]."'; return true\" /></td>" : '<td>&nbsp;</td>');

		echo "</tr></table></td></tr>\n";

		echo "\t</table>\n";
	}

	define('__EDITOR_PHP__', TRUE);
}
elseif ($Config['Debug']) error("${Lang['File']} <b>${_SERVER['PHP_SELF']}</b> ${Lang['DefinedMoreThanOnce']}");
