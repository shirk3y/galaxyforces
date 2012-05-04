<?php
$index = 'control';
$auth = true;

require('include/header.php');

$pagename = 'Thoria';

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
// RESULTS
// ===========================================================================

elseif ($result) {
	tablebegin($Lang['Thoria'], 500);
	echo "\t<h3>${Lang['Thoria']}</h3>\n";
	echo "\t\t<font class=\"result\">$result</font>\n\t\t<br />\n";
	echo "\t\t<a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br />\n\t\t<br />\n";
	sound('thoriabackground');
	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}

// ===========================================================================
// WELCOME
// ===========================================================================

elseif (checkplace('thoria')) {

	tablebegin($Lang['Thoria'], 500);

	subbegin();

	tableimg('images/bw.gif', 168, 168, 'gallery/places/thoria.jpg', 160, 160, '', 'right');

?>
		<center>
		<font class="h3"><?php echo $Lang['Thoria']; ?></font><br />
		<br />
		<font class="result"><?php echo $Lang['ThoriaInfo']; ?></font><br />
		<br />
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="form">
		<?php echo $Lang['SpendMP']; ?>: &nbsp;
		<input type="hidden" name="action" value="thoria" /><input type="text" size="6" name="amount" value="<?php echo $Player['mp']; ?>" />
		&nbsp; <font class="capacity">Max: <?php echo $Player['mp']; ?></font><br />
		<br />
		<input type="submit" value="<?php echo $Lang['Work']; ?>" />
		</form>
		</center>
		&nbsp;

		<script>
		<!--
			document.form.amount.focus();
		//-->
		</script>

<?php
	sound('thoria');
	sound('thoriabackground');
	subend();

	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}
else {
	tablebegin($Lang['TheMines'], 500);
	echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";
	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}

require('include/footer.php');
