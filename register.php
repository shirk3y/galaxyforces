<?php

// ===========================================================================
// Register {register.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.2
//	Modified:	2005-11-12
//	Author(s):	zoltarx, unk
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'register';

require('include/header.php');

// ===========================================================================
// REGISTER
// ===========================================================================

if ($action == 'register') {
	$errors = '';

	$credits = 100000 + Rand(-5000, 5000);
	$score = 0;
	$reputation = 0;

	$login = escapesql(strip_tags(strtolower(trim(postvar('login')))));
	$password = escapesql(strip_tags(postvar('password')));
	$reenter = escapesql(strip_tags(postvar('reenter')));
	$agree = postvar('agree') == 'on';
//	$name = postvar('name');
	$email = escapesql(strip_tags(postvar('email')));
	$reemail = escapesql(strip_tags(postvar('reemail')));
//	$country = postvar('country');
	$language = escapesql(strip_tags(postvar('language')));
//	$gender = postvar('gender');
//	$birth = postvar('birth');
	$gg = escapesql(strip_tags(postvar('gg')));
	$www = escapesql(strip_tags(postvar('www')));

	$year = date('Y');
	$today = date('Y-m-d');

// SPRAWDZENIE WPISANEGO NICKA

	preg_match("/[~`+=|\\{};:'\"<,>.?\/]/i", $login, $wynik);

// KONIEC

	if (!$agree) $errors .= $Lang['ErrorAgreement'] . '<br />';
	if (!$login) $errors .= $Lang['ErrorEmptyLogin'] . '!<br />';
	elseif (ereg("[<'&,;>]", $login) || (@$wynik[0]) || ($login == 'admin') || ($login == 'system') || ($login == 'root') || ($login == 'administrator') || (strrpos($login, ' ') > strlen($login) - 2) || (strpos($login, ' ') == ' ')) $errors .= $Lang['ErrorLoginInvalid'] .'<br />';
	if (strlen($password) < 6) $errors .= $Lang['ErrorPasswordTooShort'] . ' (' . $Lang['mustcontainatleast'] . ' 6 ' . $Lang['chars'] . ')' . '<br />';
	if ($password != $reenter) $errors .= $Lang['ErrorPasswordAndReenter'] . '<br />';
//	if (! $name) $errors .= $Lang['ErrorEmptyName'] . '!<br />';
	if (! $email || $email != $reemail) $errors .= $Lang['ErrorEmailMustBeValid'] . '<br />';
//	if (! $gender) $errors .= $Lang['ErrorGenderUnknown'] . '!<br />';
//	if (($birth > $year - 3) || ($birth < $year - 100)) $errors .= $Lang['ErrorBirthYear'] . '!<br />';

	if (! $errors) {
		$db->query("DELETE FROM `${prefix}users` WHERE `active` = '0' AND `registered` < '" . date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 2, date("Y"))) . "';");
		$db->query("SELECT (`id`) FROM `${prefix}users` WHERE `login`='$login' LIMIT 1;");
		if ($db->numrows()) $errors .= $Lang['ErrorLoginExists'] . '!<br />';
		else {
			$backpassword = $password;
			$password = md5($password);
			$regid = crypt($login, Rand(1111, 9999));
			$regid = Rand(11, 99) . substr($regid, strlen($regid) - 8, 8) . Rand(11, 99);

			if ($Config['Registration'] == 'auto') $sql = "INSERT INTO `${prefix}users` (`login`,`password`,`email`,`gg`,`www`,`language`,`active`,`registered`) VALUES ('$login','$password','$email','$gg','$www','$language',1,'$today');";
			else $sql = "INSERT INTO `${prefix}users` (`login`,`password`,`email`,`gg`,`www`,`language`,`regid`,`registered`) VALUES ('$login','$password','$email','$gg','$www','$language','$regid','$today');"; 

			if (! $db->query($sql)) $errors .= $Lang['ErrorRegistering'] . '<br />';
			else {
				if ($Config['Registration'] == 'auto') {
					tablebegin($Lang['Completed'], '400');
					echo "\t<br />${Lang['RegistrationCompleted']}<br /><br />\n";
					echo "\t<a href=\"welcome.php\">${Lang['MainPage']}&nbsp;&gt;&gt;</a><br /><br />\n";
					tableend($Lang['Registration']);
				}
				else {
					$link = "http://${_SERVER['HTTP_HOST']}${_SERVER['PHP_SELF']}?action=activate&regid=$regid";
					if ($Config['Registration'] == 'admin') {
						locale('messages', $Config['DefaultLanguage']);
						$msg = "${Lang['EmailRegister8']} ${Config['Title']} \n\n\t${Lang['Login']}: $login\n\t${Lang['Email']}: $email\n\t${Lang['Language']}: $language\n\n\t${Lang['RegistrationID']}: $regid\n\n${Lang['EmailRegister3']}:\n\n\t$link\n\n";
						$email = $Config['Administrator'];
					}
					else {
						locale('messages', $language);
						$msg = "${Lang['EmailDontReply']}\n\n${Lang['EmailRegister1']}\n\n\t${Lang['Login']}:$login\n\t${Lang['Password']}: $backpassword\n\n\t${Lang['RegistrationID']}: $regid\n\n${Lang['EmailRegister3']}:\n\n\t$link\n\n${Lang['EmailRegister4']}";
					}
					if (! sendmail($email, $Lang['EmailRegister1Subject'], $msg)) {
						$errors .= $Lang['ErrorSendmailFailed'] . '<br />';
						$db->query("DELETE FROM `${prefix}users` WHERE `login`='$login' LIMIT 1;");
					}
					else {				
						tablebegin($Lang['Completed'], '400');
						echo "\t<br />${Lang['RegistrationCompleted']}<br /><br />${Lang['RegistrationEmailSent']}<br /><br />\n";
						echo "\t<a href=\"welcome.php\">${Lang['MainPage']}&nbsp;&gt;&gt;</a><br /><br />\n";
						tableend($Lang['Registration']);
					}
				}
			}
		}
	}

	if ($errors) {
		tablebegin('<font class="error">' . $Lang['Error'] . '</font>', '400');
		echo "\t<br />${Lang['FormErrorList']}<br /><br />\n";
		echo "\t<font class=\"error\">$errors</font><br />\n";
		echo "\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />\n";
		tableend($Lang['Registration']);
	}
}

// ===========================================================================
// ACTIVATE
// ===========================================================================

elseif ($action == 'activate') {
	$regid = getvar('regid');
	if ($regid) {
		$db->query("SELECT `login`,`email` FROM `${prefix}users` WHERE `regid`='$regid' LIMIT 1;");
		if ($t = $db->fetchrow()) {
			$login = $t['login'];
			$email = $t['email'];
		}
		else $login="";
		if (! $login) {
			tablebegin('<font color="red" class="error">' . $Lang['Error'] . '</font>', '400');
			echo "\t<br />\n\t<center>\n";
			echo "\t<font color=\"red\" class=\"error\">${Lang['ErrorBadRegID']}</font><br />\n";
			echo "\t<br /><a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br />\n";
			echo "\t</center>\n\t<br />\n";
			tableend($Lang['Activation']);
		}
		else {
			$db->query("UPDATE `${prefix}users` SET `active`='1' WHERE `regid`='$regid' LIMIT 1;");
			if ($email) {
				$msg = $Lang['EmailDontReply'] . $Lang['EmailRegister5'];
				sendmail($email, $Lang['EmailRegister2Subject'], $msg);
			}
			tablebegin($Lang['Activation'], '400');
			echo "\t<br />\n\t<center>\n";
			echo "\t${Lang['ActivatedAccount']}<br /><br />\n";
			echo "\t<br /><a href=\"control.php?rid=$rid&login=$login\">${Lang['Login']}&nbsp;&gt;&gt;</a><br />\n";
			echo "\t</center>\n\t<br />\n";
			tableend($Lang['Activation']);
		}
	}
	else {
?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>" method="POST">
<?php tablebegin($Lang['Activation'], '400') ?>
	<table background="images/table.jpg" id="form" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['RegistrationID']; ?>:</td>
	<td><input type="text" maxlength="16" name="regid" /></td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	<tr>
	<td colspan="2"><input type="hidden" name="action" value="activate" /><center><input type="submit" value="<?php echo $Lang['Activate']; ?>" /></center></td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	</table>
<?php tableend($Lang['Registration']); ?>
	</form>
<?php
	}
}

// ===========================================================================
// DEFAULT
// ===========================================================================

else {
?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>" method="POST" name="form">
<?php tablebegin($Lang['Registration'], '400') ?>
	<h3><?php echo $Lang['Registration']; ?></h3>
	<table width="75%" background="images/table.jpg" id="form" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><?php echo $Lang['Login']; ?>:</td>
	<td><input type="text" maxlength="32" name="login" /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['Password']; ?>:</td>
	<td><input type="password" maxlength="16" name="password" /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['Reenter']; ?>:</td>
	<td><input type="password" maxlength="16" name="reenter" /></td>
	</tr>
	<tr><td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td><td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td></tr>
<?php
/*
	<tr>
	<td><?php echo $Lang['FullName']; ?>:</td>
	<td><input type="text" maxlength="64" name="name" /></td>
	</tr>
*/
?>	<tr>
	<td><?php echo $Lang['Email']; ?> (<?php echo $Lang['mustbevalid']; ?>):</td>
	<td><input type="text" maxlength="32" name="email" /></td>
	</tr>
	<tr>
	<tr>
	<td><?php echo $Lang['EmailReenter']; ?>:</td>
	<td><input type="text" maxlength="32" name="reemail" /></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
<?php
/*
	<td><?php echo $Lang['Country']; ?>:</td>
	<td>
		<select name="country">
		<option value="en"><?php echo $Lang['England']; ?></option>
		<option value="pl"><?php echo $Lang['Poland']; ?></option>
		<option value="us"><?php echo $Lang['USA']; ?></option>
		</select>
	</td>
	</tr>
*/
?>	<tr>
	<td><?php echo $Lang['Language']; ?>:</td>
	<td>
		<select name="language">
		<option value="en"<?php echo ($Config['Language'] == 'en' ? ' selected="selected"' : ''); ?>><?php echo $Lang['English']; ?> (en)</option>
		<option value="pl"<?php echo ($Config['Language'] == 'pl' ? ' selected="selected"' : ''); ?>><?php echo $Lang['Polish']; ?> (pl)</option>
		</select>
	</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
<?php
/*
	<tr>
	<td><?php echo $Lang['Gender']; ?>:</td>
	<td><input type="radio" name="gender" value="m" checked /><?php echo $Lang['Male']; ?> <input type="radio" name="gender" value="f" /><?php echo $Lang['Female']; ?></td>
	</tr>
	<tr>
	<td><?php echo $Lang['Birth']; ?> (<?php echo $Lang['yearonly']; ?>):</td>
	<td><input type="text" size="4" maxlength="4" name="birth" /></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
*/
?>	<tr>
	<td><a href="javascript:alert('<?php echo $Lang['GG#']; ?>')">GG#</a>:</td>
	<td><input type="text" maxlength="64" name="gg" /></td>
	</tr>
	<tr>
	<td><a href="javascript:alert('<?php echo $Lang['WWW']; ?>')">WWW</a>:</td>
	<td><input type="text" maxlength="64" name="www" /></td>
	</tr>
	<tr><td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td><td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td></tr>
	<tr><td colspan="2"><center><input type="checkbox" name="agree"><?php echo $Lang['AgreementCheck']; ?></input></center></td></tr>
	<tr><td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td><td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td></tr>
	<tr>
	<td colspan="2"><input type="hidden" name="action" value="register" /><center><input type="submit" value="<?php echo $Lang['Register']; ?>" /></center></td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	</table>
<?php tableend($Lang['Registration']); ?>
	</form>

	<script>
	<!--
		document.form.login.focus();
	//-->
	</script>
<?php
}

require('include/footer.php');
