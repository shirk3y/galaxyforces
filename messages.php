<?php

// ===========================================================================
// Messages {messages.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.4
//	Modified:	2005-11-12
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'messages';
$auth = true;

$js[] = 'e107';
$js[] = 'functions';

require("include/header.php");
require("include/messages.php");

$index = (int)getvar('index');
$view = getvar('view');
$category = getvar('category');
$page = (int)getvar('page');
$checkall = getvar('checkall');

$to = escapesql(strip_tags(getvar('to')));
$subject = trim(strip_tags(postvar('subject')));

$pagecount = 50;

$pagename = $Lang['Messages'];

switch ($category) {
	case 'from': $order = "`from`,`timestamp` DESC"; break;
	case 'fromdesc': $order = "`from` DESC,`timestamp` DESC"; break;
	case 'subject': $order = "`subject`,`timestamp` DESC"; break;
	case 'subjectdesc': $order = "`subject` DESC,`timestamp` DESC"; break;
	case 'timedesc': $order = "`timestamp`"; break;
	case 'time':
	default: $order = "`timestamp` DESC"; break;
}

// ===========================================================================
// BROADCAST
// ===========================================================================

if ($action == 'broadcast') {
	if (isset($_POST['checkbox']) && $_POST['checkbox']) {
		for ($i = 0; $i < count($_POST['checkbox']); $i++)
			if (! $i) $to = $_POST['checkbox'][$i];
			else $to .= ',' . $_POST['checkbox'][$i];
	}
	$action = 'compose';
}

// ===========================================================================
// SEND
// ===========================================================================

elseif ($action == 'send') {
	$subject = strip_tags(postvar('subject'));
	$message = strip_tags(postvar('message'));
	$list = explode(',', $to);
	
	for ($i = 0; $i < count($list); $i++) {
		$to = trim(strip_tags($list[$i]));
		if (!sendmessage($subject, $message, $login, $to)) $errors .= $Lang['ErrorLoginNotExists'].' '.$to.'!<br />';
	}
	$result .= $Lang['MessageSentSuccesfully'].'<br />';
}

// ===========================================================================
// MARK
// ===========================================================================

elseif ($action == 'markallread') $db->query("UPDATE `${prefix}messages` SET `read`=1 WHERE `to`='$login';");

// ===========================================================================
// DELETE
// ===========================================================================

elseif ($action == 'delete') {
	if ($Config['MessageLife']) $db->query("DELETE FROM `${prefix}messages` WHERE `timestamp`<'".date('Ymd', mktime(0, 0, 0, date("m"), date("d") - $Config['MessageLife'], date("Y"))).'000000'."';");
	if ($index == 'all') $db->query("DELETE FROM `${prefix}messages` WHERE `to`='$login';");
	else $db->query("DELETE FROM `${prefix}messages` WHERE `to`='$login' AND `id`='$index';");
}

// ===========================================================================
// DELETESELECTED
// ===========================================================================

elseif ($action == 'deleteselected') {
	if ($Config['MessageLife']) $db->query("DELETE FROM `${prefix}messages` WHERE `timestamp`<'".date('Ymd', mktime(0, 0, 0, date("m"), date("d") - $Config['MessageLife'], date("Y"))).'000000'."';");
	for ($i = 0; $i < count(@$_POST['checkbox']); $i++) @$sql .= ($sql ? ' OR ' : '')."`id`='".$_POST['checkbox'][$i]."'";
	$sql = "DELETE FROM `${prefix}messages` WHERE `to`='$login' AND (".$sql.");";
	$db->query($sql);
}

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<br />\n\t\t<font class=\"h3\">${Lang['ErrorProblems']}</font><br />\n\t\t<br />\n\t\t<font class=\"error\">$errors</font>\n\t\t<br />\n\t\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br />\n";
	echo "\t\t<br />\n";
	sound('error');
	tableend("<a href=\"messages.php?page=$page&category=$category&rid=$rid\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// RESULT
// ===========================================================================

elseif ($result) {
	tablebegin($pagename, 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br />";
	tableend("<a href=\"messages.php?page=$page&category=$category&rid=$rid\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// COMPOSE
// ===========================================================================

elseif ($view == 'compose' || $view == 'reply') {
	require('include/editor.php');

	if ($view == 'reply') {
		$db->query("SELECT `from`,`subject` FROM `${prefix}messages` WHERE `id`='$index' AND `to`='$login';");
		if ($t = $db->fetchrow()) {
			$subject = "Re: ${t['subject']}";
			$to = $t['from'];
		}
	}
	else {
		if (! isset($to)) $to = getvar('to');
		$subject = '';
	}

	echo "\t<form action=\"messages.php\" method=\"POST\" name=\"form\"><input type=\"hidden\" name=\"action\" value=\"send\" />\n";

	tablebegin($Lang['Messages'], 500);

	echo "\t<br /><font class=\"h3\">${Lang['ComposeMessage']}</font><br />\n";
?>	<table id="form" align="center" cellspacing="0" cellpadding="0" border="0">
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td><b><?php echo $Lang['To:']; ?></b></td>
	<td>&nbsp;</td>
	<td><input type="text" size="30" maxlength="512" name="to" <?php echo $to ? "value=\"$to\" " : ''; ?>/></td>
	</tr>
	<tr>
	<td><b><?php echo $Lang['Subject:']; ?></b></td>
	<td>&nbsp;</td>
	<td><input type="text" size="60" maxlength="240" name="subject" <?php echo $subject ? "value=\"$subject\" " : ''; ?>/></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td><b><?php echo $Lang['Message:']; ?></b></td>
	<td>&nbsp;</td>
	<td>
<?php
	editor('message', 72, 20, 'left');
?>
	</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td colspan="3">
		<center><input type="submit" value="<?php echo $Lang['Send']; ?>" /></center>
	</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	</table>
<?php
	tableend('<a href="messages.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
?>
	</form>

	<script>
	<!--
		document.form.<?php echo $to ? $subject ? 'message' : 'subject' : 'to'; ?>.focus();
	//-->
	</script>
<?php
}

// ===========================================================================
// READ
// ===========================================================================

elseif ($view == 'read') {
	require('include/bbcode.php');

	$db->query("SELECT * FROM `${prefix}messages` WHERE `to`='$login' ORDER BY $order;");
	$max = $db->numrows();
	while ($t = $db->fetchrow()) {
		if ($t['id'] == $index) {
			if ($next = $db->fetchrow()) $next = $next['id'];
			if (! $t['read']) $db->query("UPDATE `${prefix}messages` SET `read`=1 WHERE `id`='$index' LIMIT 1;");
			$found = TRUE;
			break;
		}
		$previous = $t['id'];
	}
	if (@$found) {
		tablebegin("${Lang['Message from']}: <a href=\"whois.php?name=${t['from']}&rid=$rid\"><b>${t['from']}</b></a>", 500);
		subbegin();
		echo "\t<b>${Lang['Subject']}</b>: <font class=\"result\">".emoticons($t['subject'])."</font><br />\n\t\t<br />\n";
		echo "\t" . emoticons(bbcode($t['message']));
		subend();

		echo "\t<a href=\"messages.php?page=$page&category=$category&rid=$rid\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />\n";

		tableend((@$previous ? "<a href=\"messages.php?view=read&page=$page&category=$category&rid=$rid&index=$previous\">&lt;&lt;&nbsp;${Lang['Previous']}</a>" : "&lt;&lt;&nbsp;${Lang['Previous']}") . " &nbsp; &nbsp; <a class=\"delete\" href=\"messages.php?action=delete&page=$page&category=$category&rid=$rid&index=$index\">${Lang['Delete']}</a> &nbsp; &nbsp; " . ($t['type'] == 'report' ? $Lang['Reply'] : "<a href=\"messages.php?view=reply&page=$page&category=$category&rid=$rid&index=$index\">${Lang['Reply']}</a>") . ' &nbsp; &nbsp; ' . ($next ? "<a href=\"messages.php?view=read&page=$page&category=$category&rid=$rid&index=$next\">${Lang['Next']}&nbsp;&gt;&gt;</a>" : "${Lang['Next']} &gt;&gt;"));
	}
	else {
		$errors .= "${Lang['ErrorAccessDenied']}<br />";
		tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
		echo "\t\t<br />\n\t\t<font class=\"h3\">${Lang['ErrorProblems']}</font><br />\n\t\t<br />\n\t\t<font class=\"error\">$errors</font>\n\t\t<br />\n\t\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br />\n";
		echo "\t\t<br />\n";
		sound('error');
		tableend("<a href=\"messages.php?page=$page&category=$category&rid=$rid\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
	}
}

// ===========================================================================
// LIST
// ===========================================================================

else {
	tablebegin($pagename);

	$db->query("SELECT `id` FROM `${prefix}messages` WHERE `to`='$login';");
	$max = $db->numrows();

	if ($page > $m = floor(($max - 1) / $pagecount)) $page = $m;
	$l = $page * $pagecount;

	$db->query("SELECT `id`,`type`,`read`,`from`,`subject`,`timestamp` FROM `${prefix}messages` WHERE `to`='$login' ORDER BY $order LIMIT $l,$pagecount;");

	if ($db->numrows()) {
		echo "\t<br /><font class=\"h3\">${Lang['Messages']}</font><br />\n";
		echo "\t<br />\n";
		echo "\t<form name=\"form\" action=\"messages.php\" method=\"POST\">\n\t<input type=\"hidden\" name=\"action\" value=\"deleteselected\" /><input type=\"hidden\" name=\"page\" value=\"$page\" /><input type=\"hidden\" name=\"category\" value=\"$category\" />\n";
		echo "\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "\t<tr id=\"header\"><td width=\"12\">&nbsp;</td><td width=\"16\">&nbsp;</td><td width=\"12\">&nbsp;</td><td width=\"16\">&nbsp;</td>";
		echo "<td width=\"80\">".'<a'.($category == 'from' ? ' class="minus"' : ($category == 'fromdesc' ? ' class="result"': ''))." href=\"messages.php?rid=$rid&page=$page&category=".($category == 'from' ? 'fromdesc' : 'from')."\" onmouseover=\"self.status='${Lang['ReverseOrder']}'; return true\" onMouseOut=\"self.status=''; return true\">${Lang['From']}</a>:</td>";
		echo "<td align=\"left\">".'<a'.($category == 'subject' ? ' class="minus"' : ($category == 'subjectdesc' ? ' class="result"': ''))." href=\"messages.php?rid=$rid&page=$page&category=".($category == 'subject' ? 'subjectdesc' : 'subject')."\" onmouseover=\"self.status='${Lang['ReverseOrder']}'; return true\" onMouseOut=\"self.status=''; return true\">${Lang['Subject']}</a>:</td>";
		echo "<td width=\"120\">".'<a'.($category == 'time' ? ' class="minus"' : ($category == 'timedesc' ? ' class="result"': ''))." href=\"messages.php?rid=$rid&page=$page&category=".($category == 'time' ? 'timedesc' : 'time')."\" onmouseover=\"self.status='${Lang['ReverseOrder']}'; return true\" onMouseOut=\"self.status=''; return true\">${Lang['Time']}</a>:</td>";
		echo "<td width=\"16\">&nbsp;</td><td width=\"16\">&nbsp;</td><td width=\"12\">&nbsp;</td></tr>\n";

		while ($t = $db->fetchrow()) {
			$a = '<a '.($t['read'] ? '' : 'class="unread" ')."href=\"messages.php?view=read&page=$page&category=$category&index=${t['id']}&rid=$rid\">";
			if (@$i++ % 2) $class = ' class="div"'; else $class = '';

			echo "\t<tr$class><td>&nbsp;</td>";
			echo '<td align="left"><input class="icon" type="checkbox"'.($checkall ? ' checked' : '').' name="checkbox[]" value="'.$t['id'].'"></input></td>';
			echo '<td></td>';
			echo "<td>$a<img class=\"icon\" src=\"images/".($t['read'] ? 'read' : 'unread').'.gif" alt="'.($t['read'] ? ' ' : 'N').'"></a></td>';
			echo "<td align=\"center\">$a${t['from']}</a></td>";
			echo "<td>$a".emoticons($t['subject'])."</a></td>";
			echo "<td align=\"center\">$a<font class=\"small\">".timestampdate($t['timestamp']).'</font> &nbsp; '.timestamptime($t['timestamp'])."</a></td>";
			echo "<td><a href=\"messages.php?action=delete&index=${t['id']}&page=$page&category=$category&rid=$rid\"><img class=\"icon\" src=\"images/delete.gif\" alt=\"X\"></a></td>";
			if ($t['type'] == 'message') echo "<td width=\"16\"><a href=\"messages.php?view=reply&page=$page&category=$category&index=${t['id']}&rid=$rid\"><img src=\"images/reply.gif\" alt=\"R\" class=\"icon\" /></a></td>";
			else echo "<td><img src=\"images/noreply.gif\" alt=\"-\" class=\"icon\" /></a></td>";
			echo "<td>&nbsp;</td></tr>\n";
		}
		echo "\t</table>\n\t</form>\n";
		echo "\t<script>\n\t<!--\n\tfunction deleteselected()\n\t{\n\t\tif (confirm('${Lang['AreYouSure?']}')) form.submit();\n\t}\n\t//-->\n\t</script>\n";

		echo "\t<br /><a href=\"${_SERVER['REQUEST_URI']}&checkall=1\" onclick=\"setcheckboxes('form', 'checkbox[]', true); return false;\">${Lang['SelectAll']}</a> &nbsp;/&nbsp; <a href=\"${_SERVER['REQUEST_URI']}&checkall=0\" onclick=\"setcheckboxes('form', 'checkbox[]', false); return false;\">${Lang['UnselectAll']}</a> &nbsp;/&nbsp; <a href=\"messages.php?action=markallread&page=$page&category=$category\">${Lang['MarkAllRead']}</a><br />\n";
		echo "\t<br /><a class=\"delete\" href=\"javascript:deleteselected()\">${Lang['DeleteSelected']}&nbsp;&gt;&gt;</a><br />\n";
	}
	else {
		echo "\t<br /><font class=\"h3\">${Lang['YBIE']}</font><br />\n";
	}

	echo "\t<br /><a href=\"messages.php?view=compose&rid=$rid\">${Lang['ComposeNewMessage']}&nbsp;&gt;&gt;</a><br />\n";

	echo "\t<br />\n";

	$n = $page;

	$s = ($n > 0 ? "<a href=\"messages.php?category=$category&page=0\">" : '').'&lt;&lt;&nbsp;'.$Lang['Begin'].($n ? '</a>' : '').' &nbsp; ';
	$s .= ($n > 0 ? "<a href=\"messages.php?category=$category&page=".($n - 1).'">' : '').'&lt;&lt;&nbsp;'.$Lang['Previous'].($n ? '</a>' : '').' &nbsp; ';

	$a = $n > 5 ? $n - 5 : 0;
	$b = $n < $m - 5 ? $n + 5 : $m;

	if ($a > 0) $s .= '... ';

	for ($i = $a; $i <= $b; $i++) {
		if ($i != $n) $s .= "<a href=\"messages.php?category=$category&page=$i\">";
		$s .= $i + 1;
		if ($i != $n) $s .= "</a>";
		$s .= ' ';
	}

	if ($b < $m) $s .= ' ...';

	$s .= " &nbsp; ".($n < $m ? "<a href=\"messages.php?category=$category&page=".($n + 1).'">' : '').$Lang['Next'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');
	$s .= ' &nbsp; '.($n < $m ? "<a href=\"messages.php?category=$category&page=$m\">" : '').$Lang['End'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');

	tableend($s);

//	tableend(($Messages ? count($Messages) . $Lang[' messages'] . ' (' . $unreadmessages . $Lang[' unread'] . ') &nbsp; &nbsp; <a class="delete" href="' . $_SERVER['PHP_SELF'] . '?view=delete&index=all&rid=' . $rid . '">' . $Lang['DeleteAll'] . ' &gt;&gt;</a>' : $Lang['No messages']));
}

require('include/footer.php');
