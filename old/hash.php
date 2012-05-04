<?php

require('include/header.php');

// ===========================================================================
// ERROR
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<h3>${Lang['ErrorProblems']}</h3><font class=\"error\">$errors</font><br />";
	sound('error');
	echo "<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("$pagename");
}

// ===========================================================================
// GENERATE
// ===========================================================================

if ($action == 'generate') {
	tablebegin('Item Generate');

	echo "\t<h3>Example</h3>\n";

	echo "\t<center>\n\t<textarea cols=80 rows=40>\n";
	print_r(generateitem('item'));
	echo "\t</textarea>\n\t</center>\n";

	echo "\t<center><a href=\"${_SERVER['PHP_SELF']}?action=generate\">Refresh&nbsp;&gt;&gt;</a></center>\n";

	echo "\t<br />\n";

	tableend("<a href=\"control.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// DEFAULT
// ===========================================================================

else {
	tablebegin('Default Page', 200);

	echo "\t<h3>Default</h3>\n";

	echo "miesiac: $starmonth<br /><br />";


	for ($i = 1; $i < 50; $i++) {
		echo $i, ' - ', round(1000000 * (Rand(0, 5) / 1000 / (35 + $i))), ' : ', round(1000000 * (Rand(0, 5) / 1000 / (50)));
		echo "<br >";
	}

	echo "\t<center><a href=\"${_SERVER['PHP_SELF']}?action=generate\">Generate&nbsp;&gt;&gt;</a></center>\n";

	echo "\t<br />\n";

	tableend("<a href=\"control.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

require('include/footer.php');
