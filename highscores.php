<?php

// ===========================================================================
// Highscores {highscores.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.8
//	Modified:	2005-11-13
//	Author(s):	zoltarx, unk
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'control';
$auth = true;

require("include/header.php");
require("include/functions.php");

$category = getvar('category');
$page = abs(getvar('page'));

$pagecount = 100;

switch ($category) {
	case 'level': $order = "`level` DESC, `score` DESC, `login`"; break;
	case 'leveldesc': $order = "`level`, `score`, `login` DESC"; break;
	case 'voyaged': $order = "`voyaged` DESC,`level` DESC,`score` DESC,`login`"; break;
	case 'voyageddesc': $order = "`voyaged`,`level`,`score`,`login` DESC"; break;
	case 'login': $order = "`login` ASC"; break;
	case 'logindesc': $order = "`login` DESC"; break;
	case 'clan': $order = "`clan`,`login`"; break;
	case 'clandesc': $order = "`clan` DESC,`login` DESC"; break;
	case 'ip': $order = "`ip`, `login`"; break;
	case 'ipdesc': $order = "`ip` DESC, `login` DESC"; break;
	case 'scoredesc': $order = "`score`,`level`, `login` DESC"; break;
	case 'score':
	default: $order = "`score` DESC,`level` DESC,`login`"; break;
}

if ($max = $db->rows("{$prefix}users")) $max--; // administrator account (id=0) is not counted

if ($page > $m = floor($max / $pagecount)) $page = $m;
$l = $page * $pagecount;
$clan = $Player['clan'];

if (!$db->query("SELECT login,clan,level,score,voyaged,ip,lastip FROM ${prefix}users WHERE id>0 ORDER BY $order LIMIT $l,$pagecount;")) {
//if (!$db->query("SELECT ${prefix}users.login,refs,usergroup,clan,level,score,voyaged,ip,lastip, ${prefix}colonies.name AS colony, ${prefix}diplomacy.type FROM ${prefix}users LEFT JOIN ${prefix}colonies ON ${prefix}users.login = ${prefix}colonies.owner LEFT JOIN ${prefix}diplomacy ON (${prefix}diplomacy.clan1 = '$clan' AND ${prefix}diplomacy.clan2 = ${prefix}users.clan) OR (${prefix}diplomacy.clan1 = ${prefix}users.clan AND ${prefix}diplomacy.clan2 = '$clan') WHERE ${prefix}users.id>0 ORDER BY ${prefix}users.$order LIMIT $l,$pagecount;")); {
	echo $errors = '<br /><span class="error">'.$Lang['ErrorQueryFailed'].'!<br />'.mysql_error().'!<br /><br />';
	$s = $Lang['Error'];
}
else {

tablebegin('Top 100 (<font class="result"><b>' . div($max) . '</b></font>)');

echo "\t<br />\n";

echo "\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "\t".'<tr id="header"><td width="12" id="headerl">&nbsp;</td><td width="8">&nbsp;</td><td width="8">&nbsp;</td><td align="left">';

echo '<a'.($category == 'login' ? ' class="result"' : ($category == 'logindesc' ? ' class="minus"' : '')).' href="highscores.php?category='.($category == 'login' ? 'logindesc' : 'login')."&page=$page\" onmouseover=\"self.status='${Lang['ReverseOrder']}'; return true\" onmouseout=\"self.status=''; return true\">${Lang['Login']}</a>";
echo ':</td><td width="8">&nbsp;</td><td align="left">'.$Lang['Colony'].':</td><td width="8">&nbsp;</td><td align="left">';

echo '<a'.($category == 'clan' ? ' class="result"' : ($category == 'clandesc' ? ' class="minus"' : '')).' href="highscores.php?category='.($category == 'clan' ? 'clandesc' : 'clan')."&page=$page\" onMouseOver=\"self.status='${Lang['ReverseOrder']}'; return true\" onMouseOut=\"self.status=''; return true\">${Lang['Clan']}</a>";
echo ':</td><td width="8">&nbsp;</td><td align="center">';

if ($User['usergroup'] == 'wheel' || $User['usergroup'] == 'moderators')
{
	echo '<a'.($category == 'ip' ? ' class="result"' : ($category == 'ipdesc' ? ' class="minus"' : '')).' href="highscores.php?category='.($category == 'ip' ? 'ipdesc' : 'ip')."&page=$page\" onMouseOver=\"self.status='${Lang['ReverseOrder']}'; return true\" onMouseOut=\"self.status=''; return true\">IP</a>";
	echo '</td><td width="8">&nbsp;</td><td align="center">';
}

echo '<a'.($category == 'level' ? ' class="result"' : ($category == 'leveldesc' ? ' class="minus"' : '')).' href="highscores.php?category='.($category == 'level' ? 'leveldesc' : 'level')."&page=$page\" onMouseOver=\"self.status='${Lang['ReverseOrder']}'; return true\" onMouseOut=\"self.status=''; return true\">${Lang['Level']}</a>";
echo ':</td><td width=\"8\">&nbsp;</td><td align="center">';

echo '<a'.($category == 'voyaged' ? ' class="result"' : ($category == 'voyageddesc' ? ' class="minus"' : '')).' href="highscores.php?category='.($category == 'voyaged' ? 'voyageddesc' : 'voyaged')."&page=$page\" onMouseOver=\"self.status='${Lang['ReverseOrder']}'; return true\" onMouseOut=\"self.status=''; return true\">${Lang['Voyaged']}</a>";
echo ':</td><td width="8">&nbsp;</td><td align="center">';
echo '<a href="highscores.php?category='.($category == 'score' ? 'scoredesc' : 'score')."&page=$page\" onMouseOver=\"self.status='${Lang['ReverseOrder']}'; return true\" onMouseOut=\"self.status=''; return true\">${Lang['Score']}</a>";
echo ':</td><td width="12" id="headerr">&nbsp;</td></tr>'."\n";

$i = 0;
$tab=array();
while ($t = $db->fetchrow()) $tab[] = $t;

foreach ($tab as $t) {
	$i++;
	//$db->query("SELECT name FROM ${prefix}colonies WHERE owner='{$t['login']}' LIMIT 1;");
	//if ($u = $db->fetchrow()) $colony = $u['name'];
	//else $colony = '';

	if ($i == 11 && ! $page && ! ($category == 'clan' || $category == 'clandesc' || $category == 'login' || $category == 'logindesc')) echo "\t<tr height=\"20\"><td colspan=\"13\">&nbsp;</td></tr>\n";

	if ($t['login'] == $login) $id=' id="here"';
	elseif (! ($i % 2)) $id = ' id="div"';
	else $id = '';

?>	<tr height="24"<?php echo $id; ?>>
	<td></td>
	<td><?php echo $l + $i; ?>.</td>
	<td></td>
	<td><a href="whois.php?name=<?php echo $t['login']; ?>"><?php echo $t['login']; ?></a></td>
	<td></td>
	<td><?php echo @$t['colony']; ?></td>
	<td></td>
	<td align="left"><?php echo $t['clan']; ?></td>
	<td>&nbsp;</td>
<?php

	if ($User['usergroup'] == 'wheel') echo '<td align="center"><a href="http://ripe.net/whois?form_type=simple&full_query_string=&searchtext='.$t['ip'].'">'.$t['ip'].'</a>'.($t['lastip'] && $t['ip'] != $t['lastip'] ? ', <a href="http://ripe.net/whois?form_type=simple&full_query_string=&searchtext='.$t['lastip'].'">'.$t['lastip'].'</a>' : '').'</td><td></td>';
	elseif ($User['usergroup'] == 'moderators') echo '<td align="center">'.ip_camuflage($t['ip']).'</td><td></td>';

?>	<td align="center"><font class="plus"><?php echo $t['level']; ?></font></td>
	<td>&nbsp;</td>
	<td align="center"><font class="capacity"><?php echo str_replace(' ', '&nbsp;', number_format($t['voyaged'], 2, $Lang['DecPoint'], ' ')); ?></font></td>
	<td>&nbsp;</td>
	<td align="center"><font class="result"><?php echo str_replace(' ', '&nbsp;', div($t['score'])); ?></font></td>
	<td>&nbsp;</td>
	</tr>
<?php
}

?>	</table>
	<br />
<?php

$n = $page;

$s = ($n ? "<a href=\"highscores.php?category=$category&page=0\">" : '').'&lt;&lt;&nbsp;'.$Lang['Begin'].($n ? '</a>' : '').' &nbsp; ';
$s .= ($n ? "<a href=\"highscores.php?category=$category&page=".($n - 1).'">' : '').'&lt;&lt;&nbsp;'.$Lang['Previous'].($n ? '</a>' : '').' &nbsp; ';

$a = $n > 5 ? $n - 5 : 0;
$b = $n < $m - 5 ? $n + 5 : $m;

if ($a > 0) $s .= '... ';

for ($i = $a; $i <= $b; $i++) {
	if ($i != $n) $s .= "<a href=\"highscores.php?category=$category&page=$i\">";
	$s .= $i + 1;
	if ($i != $n) $s .= "</a>";
	$s .= ' ';
}

if ($b < $m) $s .= ' ...';

$s .= " &nbsp; ".($n < $m ? "<a href=\"highscores.php?category=$category&page=".($n + 1).'">' : '').$Lang['Next'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');
$s .= ' &nbsp; '.($n < $m ? "<a href=\"highscores.php?category=$category&page=$m\">" : '').$Lang['End'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');

}

tableend($s);

require('include/footer.php');
