<?php

$index = 'attack';
$auth = true;

require('include/header.php');

if (isset($errors) && $errors) {
	tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');

?>		<br />
		<?php echo $Lang['ErrorCantAttack']; ?><br />
		<br />
		<font class="error"><?php echo $errors; ?></font>
		<br />
		<a href="javascript:history.back(1)"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
		<br />
<?php
}
elseif ($action == 'prepare') {

	tablebegin($Lang['Attack'], 500);

	subbegin();

?>		<center>
			<b><?php echo $Lang['AttackM1']; ?></b><br />
			<br />
			<?php echo $Lang['Target']; ?>: <font class="result"><?php echo $name; ?></font>, <?php echo $Lang['Distance']; ?>: <font class="plus"><?php echo round(100 * $distance) / 100; ?></font><br />
		</center>

		<p><?php echo $Lang['AttackCosts']; ?><br />

		<form action="attack.php" method="POST" name="form">
		<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
<?php
	$Robots = array();

	foreach ($Units as $u) {
		if ($u['id'] == 'bx10' || $u['id'] == 'walker') $Robots[] = $u;
		if ($u['type'] == 'fighter' || $u['type'] == 'thief') {

?>		<tr>
			<td><?php echo $u['name']; ?>:</td>
			<td>&nbsp; &nbsp;</td>
			<td><b>[A]</b> <font class="plus"><?php echo $u['attack']; ?></font></td>
			<td>&nbsp; &nbsp;</td>
			<td><b>[D]</b> <font class="capacity"><?php echo $u['damage']; ?></font></td>
			<td>&nbsp; &nbsp;</td>
			<td><b>[C]</b> <font class="result"><?php echo $u['capacity']; ?></font></td>
			<td>&nbsp; &nbsp;</td>
			<td><b>[S]</b> <font class="minus"><?php echo $u['speed']; ?></font></td>
			<td>&nbsp; &nbsp;</td>
			<td><font class="result"><?php echo $Lang['Available']; ?></font>: <b><?php echo strdiv($u['amount']); ?></b></td>
			<td>&nbsp; &nbsp;</td>
			<td align="right"><input size="8" type="text" name="<?php echo $u['id']; ?>" /></td>
		</tr>
<?php
		}
	}
?>		<tr><td colspan="13">&nbsp; &nbsp;</td></tr>
		<tr>
			<td><?php echo $Lang['Soldiers']; ?>:</td>
			<td colspan=9>&nbsp; &nbsp;</td>
			<td><font class="result"><?php echo $Lang['Available']; ?></font>: <b><?php echo $Colony['soldiersfree']; ?></b></td>
			<td>&nbsp; &nbsp;</td>
			<td align="right"><input size="8" type="text" name="soldiers" /></td>
		</tr>
<?php
		if ($Robots) foreach ($Robots as $b) {

?>		<tr>
			<td><?php echo $b['name']; ?>:</td>
			<td colspan=9>&nbsp; &nbsp;</td>
			<td><font class="result"><?php echo $Lang['Available']; ?></font>: <b><?php echo $b['amount']; ?></b></td>
			<td>&nbsp; &nbsp;</td>
			<td align="right"><input size="8" type="text" name="<?php echo $b['id']; ?>" /></td>
		</tr>
<?php
		}

?>		<tr><td colspan=13>&nbsp; &nbsp;</td></tr>
		<tr><td colspan=13 align="center"><?php echo $Lang['Strategy']; ?>:&nbsp; &nbsp;<select name="strategy"><?php for ($i = 0; $i < 2; $i++) { ?><option value="<?php echo $i; ?>"><?php echo $Lang['Strategies'][$i]; ?></option><?php } ?></select></td></tr>
		<tr><td colspan=13>&nbsp; &nbsp;</td></tr>
		<tr><td colspan="13" align="center"><input type="hidden" name="name" value="<?php echo $name; ?>" /><input type="hidden" name="action" value="attack" /><input type="submit" value="<?php echo $Lang['Attack']; ?>" /></td></tr>
		</table>
		</form>

		<script>
		<!--
			document.form.name.focus();
		//-->
		</script>
<?php
	subend();

}
elseif (isset($Colony) && $Colony) {

	$a = 0;
	$d = 0;
	$c = 0;

	if (isset($Units) && $Units)
		foreach ($Units as $u) {
			if (isset($u['attack'])) $a += $u['attack'] * $u['amount'];
			if (isset($u['damage'])) $d += $u['damage'] * $u['amount'];
			$c += $u['amount'];
		}

	tablebegin($Lang['Attack'], 500);

	subbegin();
?>		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
		<tr valign="top">
		<td>
			<b><?php echo $Lang['AttackPower']; ?></b>: <font class="result"><?php echo div($a); ?></font><br />
			<b><?php echo $Lang['DefensiveSystems']; ?></b>: <font class="plus"><?php echo div($Colony['defense'] + $d); ?></font> (<font class="capacity"><?php echo div($Colony['defense']); ?></font> + <font class="result"><?php echo div($d); ?></font>)<br />
		</td>
		<td width="8">&nbsp;</td>
		<td>
			<b><?php echo $Lang['Units']; ?></b>: <font class="plus"><?php echo div($c); ?></font><br />
			<b><?php echo $Lang['Soldiers']; ?></b>: <font class="plus"><?php echo div($Colony['soldiersfree']); ?></font><br />
		</td>
		</tr>
		</table>
<?php
	subend();
	tablebreak();

	$b = FALSE;
	if ($Incoming)
		foreach ($Incoming as $t)
			if ($t['status'] == 1) {
				$b = TRUE;
				break;
			}

	if ($b) {

?>		<br />
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
		<tr id="headerw">
		<td width="12">&nbsp;</td>
		<td align="left"><?php echo $Lang['Attacker']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td align="center"><?php echo $Lang['Units']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td align="center"><?php echo $Lang['Power']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td align="center">ETA:</td>
		<td width="12">&nbsp;</td>
		</tr>
<?php
		foreach ($Incoming as $t) {

?>		<tr height="24">
		<td>&nbsp;</td>
		<td><a href="whois.php?name=<?php echo $t['login']; ?>"><?php echo $t['login']; ?></a></td>
		<td>&nbsp;</td>
		<td align="center"><font class="capacity"><?php echo div($t['units']); ?></font></td>
		<td>&nbsp;</td>
		<td align="center"><font class="result"><?php echo div($t['power']); ?></td>
		<td>&nbsp;</td>
		<td align="center">[&nbsp;<font class="plus"><?php echo eta($t['end'] - $stardate); ?></font>&nbsp;]</td>
		<td>&nbsp;</td>
		</tr>
<?php
		}

?>		</table>
		<br />
<?php
		tablebreak();
	}

	if ($Attacks) {
?>		<br />
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
		<tr id="header">
		<td width="12">&nbsp;</td>
		<td align="left"><?php echo $Lang['Target']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td align="left"><?php echo $Lang['Strategy']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td align="center"><?php echo $Lang['Units']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td align="center"><?php echo $Lang['Power']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td align="center"><?php echo $Lang['Status']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td align="center">ETA:</td>
		<td>&nbsp; &nbsp;</td>
		<td align="center">&nbsp;</td>
		<td width="12">&nbsp;</td>
		</tr>
<?php
		foreach ($Attacks as $t) {

?>		<tr height="24">
		<td>&nbsp;</td>
		<td><?php echo $t['target']; ?></td>
		<td>&nbsp;</td>
		<td align="left"><?php echo $Lang['Strategies'][$t['strategy']]; ?></td>
		<td>&nbsp;</td>
		<td align="center"><font class="capacity"><?php echo div($t['units']); ?></font></td>
		<td>&nbsp;</td>
		<td align="center"><font class="result"><?php echo div($t['power']); ?></td>
		<td>&nbsp;</td>
		<td align="center"><?php echo $t['communicationlost'] ? $Lang['Unknown'] : $Lang['AST'][$t['status']]; ?></td>
		<td>&nbsp;</td>
		<td align="center">[&nbsp;<font class="plus"><?php echo eta($t['end'] - $stardate); ?></font>&nbsp;]</td>
		<td>&nbsp;</td>
		<td align="right"><?php if ($t['communicationlost']) echo '<font class="error">'.$Lang['CommunicationLost'].'</font>'; elseif ($t['status'] < 2) { ?><a class="delete" href="attack.php?action=cancelattack&id=<?php echo $t['id']; ?>"><?php echo $Lang['Cancel']; ?>&nbsp;&gt;&gt</a><?php } else echo '&nbsp;'; ?></td>
		<td>&nbsp;</td>
		</tr>
<?php
		}

?>		</table>
		<br />
<?php
		tablebreak();
	}

	if (TRUE) {

		subbegin();
// 		<center><b><?php echo $Lang['Search4AU']</b></center><br />; ?>
		<form action="attack.php" method="POST" name="form">
		<table align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><?php echo $Lang['Colony name']; ?>:</td>
			<td>&nbsp; &nbsp;
			<td><input type="text" value="<?php echo $name; ?>" name="name" /></td>
			<td>&nbsp; &nbsp;
			<td align="center"><input type="hidden" name="action" value="prepare" /><input type="submit" value="<?php echo $Lang['Attack']; ?>" /></td>
		</tr>
		</table>
		</form>

		<script>
		<!--
			document.form.name.focus();
		//-->
		</script>
<?php
		subend();
	}
	
	if ($Colony['tron'] > 1 || $Colony['militarytechnology']) {
		tablebreak();
		echo '<br /><a href="simulator.php">'.$Lang['Simulator'].'&nbsp;&gt;&gt;</a><br />';
		echo '<br />';
	}
}
else {
?>		<br />
		<b><?php echo $Lang['NotAvailable']; ?></b><br />
		<br />
		<a href="control.php"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
		<br />
<?php
}

tableend($Lang['Attack']);

require('include/footer.php');
