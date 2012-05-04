<?php

$index = 'control';
$auth = true;

require('include/header.php');

if (isset($errors) && $errors) {
	tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');
	sound('error');
?>	<br />
	<?php echo $Lang['ErrorCantGamble']; ?><br />
	<br />
	<font class="error"><?php echo $errors; ?></font>
	<br />
	<a href="control.php"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
	 tableend($Lang['GamblersHouse']);
}
elseif (checkplace('gambler')) {

	tablebegin($Lang['GamblersHouse'], 500);

	if ($action == 'gamble') {

?>		<br />
		<font class="result"><?php echo $result; ?></font>
		<br />
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=gamble"><?php echo $Lang['PlayAgain']; ?> &gt;&gt;</a><br />
		<br />
<?php
	}
	else {
		sound('gambler');

?>		<br />
		<br /><?php echo $Lang['GamblerWelcome']; ?><br />
		<br />
		<b><?php echo $Lang['PlayCost']; ?></b>: <font class="minus"><?php echo $place['parameters']; ?></font> [!]<br />
		<br />
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=gamble"><?php echo $Lang['Play']; ?> &gt;&gt;</a><br />
		<br />
<?php
	}
	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}
else {
	tablebegin($Lang['GamblersHouse'], 500);
	echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";
	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}

require('include/footer.php');
