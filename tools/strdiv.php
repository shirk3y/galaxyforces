<?php

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

?><html>
<head>
	<link rel="StyleSheet" href="../style.css">
</head>

<!---------------------------------------------------------------------------

	StrDiv Tool {tools/strdiv.php}

	Version:	0.1
	Created:	2005-01-15
	Modified:	2005-04-13
	Author(s):	zoltarx

----------------------------------------------------------------------------->

<body>

<center>

<h3>strdiv</h3>
<?php

if ($action == 'divide') {
	foreach ($t = explode(' ', @$_POST['text']) as $s) {
		while (strlen($s) > @$_POST['length']) {
			$u[] = substr($s, 0, @$_POST['length'] - 1);
			$s = substr($s, @$_POST['length']);
		}
		$u[] = $s;
	}
	$s = join(' ', $u);

	echo "<br />Your text here:<br /><br />";
	echo "<textarea rows=10 cols=40>$s</textarea>";
	echo "<br />";
}

?>
<br />
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<table>
<tr><td><b>Maximum length</b>:</td><td><input type="text" value="<?=(@$_POST['name'] ? $_POST['name'] : '8')?>" name="length" /></td></tr>
<tr><td><b>Text</b>:</td><td><textarea rows=10 cols=40 name="text"><?=(@$_POST['text'] ? $_POST['text'] : '')?></textarea></td></tr>
<tr><td colspan="2"><center><input type="hidden" name="action" value="divide" /><input type="submit" value="Divide text" /></td></tr>
</form>
</table>

</center>

</body>
</html>

