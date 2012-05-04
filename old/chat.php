<?php

$auth = 1;

include("include/common.php");

if (isset($_GET['popup'])) {
	if (!$logged) die;
	include('include/style.php');

?><html>
<head>
	<title></title>
	<meta http-equiv="refresh" content="15; url=chat.php?RID=<?php echo $rid; ?>&popup" />
	<link rel="stylesheet" href="style/default/chat.css" />
<?php

	if (@$Lang['Charset']) echo "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=${Lang['Charset']}\">\n";

?></head>
<body>

<script>
<!--

function reload()
{
	location.href='<?php echo "chat.php?RID=$rid&popup"; ?>';
}

settimeout("reload()", 10000);

//-->
</script>

<?php
	chatbox(100,1,0);
?>
</body>
</html>
<?php
	die;
}
else {
	for ($i = 0; $i < count($Modules); $i++) if ($Modules[$i] == 'chat') unset ($Modules[$i]);

	$Config['IgnoreFrames'] = 1;
	
	include("include/header.php");
	
	tablebegin($Lang['Chat'], 500);
	
?>	<br />

	<table width="460" cellspacing="0" cellpadding="0">
	<tr>
	<td align="left">
	<form action="chat.php" method="GET">
	<input type="hidden" name="rid" value="<?php echo $rid; ?>" />
	<input type="text" name="chat" size="48" />
	</td>
	<td align="right">
	<input type="submit" value="<?php echo $Lang['ChatPost']; ?>" />
	</form>
	</td>
	</table>
	
	<br />
	<iframe name="popup" frameborder="0" width="460" height="300" refresh="0" src="chat.php?popup&RID=<?php echo $rid; ?>"></iframe>
	<br /><br />

<?php	
	tableend($Lang['Chat']);
	
	include("include/footer.php");
}
