<?php

$index = 'control';
$auth = true;

require('include/header.php');

$pagename = $Lang['GalacticBank'];

$view = getvar('view');

// ===========================================================================
// ERROR
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<h3>${Lang['ErrorProblems']}</h3><font class=\"error\">$errors</font><br />";
	echo "<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("$pagename");
	sound('error');
}

// ===========================================================================
// RESULTS
// ===========================================================================

elseif ($result) {
	tablebegin("$pagename", 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("$pagename");
	sound('thankyou');
}

// ===========================================================================
// DEFAULT
// ===========================================================================

elseif (checkplace('bank')) {

	tablebegin("$pagename", 500);

	if ($view == 'list') {
		subbegin('images/table-b2.jpg');
		tableimg('images/bw.gif', 168, 168, "gallery/places/bank.jpg", 160, 160, '', 'right');
		echo "\t<center><font class=\"h3\">${Lang['BanksList']}</font><br /><br />\n";
		$db->query("SELECT * FROM `${prefix}places` WHERE type='bank';");
		echo "\t<table>\n";
		while ($t = $db->fetchrow()) echo "<tr><td><a href=\"galaxy.php?object=${t['position']}\">" . strcap($t['position']) . "</a></td><td width=\"12\">&nbsp;</td><td><b>${Lang['Limit']}</b>: " . div($t['parameters']) . "</td></tr>";
		echo "\t</table>\n";
		subend();
	}
	elseif (($view == 'transfer')) {
		subbegin('images/table-b2.jpg');
		tableimg('images/bw.gif', 168, 168, "gallery/places/bank.jpg", 160, 160, '', 'right');

		echo "\t<center><font class=\"h3\">${Lang['BankTransfer']}</font><br /><br />\n";

		if (($credit = equipmentparameters('creditcard')) > 0) {
			if (($tax = log($place['parameters'] / 1000) - log($credit / 10000)) < 0.5) $tax = 0.5;
			$tax = number_format($tax, 1, $Lang['DecPoint'], ' ');

			echo "\t{$Lang['Tax']}: <font class=\"minus\">$tax</font> %<br /><br />\n";
			
?>		<form action="bank.php" method="POST">
		<input type="hidden" name="action" value="banktransfer" />
		<table>
		<tr><td><?php echo $Lang['Login']; ?>:</td><td>&nbsp;</td><td><input type="text" name="name" /></td></tr>
		<tr><td><?php echo $Lang['Credits']; ?>:</td><td></td><td><input type="text" name="amount" /></td></tr>
		</table>
		<br />
		<input type="submit" value="<?php echo $Lang['BankTransfer']; ?>" />
		</form>
<?php
		}
		else echo '<font class="error">'.$Lang['NoCC'].'</font><br /><br />'.$Lang['NotAvailable']."<br />\n";
		
		subend();
	}
	elseif (($view == 'statistics') && ($Player['level'] >= 10)) {
		echo "\t<br /><font class=\"h3\">${Lang['Statistics']}</font><br /><br />\n";
		$db->query("SELECT `login`,`bank` FROM `${prefix}users` ORDER BY `bank` DESC LIMIT 50;");
		echo "\t<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\"><tr id=\"header\"><td>&nbsp;</td><td>&nbsp;</td><td>${Lang['Login']}:</td><td>&nbsp;</td><td>${Lang['Bank']}:</td>\n";
		while ($t = $db->fetchrow()) {
			@$i++;

			if ($t['login'] == $login) $id=' id="here"';
			elseif (! ($i % 2)) $id = ' id="div"';
			else $id = '';

			echo "<tr height=\"24\"$id><td align=\"center\">$i.</td><td width=\"12\">&nbsp;</td><td align=\"center\"><a href=\"whois.php?name=${t['login']}\">" . strcap($t['login']) . "</a></td><td width=\"12\">&nbsp;</td><td align=\"center\"><font class=\"result\">" . div($t['bank']) . "</font></td></tr>";
		}
		echo "\t</table>\n\t<br />\n";
	}
	else {
		subbegin('images/table-b2.jpg');

		tableimg('images/bw.gif', 168, 168, "gallery/places/bank.jpg", 160, 160, '', 'right');

		echo "\t<center><font class=\"h3\">${Lang['BankWelcome']}</font><br /><br />\n";

		echo "\t<font class=\"result\">${Lang['BankDescription']}</font><br /><br />\n";
		echo "\t<b>${Lang['YourBankAccount']}</b>: <font class=\"plus\">" . div($Player['bank']) . "</font> <b>[!]</b><br /><br />\n";

		if ($starmonth == 1) {
			echo "\t${Lang['BankClosed']}<br />";
		}
		else {
?>

		<table align="center" cellspacing="0" cellpadding="0" border="0">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="form">
		<tr valign="middle">
		<td>
			<input type="hidden" name="action" value="deposit" />
			<input type="text" size="7" name="amount" value="<?php echo $Player['credits']; ?>" />
		</td>
		<td>&nbsp; &nbsp;</td>
		<td align="right">
			<input type="submit" value="<?php echo $Lang['Deposit']; ?>" />
		</td>
		</form>
		</tr>
		<tr height="8"><td>&nbsp;</td></tr>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="form">
		<tr valign="middle">
		<td>
			<input type="hidden" name="action" value="withdraw" />
			<input type="text" size="7" name="amount" value="<?php echo $Player['bank']; ?>" />
		</td>
		<td>&nbsp; &nbsp;</td>
		<td align="right">
			<input type="submit" value="<?php echo $Lang['Withdraw']; ?>" />
		</td>
		</tr>
		</form>
		</table>

<?php
		}
		subend();
	}

	$s = '';
	if ($view != 'list') $s .= "\t<br /><a href=\"bank.php?view=list\">${Lang['BanksList']}&nbsp;&gt;&gt;</a><br />\n";
	if ($starmonth != 1) {
		if ($Player['level'] >= 5) {
			if ($view != 'transfer') $s .= "\t<br /><a href=\"bank.php?view=transfer\">${Lang['BankTransfer']}&nbsp;&gt;&gt;</a><br />\n";
			if (($view != 'statistics') && ($Player['level'] >= 10)) $s .= "\t<br /><a href=\"bank.php?view=statistics\">${Lang['Statistics']}&nbsp;&gt;&gt;</a><br />\n";
		}
	}
	if ($s) {
		tablebreak();
		echo "$s\t<br />\n";
	}

	tableend("<a href=\"control.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");

	if ($action) sound('thankyou'); else sound('bank');
}

// ===========================================================================
// DEFAULT
// ===========================================================================

else {
	tablebegin($pagename);
	echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";
	tableend("<a href=\"control.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
	sound('error');
}

require('include/footer.php');
