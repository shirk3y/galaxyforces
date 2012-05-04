<?php

// ===========================================================================
// Healer {healer.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.1
//	Modified:	2005-11-13
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'control';
$auth = true;

require('include/header.php');

$back = getvar('back');
$page = abs(getvar('page'));

if (!$back) $back = 'control.php';
$back .= "?rid=$rid";
if ($page) $back .= "&page=$page";

$pagename = $Lang['GalacticHospital'];

// ===========================================================================
// ERROR PAGE
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<h3>${Lang['ErrorProblems']}</h3><font class=\"error\">$errors</font><br />";
	echo "<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("$pagename");
}

elseif (checkplace('healer')) {

	tablebegin($pagename, 500);

	if ($action == 'heal') {

?>		<br /><font class="h3"><?php echo $Lang['FullyHealed']; ?><br />
		<br />
<?php
		sound('healed');
	}
	else {
		subbegin();

?>		<center>
		<font class="h3"><?php echo $Lang['HospitalWelcome']; ?></font><br />
		<br />
		<font class="result"><?php echo $Lang['HospitalDescription']; ?></font><br />
		<br />
<?php
		if ($Player['hp'] < $Player['hpmax']) {
			$cost = round($places['healer']['parameters'] * ($Player['hpmax'] - $Player['hp']) * 20);

?>		<b><?php echo $Lang['HealCost']; ?></b>: <font class="minus"><?php echo div($cost); ?></font> [!]<br />
		<br />
		<a href="healer.php?action=heal"><?php echo $Lang['Heal']; ?> &gt;&gt</a><br />
<?php
			sound('healer');
		}
		else {
			sound('healer1');
			echo "\t${Lang['YouDontNeedHealing']}<br /><br />\n";
		}

		subbreak();
		tableimg("images/bw.gif", 168, 168, 'gallery/places/healer.jpg', 160, 160, '', 'right');
		subend();
	}
	tableend("<a href=\"$back\">${Lang['GoBack']} &gt;&gt;</a>");
}
else {
	tablebegin($Lang['GalacticHospital'], 500);
	echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";
	tableend("<a href=\"$back\">${Lang['GoBack']} &gt;&gt;</a>");
}

require('include/footer.php');
