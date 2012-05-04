<?php

$index = 'documentation';

require('include/header.php');

require('include/doc.php');

$articles = readarticles('doc', false);

tablebegin($Lang['Documentation'], 500);

echo "<br />";

if ($articles) {
	$i = 0;
	
	echo "\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";

	foreach ($articles as $article) {
		if (! (++$i % 2)) $id = ' id="div"'; else $id = '';

		echo "\t<tr height=\"24\" valign=\"middle\"$id><td width=\"8\">&nbsp;</td>";
		echo "<td><b>${Lang['Title']}</b>: <font class=\"result\"><b>${article['title']}</b></font>";
		if ($article['author']) echo ", <b>${Lang['Author']}</b>: <font class=\"capacity\">${article['author']}</font>";
		if ($article['language']) echo ", <b>${Lang['Language']}</b>: ${article['language']}";
		echo "<br />";
		if ($article['mimetype']) echo "<b>${Lang['Type']}</b>: <font class=\"work\">${article['mimetype']}</font>, ";
		echo "<b>${Lang['Size']}</b>: <font class=\"minus\">" . number_format($article['size'], 0, '', ' ') . "</font>";
		echo "</td>";
		echo "<td align=\"right\"><a href=\"${article['file']}\">${Lang['Download']}&nbsp;&gt;&gt;</a></td>";
		echo "<td width=\"8\">&nbsp;</td></tr><tr><td>&nbsp;</td></tr>\n";
	}
	
	echo "\t</table>\n";
}
else echo '<h3>'.$Lang['NoFiles']."!</h3><br />";

tableend($Lang['Documentation']);

require('include/footer.php');
