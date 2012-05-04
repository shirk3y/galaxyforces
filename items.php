<?php

// ===========================================================================
// Items {items.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.4
//	Modified:	2005-11-13
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$auth = true;

require('include/header.php');

if (!$User['usergroup'] || $action == 'give' && $User['usergroup'] != $Config['Administrators']) die;

// ===========================================================================
// GIVE
// ===========================================================================

if ($action == 'give' && ($id = (int)getvar('id')) && $name) {
	$db->query("SELECT * FROM {$prefix}items WHERE `id`='$id';");
	if ($item = $db->fetchrow()) {
		if ($count = abs(getvar('count')) $item['count'] = $count;
		addequipment($item, $name);
		tablebegin();
		echo "<br /><b>" . $Lang['items'][$item['name']]['name'] . "</b> ${Lang['given2']} <a href=\"whois.php?name=$name\">$name</a><br /><br />";
		tableend();
		echo "\t<br />\n";
	}
}

// ===========================================================================
// LIST
// ===========================================================================

$category = getvar('category');
$page = abs(getvar('page'));

$pagecount = 10;

switch ($category) {
	case 1: $order = "`type` ASC"; break;
	case 2: $order = "`class` ASC"; break;
	default: $order = "`type` ASC, `name` ASC"; break;
}

$db->query("SHOW TABLE STATUS FROM `" . $Config['Database']['Name'] . "` LIKE '${prefix}items';");
if ($t = $db->fetchrow()) $max = $t['Rows'];
else $max = 0;

if ($page > $m = floor($max / $pagecount)) $page = $m;
$l = $page * $pagecount;

$db->query("SELECT * FROM `${prefix}items` ORDER BY $order LIMIT $l,$pagecount;");

tablebegin($Lang['Items']);

subbegin();

echo "\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";

$i = 0;
while ($t = $db->fetchrow()) $tab[] = $t;

foreach ($tab as $t) {
	if ($i % 4) $id = ' id="div"'; else $id = '';

	$i++;

	if ($i % 2) {
		echo "\t<tr height=\"84\"$id>\n\t<td width=\"80\">";
		tableimg('images/pw.gif', 72, 72, "gallery/items/${t['name']}.jpg", 64, 64, "description.php?type=items&back=items.php&subject=${t['name']}&id=${t['id']}");
		echo "</td>\n\t<td>&nbsp;</td>\n\t<td>\n";
	}
	else {
		echo "\t</td>\n\t<td width=\"8\">&nbsp;</td>\n\t<td align=\"right\">\n";
	}

	echo "\t\t<b>${Lang['Name']}</b>: <font class=\"plus\">" . $Lang['items'][$t['name']]['name'] . "</font><br />\n";
	echo "\t\t<b>${Lang['Type']}</b>: <font class=\"capacity\">" . $Lang['ItemType[]'][$t['type']] . "</font><br />\n";
	echo "\t\t<b>${Lang['Class']}</b>: <font class=\"result\">" . $Lang['ItemClasses[]'][$t['class']] . "</font><br />\n";

	if ($i % 2) {
		echo "\t</td>\n<td width=\"8\">&nbsp;</td>\n";
	}
	else {
		echo "\t</td>\n<td width=\"8\">&nbsp;</td>\n\t<td width=\"80\">\n";
		tableimg('images/pw.gif', 72, 72, "gallery/items/${t['name']}.jpg", 64, 64, "description.php?type=items&back=items.php&subject=${t['name']}&id=${t['id']}");
		echo "</td>\n\t</tr>\n";
	}
}

echo "\t</table>\n";

subend();

$n = $page;

$s = ($n ? "<a href=\"items.php?page=0\">" : '').'&lt;&lt;&nbsp;'.$Lang['Begin'].($n ? '</a>' : '').' &nbsp; ';
$s .= ($n ? "<a href=\"items.php?page=".($n - 1).'">' : '').'&lt;&lt;&nbsp;'.$Lang['Previous'].($n ? '</a>' : '').' &nbsp; ';

$a = $n > 5 ? $n - 5 : 0;
$b = $n < $m - 5 ? $n + 5 : $m;

if ($a > 0) $s .= '... ';

for ($i = $a; $i <= $b; $i++) {
	if ($i != $n) $s .= "<a href=\"items.php?page=$i\">";
	$s .= $i + 1;
	if ($i != $n) $s .= "</a>";
	$s .= ' ';
}

if ($b < $m) $s .= ' ...';

$s .= " &nbsp; ".($n < $m ? "<a href=\"items.php?page=".($n + 1).'">' : '').$Lang['Next'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');
$s .= ' &nbsp; '.($n < $m ? "<a href=\"items.php?page=$m\">" : '').$Lang['End'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');

tableend($s);

require('include/footer.php');
