<?php

$index = 'explore';
$auth = true;

require('include/header.php');

if (isset($Colony) && $Colony) {
	tablebegin($Lang['Explore'], 500);

	if ($action == 'explore') {
		if ($errors) {
?>		<br />
		<font class="error"><?php echo $Lang['ExploreX']; ?></font><br />
		<br />
		<?php echo $errors; ?>
		<br />
		<a href="javascript:history.back(1)"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
		<br />
<?php		}
		else {
?>		<br />
		<b><?php echo $Lang['ExpdSt']; ?></b><br />
		<br />
		<table align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
		<td><?php echo $Lang['Credits']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td><b><?php echo $Exploration['cost']; ?></b></td>
		</tr>
		<tr>
		<td><?php echo $Lang['Energy']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td><b><?php echo $Exploration['energy']; ?></b></td>
		</tr>
		<tr>
		<td><?php echo $Lang['Food']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td><b><?php echo $Exploration['food']; ?></b></td>
		</tr>
		</table>
		<br />
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
		<br />
<?php
		}
	}
	elseif ($Exploration) {
?>	<script>
	<!--

	function ask($url) {
		if (confirm('<?php echo $Lang['AreYouSure?']; ?>')) location.href = $url;
	}

	//-->
	</script>

	<br />
	<?php echo $Lang['ExpL']; ?> <b><?php echo eta($Exploration['end'] - $stardate); ?><br />
	<br />
	<a href="javascript:ask('<?php echo $_SERVER['PHP_SELF']; ?>?action=cancelexpedition')" class="delete"><?php echo $Lang['ExplC']; ?> &gt;&gt;</a><br />
	<br />
<?php
	}
	else {
		subbegin();
?>		<center>
			<b><?php echo $Lang['ExploreM1']; ?></b><br />
			<br />
			<font class="result"><?php echo $Lang['ExploreM2']; ?> <?php echo round(100 * $Planet['explored']) / 100; ?>%.</font><?php if ($Planet['explored'] == 100) echo " <font class=\"warning\">${Lang['ExploreM3']}</font>" ?><br />
		</center>

		<p><?php echo $Lang['ExploreC']; ?><br />
		<br />

		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>" method="POST" name="form">
		<table align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
		<td><b><?php echo $Lang['ExploreT']; ?></b>:</td>
		<td colspan="3">&nbsp; &nbsp;</td>
		<td>
			<select name="type">
<?php		if ($Planet['explored'] < 100) { ?> 			<option value="planet"><?php echo $Lang['planet']; ?></option>
<?php		} ?>			<option value="galaxy"><?php echo $Lang['galaxy']; ?></option>
			</select>
		</td>
		</tr>
		<tr><td colspan="5">&nbsp;</td></tr>
		<tr>
		<td><b><?php echo $Lang['Colonists']; ?></b>:</td>
		<td>&nbsp; &nbsp;</td>
		<td><font class="result"><?php echo $Lang['Available']; ?></font>: <b><?php echo $Colony['colonistsfree']; ?></b></td>
		<td>&nbsp; &nbsp;</td>
		<td><input size="8" maxlength="8" name="colonists" /></td>
		</tr>
		<tr>
		<td><b><?php echo $Lang['Scientists']; ?></b>:</td>
		<td>&nbsp; &nbsp;</td>
		<td><font class="result"><?php echo $Lang['Available']; ?></font>: <b><?php echo $Colony['scientistsfree']; ?></b></td>
		<td>&nbsp; &nbsp;</td>
		<td><input size="8" maxlength="8" name="scientists" /></td>
		</tr>
		<tr>
		<td><b><?php echo $Lang['Soldiers']; ?></b>:</td>
		<td>&nbsp; &nbsp;</td>
		<td><font class="result"><?php echo $Lang['Available']; ?></font>: <b><?php echo $Colony['soldiersfree']; ?></b></td>
		<td>&nbsp; &nbsp;</td>
		<td><input size="8" maxlength="8" name="soldiers" /></td>
		</tr>
		<tr><td colspan="5">&nbsp;</td></tr>
		<tr>
		<td><b><?php echo $Lang['Vessels']; ?></b>:</td>
		<td>&nbsp; &nbsp;</td>
		<td><font class="result"><?php echo $Lang['Available']; ?></font>: <b><?php echo $Colony['vesselsfree']; ?></b></td>
		<td>&nbsp; &nbsp;</td>
		<td><input size="8" maxlength="8" name="vessels" /></td>
		</tr>
		<tr><td colspan="5">&nbsp;</td></tr>
		<tr>
		<td><?php echo $Lang['ExpdT']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td><font class="capacity">(<?php echo $Lang['stardays']; ?>)</font></td>
		<td>&nbsp; &nbsp;</td>
		<td>
			<select name="time">
<?php
		for ($i = 1; $i <= 24; $i++) echo "<option name=\"$i\">$i</option>";
		echo "<option name=\"30\">30</option>";
		echo "<option name=\"30\">40</option>";
		echo "<option name=\"30\">50</option>";
		echo "<option name=\"30\">60</option>";
		echo "<option name=\"30\">90</option>";
		echo "<option name=\"30\">120</option>";

?>			</select>
		</td>
		</tr>
		<tr><td colspan="5">&nbsp;</td></tr>
		<tr><td colspan="5"><center><input type="hidden" name="action" value="explore" /><input type="submit" value="<?php echo $Lang['ExpdS']; ?>" /></center></td></tr>
		</table>
		</form>

		<script>
		<!--
			document.form.type.focus();
		//-->
		</script>
<?php
		subend();
	}
}
else {
?>	<br />
	<b><?php echo $Lang['HaveNoColony']; ?></b><br />
	<br />
	<a href="control.php"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
}

tableend($Lang['Explore']);

require('include/footer.php');
