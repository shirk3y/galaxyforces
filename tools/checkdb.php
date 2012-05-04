<?php

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

?><html>
<head>
	<link rel="StyleSheet" href="../style.css">
</head>

<!---------------------------------------------------------------------------

	CheckDB Tool {tools/checkdb.php}

	Version:	0.2
	Created:	2005-01-15
	Modified:	2005-04-08
	Author(s):	zoltarx

----------------------------------------------------------------------------->

<body>

<center>

<h3>checkdb</h3>
<?php

switch ($action) {
	case 'internal': include('../include/config.php'); break;
	case 'check': $Config['Database'] = array(
			'Type' => $_POST['type'],
			'Host' => $_POST['host'],
			'User' => $_POST['user'],
			'Password' => $_POST['password'],
			'Name' => $_POST['name']
		);
		break;
}

if ($action) {
	require('../db/' . $Config['Database']['Type'] . '.php');

	echo "<br />Connection to host: <b>" . $Config['Database']['Host'] . "</b> (database: <b>" . $Config['Database']['Name'] . '</b>): ';
	if (! ($db = new sql_db($Config['Database']['Host'], $Config['Database']['User'], $Config['Database']['Password'], $Config['Database']['Name'])) || (! $db) || (! $db->sql_query("SHOW STATUS"))) echo '<font class="error">failed</font>';
	else echo '<font class="plus">succesful</font>';
	echo "<br />";
}

if (! ($type = @$_POST['type'])) $type = 'mysql';

?>
<br />
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<table>
<tr><td><b>Database type</b>:</td><td><select name="type">
<option value="db2"<?=($type == 'db2' ? ' selected' : '')?>>DB2</option>
<option value="msaccess"<?=($type == 'msaccess' ? ' selected' : '')?>>MS Access</option>
<option value="mssql-odbc"<?=($type == 'mssql-odbc' ? ' selected' : '')?>>MSSQL via ODBC</option>
<option value="mssql"<?=($type == 'mssql' ? ' selected' : '')?>>MSSQL</option>
<option value="mysql"<?=($type == 'mysql' ? ' selected' : '')?>>MySQL</option>
<option value="mysql4"<?=($type == 'mysql4' ? ' selected' : '')?>>MySQL 4 (preferably)</option>
<option value="oracle"<?=($type == 'oracle' ? ' selected' : '')?>>Oracle</option>
<option value="postgres7"<?=($type == 'postgres7' ? ' selected' : '')?>>PostgreSQL</option>
</select></td></tr>
<tr><td><b>Hostname or IP</b>:</td><td><input type="text" value="localhost" name="host" /></td></tr>
<tr><td><b>Database name</b>:</td><td><input type="text" value="<?=(@$_POST['name'] ? $_POST['name'] : 'test')?>" name="name" /></td></tr>
<tr><td><b>Database user</b>:</td><td><input type="text" value="<?=(@$_POST['user'] ? $_POST['user'] : '')?>" name="user" /></td></tr>
<tr><td><b>Password</b>:</td><td><input type="password" name="password" value="<?=(@$_POST['password'] ? $_POST['password'] : '')?>" /></td></tr>
<tr><td colspan="2"><center><input type="hidden" name="action" value="check" /><input type="submit" value="Check database connection" /></td></tr>
</form>
</table>

<br />
<a href="<?=$_SERVER['PHP_SELF']?>?action=internal">Configuration test&nbsp;&gt;&gt;</a>

</center>

</body>
</html>

