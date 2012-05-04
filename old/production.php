<?php

// ===========================================================================
// Production {production.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.3
//	Modified:	2005-11-02
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'production';
$auth = true;

require('include/header.php');

if (isset($Colony) && $Colony) {

	if ($action == 'product') {
		if ($errors) {
			tablebegin('<font color="red" class="error">' . $Lang['Error'] . '!</font>', '400');

?>	<br />
	<b><?php echo $Lang['ErrorCantProduct']; ?></b><br />
	<br />
	<font color="red" class="error"><?php echo $errors; ?></font>
	<br />
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
		 	tableend($Lang['Production']);
		}
		else {
			tablebegin($Lang['Production'], 500);

?>		<br /><font class="h3"><?php echo $Lang['ProductS']; ?></font><br />
		<br />

		<table align="center" cellspacing="0" cellpadding="0" border="0">
<?php
 			if ($Cost['credits']) echo "\t<tr><td><b>${Lang['Credits']}</b>:</td><td>&nbsp;</td><td class=\"result\">".div($Cost['credits'])."</td></tr>\n";
 			if ($Cost['energy']) echo "\t<tr><td><b>${Lang['Energy']}</b>:</td><td>&nbsp;</td><td>".div($Cost['energy'])."</td></tr>\n";
 			if ($Cost['silicon']) echo "\t<tr><td><b>${Lang['Silicon']}</b>:</td><td>&nbsp;</td><td>".div($Cost['silicon'])."</td></tr>\n";
 			if ($Cost['metal']) echo "\t<tr><td><b>${Lang['Metal']}</b>:</td><td>&nbsp;</td><td>".div($Cost['metal'])."</td></tr>\n";
 			if ($Cost['uran']) echo "\t<tr><td><b>${Lang['Uran']}</b>:</td><td>&nbsp;</td><td>".div($Cost['uran'])."</td></tr>\n";
 			if ($Cost['plutonium']) echo "\t<tr><td><b>${Lang['Plutonium']}</b>:</td><td>&nbsp;</td><td>".div($Cost['plutonium'])."</td></tr>\n";
 			if ($Cost['deuterium']) echo "\t<tr><td><b>${Lang['Deuterium']}</b>:</td><td>&nbsp;</td><td>".div($Cost['deuterium'])."</td></tr>\n";
 			if ($Cost['food']) echo "\t<tr><td><b>${Lang['Food']}</b>:</td><td>&nbsp;</td><td>".div($Cost['food'])."</td></tr>\n";
 			if ($Cost['crystals']) echo "\t<tr><td><b>${Lang['Crystals']}</b>:</td><td>&nbsp;</td><td>".div($Cost['crystals'])."</td></tr>\n";

?>		</table>
		<br />
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
		<br />
<?php
			tableend($Lang['Production']);
		}
	}
	else {
		tablebegin($Lang['Production']);

		if ($Productions) {

?>	<br /><font class="h3"><?php echo $Lang['CurrentProductions']; ?><br />
	<br />

	<script>
	<!--

	function ask($url) {
		if (confirm('<?php echo $Lang['AreYouSure?']; ?>')) location.href = $url;
	}

	//-->
	</script>

	<table cellspacing="0" cellpadding="0" border="0" align="center">
<?php
			foreach($Productions as $s) {

?>	<tr>
	<td><font class="result"><b><?php echo $ProductionsAvailable[$s['name']]['name']; ?></b></font></td>
	<td>&nbsp; &nbsp;</td>
	<td><b><?php echo $Lang['Amount']; ?></b>: <font class="plus"><?php echo div($s['amount']); ?></font></td>
	<td>&nbsp; &nbsp;</td>
	<td><?php echo $Lang['FullETA']; ?>: <font class="value"><?php echo eta($s['end'] - $stardate); ?></font><?php if ($s['end'] - $stardate > 0) echo ' ('.round(100 * ($stardate - $s['begin']) / $s['time']).'%)'; ?></td>
	<td>&nbsp; &nbsp;</td>
	<td><a href="javascript:ask('<?php echo $_SERVER['PHP_SELF']; ?>?action=cancelproduction&id=<?php echo $s['id']; ?>')" class="delete"><?php echo $Lang['ProductC']; ?> &gt;&gt;</a></td>
	</tr>
<?php
			}

?>	</table>
	<br />
<?php
			tablebreak();
		}

		$count = 0;

		if ($ProductionsAvailable) {

?>	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<?php
			foreach ($ProductionsAvailable as $s) {
				if (!isset($type)) $type = $s['type'];
				elseif ($s['type'] != $type) {

?>	<tr height="8"><td>&nbsp;</td></tr>
	</table>
<?php
				tablebreak();

?>	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<?php
				$type = $s['type'];
			}

?>	<tr height="8"><td>&nbsp;</td></tr>
	<tr height="72" valign="middle">
	<td width="12">&nbsp;</td>
	<td width="72">
		<table background="images/pw.gif" width="72" height="72" cellspacing="0" cellpadding="0" border="0" align="center">
		<tr height="72" valign="center">
		<td><center><a href="description.php?type=unit&subject=<?php echo $s['id']; ?>&back=<?php echo $_SERVER['PHP_SELF']; ?>"><img src="gallery/units/icons/<?php echo $s['id']; ?>.jpg" alt="" width="64" height="64" hspace="0" vspace="0" border="0"></a></center></td>
		</tr>
		</table>
	</td>
	<td width="8">&nbsp;</td>
	<td align="left">
		<font class="result"><b><?php echo $s['name']; ?></b></font><br />
		<?php echo $s['description']; ?><br />
	</td>
	<td width="8">&nbsp;</td>
	<td width="80" align="left"><b><?php echo $Lang['Amount']; ?></b>: <font class="capacity"><?php echo div($Colony[$s['id']] + 0); ?></td>
	<td width="8">&nbsp;</td>
	<td width="100" align="left">
<?php
			if (isset($s['cost'])) echo "\t\t<b>[!]</b>&nbsp;<font class=\"work\">" . div($s['credits'] + $s['cost']) . "</font><br />\n";
			elseif (isset($s['credits'])) echo "\t\t<b>[!]</b>&nbsp;<font class=\"result\">" . div($s['credits']) . "</font><br />\n";

			if (@$s['energy']) echo "\t\t<b>[E]</b>&nbsp;" . div($s['energy']) . "<br />\n";
			if (@$s['silicon']) echo "\t\t<b>[S]</b>&nbsp;".div($s['silicon'])."<br />\n";
			if (@$s['metal']) echo "\t\t<b>[M]</b>&nbsp;" . div($s['metal']) . "<br />\n";
			if (@$s['uran']) echo "\t\t<b>[U]</b>&nbsp;" . div($s['uran']) . "<br />\n";
			if (@$s['plutonium']) echo "\t\t<b>[P]</b>&nbsp;".div($s['plutonium'])."<br />\n";
			if (@$s['deuterium']) echo "\t\t<b>[D]</b>&nbsp;".div($s['deuterium'])."<br />\n";
			if (@$s['crystals']) echo "\t\t<b>[C]</b>&nbsp;" . div($s['crystals']) . "<br />\n";

?>	</td>
	<td width="4">&nbsp;</td>
	<td width="80" align="center">
<?php
	if ($Colony['military']) {
		echo '[ <font class="plus">'.eta(1 + round((50 / $Colony['military']) * ($s['work'] / log($Colony['workforce']) / ($Colony['factory'] + $Colony['tron'])))).'</b></font> ]<br /><br />';
		echo '[ <font class="work">'.eta(1 + round((50 / $Colony['military']) * (100 * $s['work'] / log($Colony['workforce']) / ($Colony['factory'] + $Colony['tron'])))).'</b></font> ]<br />';
	}
	else echo $Lang['NotAvailable'];
	
?>	</td>
	<td width="4">&nbsp;</td>
	<td width="120" align="right">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>" method="POST">
		<input type="hidden" name="action" value="product" />
		<input type="hidden" name="name" value="<?php echo $s['id']; ?>" />
		<input size="4" maxlength="8" name="amount" value="1" />&nbsp;<input type="submit" value="<?php echo $Lang['Production']; ?>" />
		</form>
	</td>
	<td width="12">&nbsp;</td>
	</tr>
<?php
				$count++;
			}

?>	<tr height="8"><td>&nbsp;</td></tr>
	</table>
<?php
		}
		if ($count) tableend($count . $Lang[' unit(s) available']);
		else tableend($Lang['Production']);
	}
}

require('include/footer.php');
