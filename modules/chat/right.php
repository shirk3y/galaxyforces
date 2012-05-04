<?php

global $db, $prefix, $login, $timestamp, $logged, $Lang, $rid, $banned, $locked, $Lang;

if (@$db) {
	tablebegin('<a href="chat.php?RID='.$rid.'">'.$Lang['Chat'].'</a>');

	echo '<div class="chatbox">';
	chatbox(10);
	if ($logged) echo "<hr size=\"1\"><center><a href=\"album.php\" class=\"result\">&raquo&nbsp;${Lang['Emoticons']}&nbsp;&laquo;</a></center>"; // lander & jaco
	echo '</div>';

	tableend(($banned ? "<a href=\"javascript:alert('${Lang['ChatBanned']}')\">${Lang['Banned']}&nbsp;&gt;&gt;</a>" : ($logged ? "<a href=\"javascript:chat()\">" : "<a href=\"javascript:alert('${Lang['ChatCannotPost']}')\">") . "${Lang['ChatPost']}&nbsp;&gt;&gt;</a>"));

?>
<br />

<script>
<!--

function chat()
{
	var m=prompt('<?php echo $Lang['ChatEnterMessage']; ?>:','');
	var t=m;
	if (m>'') {
		m=m.replace(/\+/g,"%2B"); // kot
		m=m.replace(/\&/g,"%26");
		m=m.replace(/\#/g,"%23");
		if (!m) m=t;
		var url='<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>&chat='+m;
		document.location.href = url;
	}
}

//-->
</script>

<?php

}
