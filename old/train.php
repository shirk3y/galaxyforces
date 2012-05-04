<?php
$index = 'colony';
$auth = true;

require('include/header.php');

if (isset($errors) && $errors) {
	 tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');

?>	<br />
	<?php echo $Lang['ErrorCantTrain']; ?><br />
	<br />
	<font class="error"><?php echo $errors; ?></font>
	<br />
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
	 tableend($Lang['TrainingS']);
}
elseif ($Colony && $Colony['academy']) {

	tablebegin($Lang['TrainingS'], 500);

?>		<br />
		<b><?php echo $Lang['Academy']; ?></b><br />
		<br />
<?php
	if ($action == 'train') {

?>		<?php echo $Lang['ColonistsTrained']; ?><font class="plus"><?php echo $result; ?></font><br />
		<br />
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
		<br />
<?php
	}
	else {
		$max = $Player['credits'] / $soldierstraincost < $Colony['colonistsfree'] ? floor($Player['credits'] / $soldierstraincost) : $Colony['colonistsfree'];
		$max = $Colony['barracks'] * 50 - $Colony['soldiers'] < $max ? $Colony['barracks'] * 50 - $Colony['soldiers'] : $max;

		if ($max) {

?>		<?php echo $Lang['SoldiersTrainCost']; ?>: <font class="plus"><?php echo $soldierstraincost; ?></font> [!]<br />
		<br />
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<b><?php echo $Lang['SCn']; ?></b> (Max: <font class="result"><?php echo $max; ?></font>): &nbsp; &nbsp;<input type="text" size="5" value="<?php echo $max; ?>" name="amount" /><br />
		<br />
		<input type="hidden" name="action" value="train" /><input type="submit" value="<?php echo $Lang['TrainS']; ?>" /><br />
		<br />
		</form>
<?php
		}
		else {

?>		<font class="result"><?php echo $Lang['NoRoomS']; ?></font><br />
		<br />
<?php
		}
	}

	tableend('<a href="colony.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}
else {
	tablebegin($Lang['TrainingS'], 500);
	echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\t\t<font class=\"result\">${Lang['UDNA']}</font><br />\n\t\t<br />\n";
	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}

require('include/footer.php');
