<?php

$index = 'login';

$Config['Maintenance'] = 1;

require('include/header.php');

$error = $action == 'login' && ! $logged;

?>
<script>

function autoFocus()
{
	if (findElement("login").value != "") 
	{
		setFocus("password");
	}
	else
	{
		setFocus("login");
	}
}

addReadyEvent(autoFocus);

</script>

<?php

if ($action == 'useless') {
	echo $secret;
}
else {
	$url = $back ? $back : $_SERVER['PHP_SELF'];
?>
	<form action="<?php echo $url; ?>?rid=<?php echo $rid; ?>" method="POST" name="form">
<?php 

tablebegin($error ? ('<font class="error">' . $Lang['LoginFailed'] . '</font>') : $Lang['Logging'], '300');

if ($error) { ?><br /><font class="error"><?php echo $Lang['ErrorLoginFailed']; ?></font><?php }

if ($action == 'logout') { ?><br /><font class="capacity"><?php echo $Lang['SuccesfullyLogout']; ?></font><?php }

?>
	<center>
	<br />
	<table class="form" align="center">
	<tr>
	<td><?php echo $Lang['Login']; ?>:</td>
	<td><input type="text" maxlength="32" name="login"<?php echo ($login ? " value=\"$login\"" : ''); ?> /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['Password']; ?>:</td>
	<td><input type="password" maxlength="32" name="password"<?php echo ($error ? " value=\"$password\"" : ''); ?> /></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	<td colspan="2"><input type="hidden" name="confirm" value="<?php echo $secret; ?>" /><input type="hidden" name="action" value="login" /><center><input type="submit" value="<?php echo $Lang['Login']; ?>" /></center></td>
	</tr>
<?php
if ($error) {
?>
	<tr>
	<td colspan="2">
	<br />
	<center><a href="lostpassword.php?login=<?php echo $login; ?>"><?php echo $Lang['ForgottenPassword']; ?></a></center>
	</td>
	</tr>
<?php
}
?>	<tr><td colspan="2">&nbsp;</td></tr>
<?php
if ($action == 'login' && ! $logged) {
}
?>	</table>
<?php tableend($Lang['Logging']); ?>
	</form>

<?php
}

require('include/footer.php');
