<?php

// ===========================================================================
// Profile {profile.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.2
//	Modified:	2005-11-19
//	Author(s):	zoltarx, sharkpp
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$auth = true;
$index = 'profile';

require('include/header.php');

locale('website/profile');

$User = $Player;

// ===========================================================================
// DELETE
// ===========================================================================

if ($action == 'deleteaccount') {
	if ($confirm = getvar('confirm')) {
	 	tablebegin($Lang['Profile'], '400');

?>	<br />
	<?php echo $Lang['AccountDeleted']; ?><br />
	<br />
	<a href="control.php"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
		tableend("<a href=\"control.php\">${Lang['GoBack']} &gt;&gt;</a>");
	}
	else {
		tablebegin('<font class="work">' . $Lang['Confirmation'] . '</font>', 500);

?>	<br />
	<b><?php echo $Lang['DeleteAccount']; ?>!</b><br />
	<br />
	<font class="error"><?php echo $Lang['RUSure']; ?></font><br />
	<br />
	<a href="profile.php?action=deleteaccount&confirm=<?php echo $secret; ?>" class="delete"><?php echo $Lang['Yes']; ?></a>&nbsp; &nbsp; &nbsp; &nbsp;<a href="profile.php"><?php echo $Lang['No']; ?></a><br />
	<br />
<?php
		tableend('<a href="profile.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
	}
}

// ===========================================================================
// CHANGE PASSWORD
// ===========================================================================

elseif ($action == 'changepassword') {
	$new = escapesql(strip_tags(@$_POST['new']));
	$reenter = escapesql(strip_tags(@$_POST['reenter']));
	if (strlen($new) < 6) $errors .= $Lang['ErrorPasswordTooShort'] . ' (' . $Lang['mustcontainatleast'] . ' 6 ' . $Lang['chars'] . ')' . '<br />';
	if ($new != $reenter) $errors .= $Lang['ErrorPasswordAndReenter'] . '<br />';
	if (! $errors) {
		$new = md5($new);
		$db->query("UPDATE `${prefix}users` SET `password`='$new' WHERE `login`='$login' LIMIT 1;");

	 	tablebegin($Lang['Profile'], '400');
?>	<br />
	<b><?php echo $Lang['EditProfile']; ?></b><br />
	<br />
	<?php echo $Lang['PasswordChanged']; ?><br />
	<br />
	<a href="profile.php"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
		tableend("<a href=\"control.php\">${Lang['GoBack']} &gt;&gt;</a>");
	}
}

// ===========================================================================
// VALIDATE
// ===========================================================================

elseif ($action == 'validate') {
	$email = escapesql(strip_tags(@$_POST['email']));
	$reemail = escapesql(strip_tags(@$_POST['reemail']));
	$language = escapesql(strip_tags(@$_POST['language']));
	$gg = escapesql(strip_tags(@$_POST['gg']));
	$ggpublic = (@$_POST['ggpublic'] ? 1 : 0);
	$soundsoff = (@$_POST['soundsoff'] ? 1 : 0);
	$antispam = (@$_POST['antispam'] ? 1 : 0);
	$www = escapesql(strip_tags(@$_POST['www']));

	if (!$email || $email != $reemail) $errors .= $Lang['ErrorEmailMustBeValid'] . '<br />';

	if (!$errors) {
		$db->query("UPDATE ${prefix}users SET email='$email',language='$language',gg='$gg',ggpublic='$ggpublic',www='$www',soundsoff='$soundsoff',antispam='$antispam' WHERE login='$login';");
		$User['email'] = $email;
		$User['language'] = $language;
		$User['gg'] = $gg;
		$User['www'] = $www;
		$User['soundsoff'] = $soundsoff;
		$User['antispam'] = $antispam;

	 	tablebegin($Lang['Profile'], '400');

?>	<br /><font class="h3"><?php echo $Lang['EditProfile']; ?></font><br />
	<br />
	<?php echo $Lang['ProfileChanged']; ?><br />
	<br />
	<a href="profile.php"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
		tableend("<a href=\"control.php\">${Lang['GoBack']} &gt;&gt;</a>");
	}
}

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');

?>		<br />
		<font class="error"><?php echo $errors; ?></font>
		<br />
		<a href="javascript:history.back(1)"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
		<br />
<?php
	tableend($Lang['Profile']);
}
elseif (($action != 'changepassword') && ($action != 'validate') && ($action != 'deleteaccount')) {
 	tablebegin($Lang['Profile'], '400');

?>	<br />
	<font class="h3"><?php echo $Lang['EditProfile']; ?></font><br />
<?php
	subbegin();

?>	<p />
	<font class="result"><?php echo $Lang['ProfileDescription']; ?></font><br />
<?php
	subend();

	tablebreak();

?>	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="form">
	<table background="images/table.jpg" id="form" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><img src="images/0.gif" width="160" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="160" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['Password']; ?>:</td>
	<td><input type="password" maxlength="16" name="new" /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['Reenter']; ?>:</td>
	<td><input type="password" maxlength="16" name="reenter" /></td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	<tr>
	<td colspan="2"><input type="hidden" name="action" value="changepassword" /><center><input type="submit" value="<?php echo $Lang['ChangePassword']; ?>" /></center></td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
        </table>
	</form>
<?php
	tablebreak();
?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="form">
	<table background="images/table.jpg" id="form" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><img src="images/0.gif" width="160" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="160" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['Email']; ?>:</td>
	<td><input type="text" maxlength="32" name="email" value="<?php echo $User['email']; ?>" /></td>
	</tr>
	<tr>
	<tr>
	<td><?php echo $Lang['EmailReenter']; ?>:</td>
	<td><input type="text" maxlength="32" name="reemail" value="<?php echo $User['email']; ?>" /></td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	<tr>
	<td><?php echo $Lang['Language']; ?>:</td>
	<td>
		<select name="language">
		<option value="en"<?php echo ($User['language'] == 'en' ? ' selected="selected"' : ''); ?>><?php echo $Lang['English']; ?> (en)</option>
		<option value="pl"<?php echo ($User['language'] == 'pl' ? ' selected="selected"' : ''); ?>><?php echo $Lang['Polish']; ?> (pl)</option>
		</select>
	</td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	<tr>
	<td><a href="javascript:alert('<?php echo $Lang['GG#']; ?>')">GG#</a>:</td>
	<td><input type="text" maxlength="64" name="gg" value="<?php echo $User['gg']; ?>" /></td>
	</tr>
	<tr>
	<td><a href="javascript:alert('<?php echo $Lang['WWW']; ?>')">WWW</a>:</td>
	<td><input type="text" maxlength="64" name="www" value="<?php echo $User['www']; ?>" /></td>
	</tr>
	<tr><td colspan=2><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td></tr>
	<tr>
	<td colspan="2"><input name="ggpublic" type="checkbox"<?php echo ($User['ggpublic'] ? ' checked' : ''); ?>><?php echo $Lang['PublicGG']; ?></input></td>
	</tr>
	<tr>
	<td colspan="2"><input name="soundsoff" type="checkbox"<?php echo ($User['soundsoff'] ? ' checked' : ''); ?>><?php echo $Lang['SoundsOff']; ?></input></td>
	</tr>
	<tr>
	<td colspan="2"><input name="antispam" type="checkbox"<?php echo ($User['antispam'] ? ' checked' : ''); ?>><?php echo $Lang['Antispam']; ?></input></td>
	</tr>
	<tr><td colspan=2><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td></tr>
	<tr>
	<td colspan="2"><input type="hidden" name="action" value="validate" /><center><input type="submit" value="<?php echo $Lang['Validate']; ?>" /></center></td>
	</tr>
	<tr>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	<td><img src="images/0.gif" width="1" height="8" hspace="0" vspace="0" border="0" /></td>
	</tr>
	</table>
<?php
	tablebreak();

?>	<br />
	<a class="delete" href="profile.php?action=deleteaccount"><?php echo $Lang['DeleteAccount']; ?> &gt;&gt;</a><br />
	<br />
<?php
	tableend("<a href=\"control.php\">${Lang['GoBack']} &gt;&gt;</a>");

?>	</form>

	<script>
	<!--
		document.form.login.focus();
	//-->
	</script>
<?php
}

require('include/footer.php');
