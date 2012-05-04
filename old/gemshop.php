<?php

// ===========================================================================
// Gem Shop {gemshop.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.0
//	Modified:	2005-11-12
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'control';
$auth = true;

require('include/header.php');

$view = getvar('view');
$pagename = $Lang['GemShop'];

// ===========================================================================
// ERROR PAGE
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<h3>${Lang['ErrorProblems']}</h3><font class=\"error\">$errors</font><br />";
	echo "<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("$pagename");
	sound('error');
}

// ===========================================================================
// RESULTS PAGE
// ===========================================================================

elseif ($result) {
	tablebegin("$pagename", 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("$pagename");
	sound('thankyou');
}

// ===========================================================================
// DEFAULT PAGE
// ===========================================================================

elseif (checkplace('gemshop')) {
	tablebegin($pagename, 500);

	subbegin('images/table-b2.jpg');
	tableimg("images/bw.gif", 168, 168, 'gallery/places/gemshop.jpg', 160, 160, '', 'right');
	echo "\t\t<center><font class=\"h3\">${Lang['GemShop']}</font><br /><br />\n";
	echo "\t\t<font class=\"result\">${Lang['GemShopInfo']}</font><br />\n";
	subend();

	$ratio = $place['extra']; if ($ratio < 1) $ratio = 1;

	$query = '';

	for ($i = 0; $i < count($list = explode(',', $place['parameters'])); $i++) {
		if ($query) $query .= ' OR ';
		$query .= "`name`='${list[$i]}'";
	}

	$Items = array();

	if ($query) {
		$db->query("SELECT * FROM `${prefix}items` WHERE $query;");
		while ($t = $db->fetchrow()) $Items[] = $t;
	}

	if ($Items) {
		$mod = reputationmodifier($Player['reputation']);

		tablebreak();
		subbegin('images/table-a1.jpg');

		echo "\t\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";

		$b = FALSE;
		$c = FALSE;

		foreach ($Items as $t) {
			$b = ! $b;

			$icon = "gallery/items/icons/${t['name']}.jpg";

			if ($b) {
				$align = 'left';

				if ($c) echo "\t\t<tr valign=\"middle\"><td colspan=\"7\">&nbsp;</td></tr>\n"; else $c = TRUE;
				echo "\t\t<tr valign=\"middle\">\n";
				echo "\t\t<td width=\"72\">\n";
				tableimg('images/pw.gif', 72, 72, $icon, 64, 64, "description.php?type=items&back=gemshop.php&subject=${t['name']}&id=${t['id']}");
				echo "\t\t</td>\n";
			}
			else {
				$align = 'right';

				echo "\t\t<td>&nbsp;</td>\n";
			}

			echo "\t\t<td width=\"8\">&nbsp;</td>\n\t\t<td align=\"$align\"><font class=\"capacity\"><b>";
			echo $Lang['items'][$t['name']]['name'];
//			if ($t['level']) echo " +${t['level']}";
			echo '</b></font><br />';

			$r = '';
			if ($t['min'] || $t['max']) $r .= ($r ? ', ' : '') . "<b>${Lang['Damage']}</b>: <font class=\"plus\">${t['min']}-${t['max']}</font>";
			if ($t['armor']) $r .= ($r ? ', ' : '') . "<b>${Lang['Armor']}</b>: <font class=\"minus\">${t['armor']}</font>";

			echo "$r<br />";

			$price = round($t['price'] * $ratio * $mod);

			echo "<b>${Lang['Price']}</b>: <font class=\"result\">" . div($price) . "</font> <b>[!]</b><br />";
			echo "<form action=\"${_SERVER['PHP_SELF']}\" method=\"POST\"><input type=\"hidden\" name=\"action\" value=\"buyitem\" /><input type=\"hidden\" name=\"name\" value=\"${t['name']}\" />";
			if ($t['count']) echo "${Lang['Amount']}: <input type=\"text\" size=\"4\" name=\"amount\" value=\"1\" /> ";
			echo "<input type=\"submit\" value=\"${Lang['Buy']}\" /></form>";
			echo "</td>\n";

			echo "\t\t<td width=\"8\">&nbsp;</td>\n";

			if (! $b) {
				echo "\t\t<td width=\"72\">\n";
				tableimg('images/pw.gif', 72, 72, $icon, 64, 64, "description.php?type=items&back=gemshop.php&subject=${t['name']}&id=${t['id']}");
				echo "\t\t</td>\n\t\t</tr>\n";
			}
		}

		echo "\t\t</table>\n";
		subend();
	}

	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}

// ===========================================================================
// PAGE NOT AVAILABLE
// ===========================================================================

else {
	tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');
	echo "\t\t<h3>${Lang['NotAvailable']}</h3><font class=\"capacity\">${Lang['BugHint']}</font><br /><br />";
	tableend($back ? "<a href=\"$back\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>" : "${Lang['Error']}: ${Lang['NotAvailable']}");
}

require('include/footer.php');
