<?php

$index = 'news';

require('include/header.php');

locale('website/news');

tablebegin('Galaxy Forces', 500);

$db->query("SELECT * FROM `${prefix}news` WHERE `locale`='' OR `locale`='${language}' ORDER BY `timestamp` DESC LIMIT 0, 3;");

if ($db->numrows()) {
	echo "\t<h3>${Lang['News']}</h3>\n\n";
	echo "\t<table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
	while ($m = $db->fetchrow()) {
		$m['date'] = timestampdate($m['timestamp']);
		$m['time'] = timestamptime($m['timestamp']);
		echo "\t<tr valign=\"top\">\n\t";
		echo "<td width=\"12\">&nbsp;</td>";
		echo "<td width=\"80\"><font class=\"plus\">${m['date']}</font><br /><font class=\"result\">${m['time']}</font><br /><font class=\"capacity\">${m['from']}</font><br /></td>";
		echo "<td width=\"16\">&nbsp;</td><td><p />${m['message']}<br /></td><td width=\"12\">&nbsp;</td>";
		echo "\n\t</tr>\n\t<tr><td colspan=\"5\">&nbsp;</td></tr>\n";
	}
	echo "\t</table>\n";

	tablebreak();
}

if ($changelog=file_get_contents("CHANGES.txt")) {
	$pattern='/([0-9\-]+)\s*\[\s*([0-9\.]+)\s*\]/s';
	preg_match($pattern, $changelog, $match, PREG_OFFSET_CAPTURE);
	$split=preg_split($pattern, $changelog);

	$version=@$match[2][0];
	$date=@$match[1][0];
	$log=trim(@$split[1]);
	
	if ($log && $version && $date) {

		echo "\t<h3>${Lang['Changes']}</h3>\n\n";
		echo "\t<table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr valign=\"top\"><td width=\"12\">&nbsp;</td>\n";
		echo "\t<td width=\"80\"><font class=\"result\">$date</font><br /><font class=\"minus\">$version</font></td>\n";
		echo "\t<td width=\"16\">&nbsp;</td><td><p />";

		foreach (explode("\n", $log) as $line) {
			if (false!==$p=strpos($line, ":")) 
				$line = '<font class="work">'.substr($line, 0, ++$p).'</font>'.substr($line, $p);
			echo $line.'<br />';				
		}

		echo "</td>\n\t<td width=\"16\">&nbsp;</td></tr></table>\n\t<br />\n";

		tablebreak();
	}
}

echo "\t<br /><a href=\"CHANGES.txt\">".$Lang['Full list of changes']." &gt;&gt;</a><br /><br />\n";

tableend('Galaxy Forces', 500);

require('include/footer.php');
