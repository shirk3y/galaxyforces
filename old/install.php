<?php

// ===========================================================================
// Galaxy Forces Setup {install.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.0
//	Modified:	2005-06-19
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$Config['DefaultLanguage']='en';

require('include/locale.php');
locale('install');
locale('messages');

if (!@filesize('include/config.php')) $mode = 'install';
elseif (file_exists('log/VERSION.txt') && file_get_contents('VERSION.txt') != file_get_contents('log/VERSION.txt')) $mode = 'update';
else $mode = '';

// ---------------------------------------------------------------------------
// Variables
// ---------------------------------------------------------------------------

$layer = @$_POST['layer'] ? strtolower($_POST['layer']) : 'mysql';
$host = @$_POST['host'] ? $_POST['host'] : 'localhost';
$user = @$_POST['user'] ? $_POST['user'] : 'galaxy';
$password = @$_POST['password'] ? $_POST['password'] : '';
$name = @$_POST['name'] ? $_POST['name'] : 'galaxy';
$prefix = isset($_POST['prefix']) ? $_POST['prefix'] : 'galaxy_';
$create = @$_POST['create'] ? $_POST['create'] : 'on';
$email = @$_POST['email'] ? $_POST['email'] : '';
$initialuser = @$_POST['initialuser'] ? $_POST['initialuser'] : 'admin';
$initialpassword = @$_POST['initialpassword'] ? $_POST['initialpassword'] : '';

if (!function_exists("is_az")) {

function is_az($str)
{
	if (1>$l=strlen($str)) return false;
	foreach (str_split($str) as $c) if (($c < "A" || $c > "Z") && ($c < "a" || $c > "z")) return false;
	return true;
}

}

if (!is_az($layer)) $layer='mysql';

if ($action = @$_POST['action']) {
	$createtables = @$_POST['createtables'];
	$initialcreate = @$_POST['initialcreate'];
	$createworld = @$_POST['createworld'];
	$createitems = @$_POST['createitems'];
}
else {
	$createtables = 'on';
	$initialcreate = 'on';
	$createworld = 'on';
	$createitems = 'on';
}

// ---------------------------------------------------------------------------
// Functions
// ---------------------------------------------------------------------------

function readsql($filename)
{
        $b = FALSE;
	if ($f = @fopen($filename, 'r')) {
		flock($f, LOCK_SH);
		while (! feof($f)) {
			$s = trim(fgets($f, 32768));
			if (strpos($s, 'CREATE TABLE') !== FALSE || strpos($s, 'INSERT INTO') !== FALSE) {
				$b = TRUE;
				$t = '';
			}
			if ($b) {
				$t .= $s;
				if (strpos($s, ';') !== FALSE) {
					$sql[] = $t;
					$b = FALSE;
				}
			}
		}
		flock($f, LOCK_UN);
		fclose($f);
	}
	return @$sql;
}

function error($message)
{
	global $errors, $Lang;
	$errors[] = "<b>${Lang['Error']}</b>: <font class=\"error\">$message</font><br />";
}

function parsefile($filename, $replace = array(), $with = array())
{
	$result = '';
	if ($f = fopen($filename, 'r')) {
		flock($f, LOCK_SH);
		while (! feof($f)) {
			$s = rtrim(fgets($f, 32768));
			if ($replace) {
				for ($i = 0; $i < count($replace); $i++) {
					if (strpos($s, $replace[$i]) !== FALSE) {
						$s = $with[$i];
						break;
					}
				}
			}
			$result .= ($result ? "\n" : '').$s;
		}
		flock($f, LOCK_UN);
		fclose($f);
	}
	return $result;
}

function writefile($filename, $content = '')
{
	if ($f = @fopen($filename, 'w')) {
		flock($f, LOCK_EX);
		fwrite($f, $content);
		flock($f, LOCK_UN);
		fclose($f);
		return TRUE;
	}
	return FALSE;
}

// ---------------------------------------------------------------------------
// Initialization stuff
// ---------------------------------------------------------------------------

if ($mode == 'install') {
	if (! file_exists('include/config.php') && ($f = @fopen('include/config.php', 'w'))) fclose($f);
	if (! is_writable('include/config.php')) $warnings[] = "<b>${Lang['Warning']}</b>: ${Lang['WarningInstall1']}<br />";

	if (! file_exists('log/common.log') && ($f = fopen('log/common.log', 'w'))) fclose($f);
	if (! file_exists('log/chat.log') && ($f = @fopen('log/chat.log', 'w'))) fclose($f);
	if (! file_exists('log/VERSION.txt') && ($f = @fopen('log/VERSION.txt', 'w'))) fclose($f);
	
	if (! is_writable('log/common.log') || ! is_writable('log/chat.log') || ! is_writable('log/VERSION.txt')) $warnings[] = "<b>${Lang['Warning']}</b>: ${Lang['WarningInstall2']}<br />";
}
else die;

// ---------------------------------------------------------------------------
// Configuration
// ---------------------------------------------------------------------------

$source_prefix = 'galaxy_';
$destination_prefix = $prefix;

// ---------------------------------------------------------------------------
// HEAD
// ---------------------------------------------------------------------------

echo "<html>\n<head>\n\t<title>[ Galaxy Forces Setup ]</title>\n";
if (@$Lang['Charset']) echo "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=${Lang['Charset']}\">\n";
echo "\t<link rel=\"stylesheet\" href=\"style/default/style.css\">\n";
echo "</head>\n\n<body>\n";

echo "<h1>${Lang['Install1']}</h1>\n";

if ($action == 'install' && $mode == 'install') {
	$Database = array(
		"Host"=>$host,
		"User"=>$user, 
		"Password"=>$password,
		"Name"=>$name,
		"Charset"=>"UTF-8",
		"Layer"=>$layer,
	);

	require("include/db.php");

	if (!@$db) {
		if (isset($db)) error($db->status());
		error($Lang['Error2']);
	}
	else {
		$failed = 0;
		$failedQueries=array();
		
		$replace[] = "CREATE TABLE `$source_prefix";
		$with[] = "CREATE TABLE `$destination_prefix";
		
		$replace[] = "INSERT INTO `$source_prefix";
		$with[] = "INSERT INTO `$destination_prefix";

		if ($createtables && ($sql = readsql('sql/install.sql'))) {
			foreach ($sql as $query) {
				$query = str_replace($replace, $with, $query);
				if (! $db->query($query)) $failedcreate[] = preg_replace('/CREATE TABLE \`(.*?)\`(.*)/si', '<b>$1</b>', $query);
			}
			if (@$failedcreate) error($Lang['Error3a'].(join(', ', $failedcreate)).$Lang['Error3b']);
		}

		if ($initialcreate) {
			if (!$db->query("INSERT INTO {$destination_prefix}users (id,active,login,password,usergroup,email,registered) VALUES (0,1,'$initialuser','".md5($initialpassword)."','wheel','$email','".date("Y-m-d")."');")) error($Lang['Error4']);
		}

		if ($createworld && ($sql = readsql('sql/world.sql'))) {
			foreach ($sql as $query) {
				$query = str_replace($replace, $with, $query);
				if (! $db->query($query)) {
					$failed++;
					$failedQueries[]=$query;
				}
			}
		}

		if ($createitems && ($sql = readsql('sql/items.sql'))) {
			foreach ($sql as $query) {
				$query = str_replace($replace, $with, $query);
				if (! $db->query($query)) {
					$failed++;
					$failedQueries[]=$query;
				}
			}
		}

		if ($failed) {
			error($Lang['Error6']."<b>$failed</b>");
			error("<pre>".join("<br />", $failedQueries)."</pre>");
		}

		$replace = $with = array();
		$replace[] = "\$Database['Type']";
		$with[] = "\$Database['Type']='$layer';";
		$replace[] = "\$Database['Host']";
		$with[] = "\$Database['Host']='$host';";
		$replace[] = "\$Database['User']";
		$with[] = "\$Database['User']='$user';";
		$replace[] = "\$Database['Password']";
		$with[] = "\$Database['Password']='$password';";
		$replace[] = "\$Database['Name']";
		$with[] = "\$Database['Name']='$name';";
		$replace[] = "\$Database['Prefix']";
		$with[] = "\$Database['Prefix']='$destination_prefix';";
		$replace[] = "\$Config['Administrator']";
		$with[] = "\$Config['Administrator']='$email';";

		$config = parsefile('include/config.php.example', $replace, $with);

		if (! writefile('include/config.php', $config)) {
			echo '<p />'.$Lang['Error5'].'<br /><p>';
			echo "<code>".(str_replace("\n", '<br />', htmlspecialchars($config)))."</code></p>";
		}

		writefile('log/VERSION.txt', file_get_contents('VERSION.txt'));
		
		$finished = TRUE;
	}
	
}

switch ($mode) {
	case 'install':
		if (@$warnings) foreach ($warnings as $s) echo "$s<br />\n";
		if (@$errors) foreach ($errors as $s) echo "$s<br />\n";
		break;
	default:
		echo $Lang['ErrorInstall1'];	
}

if ($mode == 'install') {
	if (@$finished) {
		echo $Lang['InstallationFinished'].'<br />';
		echo "<br /><a href=\"welcome.php\">${Lang['WelcomePage']}&nbsp;&gt;&gt;</a><br />";
	}
	else {
?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

	<input type="hidden" name="action" value="install" />
	
	<table align="center">
	<tr>
	<td><?php echo $Lang['InstallMode']; ?>:</td>
	<td>&nbsp;</td>
	<td><b><?php echo $Lang['Install']; ?></b></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3" class="box"><h3><?php echo $Lang['DatabaseSettings']; ?></h3></td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td><?php echo $Lang['DatabaseType']; ?>:</td>
	<td>&nbsp;</td>
	<td>
		<select name="layer">
		<option value="mysql"<?php echo $layer == 'mysql' ? ' selected' : ''; ?>>MySQL</option>
<?php
/*
		<option value="postgres"<?php echo $layer == 'postgres' ? ' selected' : ''; ?>>PostgreSQL</option>
*/
?>
		</select>
	</td>
	</tr>
	<tr>
	<td><?php echo $Lang['DatabaseHost']; ?>:</td>
	<td>&nbsp;</td>
	<td><input type="text" name="host"<?php echo $host ? ' value="'.$host.'"' : ''; ?> /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['DatabaseUser']; ?>:</td>
	<td>&nbsp;</td>
	<td><input type="text" name="user"<?php echo $user ? ' value="'.$user.'"' : ''; ?> /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['DatabasePass']; ?>:</td>
	<td>&nbsp;</td>
	<td><input type="password" name="password"<?php echo $password ? ' value="'.$password.'"' : ''; ?> /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['DatabaseName']; ?>:</td>
	<td>&nbsp;</td>
	<td><input type="text" name="name"<?php echo $name ? ' value="'.$name.'"' : ''; ?> /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['DatabasePrefix']; ?>:</td>
	<td>&nbsp;</td>
	<td><input type="text" name="prefix" value="<?php echo $destination_prefix; ?>" /></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3"><input name="createtables" type="checkbox"<?php echo $createtables == 'on' ? ' checked="checked"' : ''; ?>" /><?php echo $Lang['CreateTables']; ?></td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
<?php	
/*
	<tr><td colspan="3"><input name="create" type="checkbox" checked="<?php echo $create == 'on' ? 'true' : 'false'; ?>" /><?php echo $Lang['DatabaseCreate']; ?></td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
*/
?>

	<tr><td colspan="3" class="box"><h3><?php echo $Lang['InitialSettings']; ?></h3></td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3"><input name="initialcreate" type="checkbox"<?php echo $initialcreate == 'on' ? ' checked="checked"' : ''; ?> /><?php echo $Lang['InitialCreate']; ?></td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td><?php echo $Lang['Login']; ?>:</td>
	<td>&nbsp;</td>
	<td><input type="text" name="initialuser"<?php echo $initialuser ? ' value="'.$initialuser.'"' : ''; ?> /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['Password']; ?>:</td>
	<td>&nbsp;</td>
	<td><input type="password" name="initialpassword"<?php echo $initialpassword ? ' value="'.$initialpassword.'"' : ''; ?> /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['Email']; ?>:</td>
	<td>&nbsp;</td>
	<td><input type="text" name="email"<?php echo $email ? ' value="'.$email.'"' : ''; ?> /></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3"><input name="createworld" type="checkbox"<?php echo $createworld == 'on' ? ' checked' : ''; ?> /><?php echo $Lang['CreateWorld']; ?></td></tr>
	<tr><td colspan="3"><input name="createitems" type="checkbox"<?php echo $createitems == 'on' ? ' checked' : ''; ?> /><?php echo $Lang['CreateItems']; ?></td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3"><center><input type="submit" /></center></td></tr>
	</table>

	</form>
<?php
	}
}

?></body>
</html>
