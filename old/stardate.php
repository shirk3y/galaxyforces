<?php

// ===========================================================================
// Universal Galactic Calendar {stardate.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.1
//	Modified:	2005-11-12
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$auth = true;
require('include/header.php');

locale('stardate');

tablebegin($Lang['Calendar']);

if (!$date = (int)getvar('date')) $date = $stardate;

$day = $stardate % 12;
$week = $stardate % 72;
$month = $stardate % 288;
$year = $stardate % 3456;

$y = $date / 12;
$y = ($y -= ($d = $y % 6)) / 6;
$y = ($y -= ($w = $y % 4)) / 4;
$y = round(($y -= ($m = $y % 12)) / 12);

$d += $w * 6;
$d++;
$w++;
$q=floor($m/3);
$m++;
$y++;

echo "<h3>${Lang['UGC']}</h3>";

echo '<form action="stardate.php" method="GET">';
echo '<table>';
echo '<tr><td><b>' . $Lang['StarDate'] . '</b>:</td><td>&nbsp;</td><td><input type="text" maxlength="10" name="date" value="' . $date . '" /></td><td>&nbsp;</td><td><input type="submit" value="' . $Lang['Show'] . '" /></td></tr>';
echo '</table>';

subbegin();

echo '<table id="calendar">';

echo '<tr class="calendar2"><td><b>' . $Lang['CalendarT'][1] . '</b></td>';
echo '<td><b>' . $Lang['CalendarT'][2] . '</b></td>';
echo '<td colspan="6"><b>' . $Lang['CalendarT'][0] . ' 1</b></td>';
echo '<td colspan="6"><b>' . $Lang['CalendarT'][0] . ' 2</b></td>';
echo '<td colspan="6"><b>' . $Lang['CalendarT'][0] . ' 3</b></td>';
echo '<td colspan="6"><b>' . $Lang['CalendarT'][0] . ' 4</b></td>';
echo '</tr>';


for ($j = 0; $j < 12; $j++) {
	$b = $j == $m - 1;
	echo '<tr' . ($b ? ' class="calendar1"' : '') . '>';
	echo '<td><font class="result">' . ($b ? '<b>' : '') . $Lang['CalendarM'][$j] . ($b ? '</b>' : '') . "</font></td>";
	if (! ($j % 3)) {
		echo '<td rowspan="3"' . ($q == round(($j + 1) / 3) ? ' class="calendar1"' : '') . '><font class="capacity"><i>' . ($Lang['CalendarQ'][$j / 3]) . '</i></font></td>';
	}
	for ($i = 1; $i <= 24; $i++) {
		$s = ($y - 1) * 3456 + $j * 288 + ($i - 1) * 12;
		$t = date("Y-m-d H:i", $s * $thicklength + $begining);

		$c = $b ? '' : ' class="calendar' . ((($i - 1) / 6) % 2 ? 2 : 3) . '"';
		
		echo "<td$c><a href=\"stardate.php?date=$s\" onmouseover=\"self.status='$t $s'; return true\" onmouseout=\"self.status=''; return true\"";
		echo $b && $i == $d ? ' class="minus"><b>' : '>';
		echo $i;
		echo $b && $i == $d ? '</b>' : '';
		echo '</a></td>';
	}
	echo "</tr>";
}

echo '<tr><td><b>' . $Lang['Year'] . '</b>: <font class="plus">' . div($y) . '</font></td>';
echo '<td style="border: 0px">&nbsp;</td>';
for ($i = 0; $i < 4; $i++) {
	$c = ($i % 2) ? 2 : 3;
	echo '<td colspan=6 class="calendar' . $c . '"><font class="minus"><i>' . ($i == $w - 1 ? '<b>' : '') . $Lang['CalendarW'][$i] . ($i == $w - 1 ? '</b>' : '') . '</i></font></td>';
}
echo "</tr>";


echo "</table>";

subend();

tableend('Galaxy Forces', 500);

require('include/footer.php');
