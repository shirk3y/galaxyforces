<?php

// ===========================================================================
// Password Recovery {lostpassword.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.1
//	Modified:	2005-11-12
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'lostpassword';
require('include/header.php');

$exists = TRUE;
$activated = TRUE;

if ($action == 'recover') {
	$regid = getvar('regid');
	if ($regid) {
		$db->query("SELECT `login`,`email` FROM `${prefix}users` WHERE `regid`='$regid';");
		if ($t = $db->fetchrow()) {
			$login = $t['login'];
			$email = $t['email'];
		}
		else $login="";

		if (! $login) {
			tablebegin('<font color="red" class="error">'.$Lang['Error'].'</font>', '400');
			echo TAB.'<br /><center><font color="red" class="error">'.$Lang['ErrorBadRecoveryID'].'<br /></font>'.LF;
			echo TAB.'<br /><a href="javascript:history.back(1)">'.$Lang['GoBack'].'&nbsp;&gt;&gt;</a><br />'.LF;
			echo TAB.'</center><br />'.LF;
			tableend($Lang['LostPassword']);
		}
		else {
			$password = Rand(111, 999) . Rand(111, 999);
			$backpassword = $password;
			$password = md5($password);
			$db->query("UPDATE `${prefix}users` SET `password`='$password' WHERE `regid`='$regid' LIMIT 1;");
			if ($email) {
				$msg = $Lang['EmailDontReply'] . $Lang['EmailForgotten3'] . $Lang['EmailRegister21'] . "$login\n\n" . $Lang['EmailRegister22'] . "$backpassword\n\n" . $Lang['EmailForgotten4'];
				sendmail($email, $Lang['EmailForgotten2Subject'], $msg);
			}
?>
<?php tablebegin($Lang['Finished'], '400') ?>
	<br />
	<center>
		<?php echo $Lang['RecoveryCompleted']; ?><br />
		<br />
		<a href="login.php?login=<?php echo $login; ?>"><?php echo $Lang['Login']; ?> &gt;&gt;</a><br />
	</center>
	<br />
<?php tableend($Lang['LostPassword']); ?>
<?php
		}
	}
	else {
?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>" method="POST" name="form">
<?php tablebegin($Lang['LostPassword'], '400') ?>
	<table background="images/table.jpg" id="form" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><img src="images/0.gif" width="160" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="160" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['RecoveryID']; ?>:</td>
	<td><input type="text" maxlength="16" name="regid" /></td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	<tr>
	<td colspan="2"><input type="hidden" name="action" value="recover" /><center><input type="submit" value="<?php echo $Lang['Continue']; ?>" /></center></td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	</table>
<?php tableend($Lang['LostPassword']); ?>
	</form>

	<script>
	<!--
		document.form.regid.focus();
	//-->
	</script>
<?php
	}
}

elseif ($login = escapesql(getvar('login'))) {
	$db->query("SELECT `active`, `email` FROM `${prefix}users` WHERE `login` = '$login' LIMIT 1;");
	if (! $db->numrows()) {
		$errors = TRUE;
		$exists = FALSE;
	}
	else {
		$tab = $db->fetchrow();
		if (! $tab['active']) {
			$errors = TRUE;
			$activated = FALSE;
		}
		else {
			$email = $tab['email'];
			$regid = crypt($login, Rand(1111, 9999));
			$regid = Rand(11, 99) . substr($regid, strlen($regid) - 8, 8) . Rand(11, 99);
			$db->query("UPDATE `${prefix}users` SET `regid` = '$regid' WHERE `login` = '$login' LIMIT 1");
			$msg = $Lang['EmailDontReply'].LF.LF.$Lang['EmailForgotten1']."\thttp://${_SERVER['HTTP_HOST']}${_SERVER['PHP_SELF']}?action=recover&regid=$regid\n\n".$Lang['Login'].": $login\n".$Lang['EmailForgotten2']."$regid\n\n".$Lang['EmailRegister4'];
			if (sendmail($email, $Lang['EmailForgotten1Subject'], $msg)) {
				tablebegin($Lang['LostPassword'], '400');
				echo "\t\t<br />${Lang['ForgottenRequestSent']}<br />";
				echo "<a href=\"lostpassword.php?action=recover&login=$login\">${Lang['Continue']}&nbsp;&gt;&gt;</a><br />";
				echo "<br />\n";
				tableend($Lang['LostPassword']);
			}
			else {
				tablebegin("<font class=\"error\">${Lang['Error']}</font>", '400');
				echo "\t\t<br /><font class=\"error\">${Lang['ErrorSendmailFailed']}</font><br /><br />";
				echo "<a href=\"lostpassword.php?login=$login\">${Lang['Continue']}&nbsp;&gt;&gt;</a><br />";
				echo "<br />\n";
				tableend($Lang['LostPassword']);
			}
		}
	}

} else $errors = TRUE;

if ($errors) {
?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>" method="POST" name="form">
<?php tablebegin($Lang['LostPassword'], '400') ?>
	<table background="images/table.jpg" id="form" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><img src="images/0.gif" width="100" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="100" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
<?php
if (! $activated) {
?>	<tr>
	<td colspan="2" width="200">
	<center>
	<font color="red" class="error">
		<?php echo $Lang['ErrorLoginNotActive']; ?>
	</font>
	</center>
	</td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
<?php
}
if (! $exists) {
?>	<tr>
	<td colspan="2" width="200">
	<center>
	<font color="red" class="error">
		<?php echo $Lang['ErrorLoginNotExistsDesc']; ?>
	</font>
	</center>
	</td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
<?php
}
?>	<tr>
	<td><?php echo $Lang['Login']; ?>:</td>
	<td><input type="text" maxlength="16" name="login"<?php echo ($login ? " value=\"$login\"" : ''); ?> /></td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	<tr>
	<td colspan="2"><input type="hidden" name="action" value="login" /><center><input type="submit" value="<?php echo $Lang['Check']; ?>" /></center></td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	</table>
<?php tableend($Lang['LostPassword']) ?>
	</form>

	<script>
	<!--
		document.form.login.focus();
	//-->
	</script>
<?php
}
require('include/footer.php');
