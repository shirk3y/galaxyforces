<?

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
$string = isset($_POST['string']) ? $_POST['string'] : (isset($_GET['string']) ? $_GET['string'] : '');
$key = isset($_POST['key']) ? $_POST['key'] : (isset($_GET['key']) ? $_GET['key'] : '$1');

?><html>
<head>
	<link rel="StyleSheet" href="../style.css">
</head>

<!---------------------------------------------------------------------------

	MD5 Tool {tools/md5.php}

	Version:	0.1
	Created:	2005-02-10
	Modified:	2004-02-11
	Author(s):	zoltarx@o2.pl

----------------------------------------------------------------------------->

<body>

<center>

<h3>md5</h3>
<?

if ($action) {
	echo '<table>';
	echo '<tr><td>Crypt (random salt):</td><td><i>crypt($string)</i></td><td><b>' . crypt($string) . '</b></td></tr>'; 
	echo '<tr><td>Crypt (constant salt):</td><td><i>crypt($string, "' . $key . '")</td><td><b>' . crypt($string, $key) . '</b></td></tr>'; 
	echo '<tr><td>Crypt:</td><td><i>crypt($string, $string)</td><td><b>' . crypt($string, $string) . '</b></td></tr>'; 
	echo '<tr><td colspan="3">&nbsp;</td></tr>';
	echo '<tr><td>MD5:</td><td><i>md5($string)</td><td><b>' . md5($string) . '</b></td></tr>'; 
	echo '</table>';
}

if ($action) {
}

?>
<br />
<form name="form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<table>
<tr><td><b>String</b>:</td><td><input type="text" value="<?=$string?>" name="string" /></td></tr>
<tr><td><b>Key</b>:</td><td><input type="text" value="<?=$key?>" name="key" /></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2"><center><input type="hidden" name="action" value="md5" /><input type="submit" value="Calculate" /></td></tr>
</form>
</table>

<script>
	document.form.string.focus();
</script>

</center>

</body>
</html>
