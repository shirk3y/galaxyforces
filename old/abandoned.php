<?php
$index = 'control';
$auth = true;

require('include/header.php');


if (isset($errors) && $errors) {
	 tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');
?>	<br />
		<script>
		<!--
			document.form.amount.focus();
		//-->
		</script>
<?php
		subend();	

	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}
else {
	tablebegin($Lang['TheMines'], 500);
	echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";
	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}

require('include/footer.php');
