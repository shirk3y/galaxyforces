<?php

// ===========================================================================
// Arena {arena.php}
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

$page = (int)getvar('page');
$category = (int)getvar('page');

$pagecount = 50;

// ===========================================================================
// FIGHT
// ===========================================================================

if (isset($fight) && $fight) {
	tablebegin($Lang['BattleArena'], 500);

	if ($n = ($a = count($attackers)) > ($d = count($defenders)) ? $a : $d) {
		subbegin();

		echo "\t\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";

		for ($i = 0; $i < $n; $i++) {
			echo "\t\t<tr height=\"72\">\n\t\t<td width=\"72\">\n";

			if ($i < $a) {
				$t = $attackers[$i];
				tableimg('images/pw.gif', 72, 72, ($t['avatar'] ? $t['avatar'] : 'gallery/avatars/icons/noavatar.gif'), 64, 64, "whois.php?name=${t['login']}");
				echo "\t\t<td align=\"left\" width=\"8\">&nbsp;</td>\n\t\t<td align=\"left\">";
				echo "\t\t<font class=\"plus\"><b>${t['login']}</b></font><br />\n";
				echo "\t\t<font class=\"result\">${t['clan']}</font><br />\n";
				echo "\t\t<b>${Lang['Level']}</b>: <font class=\"result\">${t['level']}</font><br />\n";
				echo "\t\t<b>${Lang['Strength']}</b>: <font class=\"result\">" . div($t['strength']) . "</font>, <b>${Lang['Agility']}</b>: <font class=\"work\">" . div($t['agility']) . "</font><br />\n";
				echo "\t\t</td>\n";
			}
			else echo "\t\t</td>\n\t\t<td colspan=\"2\" align=\"left\">&nbsp;</td>\n";

			echo "\t\t<td width=\"8\">&nbsp;</td>\n\t\t<td align=\"72\">\n";

			if ($i < $d) {
				$t = $defenders[$i];
				echo "\t\t<td align=\"right\">\n";
				echo "\t\t<font class=\"capacity\"><b>${t['login']}</b></font><br />\n";
				echo "\t\t<font class=\"result\">${t['clan']}</font><br />\n";
				echo "\t\t<b>${Lang['Level']}</b>: <font class=\"result\">${t['level']}</font><br />\n";
				echo "\t\t<b>${Lang['Strength']}</b>: <font class=\"result\">" . div($t['strength']) . "</font>, <b>${Lang['Agility']}</b>: <font class=\"work\">" . div($t['agility']) . "</font><br />\n";
				echo "\t\t</td>\n\t\t<td align=\"left\" width=\"8\">&nbsp;</td>\n\t\t<td width=\"72\">\n";
				tableimg('images/pw.gif', 72, 72, ($defenders[$i]['avatar'] ? $defenders[$i]['avatar'] : 'gallery/avatars/icons/noavatar.gif'), 64, 64, "whois.php?name=${t['login']}");
			}
			else echo "\t\t</td>\n\t\t<td>&nbsp;</td>\n";

			echo "\t\t</td>\n\t\t</tr>\n";

			if ($i < $n - 1) echo "\t\t<tr><td colspan=\"7\">&nbsp;</td></tr>\n";
		}
		echo "\t\t</table>";

		subend();
		tablebreak();
	}

?>	<br />
	<b><?php echo $Lang['ArenaFight']; ?></b><br />
	<br />
	<?php echo $fight; ?></font><br />
<?php
	tablebreak();

	if (($Player['hp'] < $Player['hpmax']) && checkplace('healer')) {
		echo "\t<br />\n";
		echo "\t<a href=\"healer.php?action=heal&back=arena&page=$page\">${Lang['GalacticHospital']}&nbsp;&gt;&gt;</a><br />\n";
	}

	echo "\t<br />\n";
	echo "\t<a href=\"arena.php?rid=$rid&page=$page\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br />\n";
	echo "\t<br />\n";

	if ($winner) sound('fightwin');
	else sound('fightlost');

	tableend($Lang['BattleArena']);
}

// ===========================================================================
// ERRORS
// ===========================================================================

elseif (isset($errors) && $errors) {
	tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');

?>	<br />
	<b><?php echo $Lang['ErrorCantFight']; ?></b><br />
	<br />
	<font class="error"><?php echo $errors; ?></font>
	<br />
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
	tableend($Lang['BattleArena']);
	sound('error');
}

// ===========================================================================
// ARENA
// ===========================================================================

else {
	tablebegin($Lang['BattleArena'], 500);

	$s = '';

	if (checkplace('arena')) {

		subbegin('images/table-b2.jpg');

?>		<table background="images/bw.gif" width="168" height="168" cellspacing="0" cellpadding="0" hspace="4" border="0" align="right">
		<tr height="168" valign="center">
		<td><center><img src="gallery/places/arena.jpg" alt="" width="160" height="160" hspace="0" vspace="0" border="0"></center></td>
		</tr>
		</table>

		<center>
		<font class="h3"><?php echo $Lang['ArenaWelcome']; ?></font><br />
		<br />
		<font class="result"><?php echo $Lang['ArenaDescription']; ?></font><br />
		<br />

		<form action="arena.php" method="POST" name="form">
		<?php echo $Lang['Login']; ?>:
		<input type="hidden" name="action" value="fight" /><input type="text" size="12" name="name" />
		<input type="submit" value="<?php echo $Lang['Fight']; ?>" />
		</form>
		</center>

<?php if (! $page) sound('arena'); ?>

		<script>
		<!--
			document.form.name.focus();
		//-->
		</script>
<?php
		subend();

		$db->query("SELECT `id` FROM `{$prefix}users` WHERE (`planet`='$planet' AND `destination`='' or `destination`='$planet') AND (`thicks`<$stardate-20 OR `hp`>0.1*`hpmax`) AND `login`<>'$login';");
		$max = $db->numrows();

		if ($page > ($m = floor($max / $pagecount))) $page = $m;
		$l = $page * $pagecount;

		$db->query("SELECT `destination`,`login`,`clan`,`level`,`online` FROM `{$prefix}users` WHERE (`planet` = '$planet' AND `destination`='' or `destination`='$planet') AND (`thicks`<$stardate-20 OR `hp`>0.1*`hpmax`) AND `login`<>'$login' ORDER BY `level` DESC,`login` ASC LIMIT $l,$pagecount;");

		if (! $db->numrows()) {

?>		<font class="minus"><?php echo $Lang['ArenaNoone']; ?></font><br />
		<br />
<?php
		}
		else {
			$online = date('YmdHis', time() - 300);

			tablebreak();

?>		<br />
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
<?php
			$i = 0;
			while ($t = $db->fetchrow()) {
				if (! ($i++ % 2)) $id = ' id="div"';
				else $id = '';

?>		<tr height="24" valign="middle"<?php echo $id; ?>>
		<td align="center"><a href="whois.php?name=<?php echo $t['login']; ?>"<?php echo $t['destination'] ? ' class="work"' : ''; ?>><?php echo $t['login']; ?></a></td>
		<td>&nbsp; &nbsp;</td>
		<td align="center"><?php echo $t['clan'] ? $t['clan'] : '&nbsp;'; ?></td>
		<td>&nbsp; &nbsp;</td>
		<td align="center"><font class="result"><?php echo $t['level']; ?></td>
		<td>&nbsp; &nbsp;</td>
		<td align="center"><?php if ($t['online'] > $online) echo '<font class="warning">online</font>'; else echo '&nbsp; &nbsp;'; ?></td>
		<td>&nbsp; &nbsp;</td>
		<td>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<input type="hidden" name="action" value="fight" />
			<input type="hidden" name="name" value="<?php echo $t['login']; ?>" />
			<input type="submit" value="<?php echo $Lang['Fight']; ?>" />
			</form>
		</td>
		</tr>
<?php
			}

?>		</table>
		<br />
<?php
			$n = $page;

			$s = ($n ? "<a href=\"arena.php?category=$category&page=0\">" : '').'&lt;&lt;&nbsp;'.$Lang['Begin'].($n ? '</a>' : '').' &nbsp; ';
			$s .= ($n ? "<a href=\"arena.php?category=$category&page=".($n - 1).'">' : '').'&lt;&lt;&nbsp;'.$Lang['Previous'].($n ? '</a>' : '').' &nbsp; ';

			$a = $n > 5 ? $n - 5 : 0;
			$b = $n < $m - 5 ? $n + 5 : $m;

			if ($a > 0) $s .= '... ';

			for ($i = $a; $i <= $b; $i++) {
				if ($i != $n) $s .= "<a href=\"arena.php?category=$category&page=$i\">";
				$s .= $i + 1;
				if ($i != $n) $s .= "</a>";
				$s .= ' ';
			}

			if ($b < $m) $s .= ' ...';

			$s .= " &nbsp; ".($n < $m ? "<a href=\"arena.php?category=$category&page=".($n + 1).'">' : '').$Lang['Next'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');
			$s .= ' &nbsp; '.($n < $m ? "<a href=\"arena.php?category=$category&page=$m\">" : '').$Lang['End'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');
		}
	}
	else {
		echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";
		$s = '<a href="control.php">'.$Lang['GoBack'].'&nbsp;&gt;&gt;</a>';
	}

	tableend($s);
}

require('include/footer.php');
