<?php

$index = 'documentation';

require('include/header.php');

require('include/doc.php');

tablebegin($Lang['Download'], 500);

echo "\t<br /><font class=\"h3\">${Lang['Sources']}</h3><br />";

$files = readfiles('src', false);

// ---------------------------------------------------------------------------
// FILES
// ---------------------------------------------------------------------------

if ($files) {
	echo "<br />";

	echo "\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";

	$i = 0;
	foreach ($files as $file) {
		if (! (++$i % 2)) $id = ' id="div"'; else $id = '';

		echo "\t<tr height=\"24\" valign=\"middle\"$id><td width=\"8\">&nbsp;</td>";
		echo "<td><font class=\"result\">" . substr($file, strrpos($file, '/') + 1) . "</font></td>";
		echo "<td width=\"8\">&nbsp;</td><td align=\"right\"><a href=\"${file}\">${Lang['Download']}&nbsp;&gt;&gt;</a></td>";
		echo "<td width=\"8\">&nbsp;</td></tr>\n";
	}

	echo "\t</table>\n";
	echo "<br />";
}

// ---------------------------------------------------------------------------
// EMPTY PAGE
// ---------------------------------------------------------------------------

else {
	echo "<h3>Empty Page</h3>If you think this is a problem, please <a href=\"mailto:${Config['Administrator']}\">send</a> a bug report!<br /><br />";
}

tablebreak();

echo "\t<br /><font class=\"h3\">${Lang['Stuff']}</h3><br />";

$files = readfiles('src/stuff', false);

// ---------------------------------------------------------------------------
// FILES
// ---------------------------------------------------------------------------

if ($files) {
	echo "<br />";

	echo "\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";

	$i = 0;
	foreach ($files as $file) {
		if (! (++$i % 2)) $id = ' id="div"'; else $id = '';

		echo "\t<tr height=\"24\" valign=\"middle\"$id><td width=\"8\">&nbsp;</td>";
		echo "<td><font class=\"result\">" . substr($file, strrpos($file, '/') + 1) . "</font></td>";
		echo "<td width=\"8\">&nbsp;</td><td align=\"right\"><a href=\"${file}\">${Lang['Download']}&nbsp;&gt;&gt;</a></td>";
		echo "<td width=\"8\">&nbsp;</td></tr>\n";
	}

	echo "\t</table>\n";
	echo "<br />";
}

tableend($Lang['Download']);



require('include/footer.php');
