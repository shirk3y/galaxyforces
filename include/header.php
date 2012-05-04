<?php

// ===========================================================================
// HTML Header {header.php}
// ===========================================================================

include('include/common.php');
include('include/style.php');

// ---------------------------------------------------------------------------
// HEAD
// ---------------------------------------------------------------------------

echo "<html>\n<head>\n\t<title>[ ${Config['Title']}" . (@$title ? " - $title" : '') . " ]</title>\n";
if (@$Lang['Charset']) echo "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=${Lang['Charset']}\">\n";
if (@$Config['Refresh']) echo "\t<meta http-equiv=\"refresh\" content=\"${Config['Refresh']}; url=${_SERVER['PHP_SELF']}\" />\n";
if (@$Config['Cache']) echo "\t<meta name=\"pragma\" content=\"${Config['Cache']}\" />\n";
if (@$Config['Author']) echo "\t<meta name=\"author\" content=\"${Config['Author']}\" />\n";
if (@$Config['Description']) echo "\t<meta name=\"description\" content=\"${Config['Description']}\" />\n";
if (@$Config['Generator']) echo "\t<meta name=\"generator\" content=\"${Config['Generator']}\" />\n";
if (@$Config['Keywords']) echo "\t<meta name=\"keywords\" content=\"${Config['Keywords']}\" />\n";
if (@$Config['Stylesheet']||@$Style['Stylesheet']) {
	foreach (array_merge(array(@$Style['Stylesheet']), array(@$Config['Stylesheet'])) as $css) {
		if (empty($css)) continue;
?>	<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>" />
<?php
	}
}
if (@$Config['ShortcutIcon']) echo "\t<link rel=\"shortcut icon\" href=\"${Config['ShortcutIcon']}\" />\n";

// ---------------------------------------------------------------------------
// CUSTOM STUFF
// ---------------------------------------------------------------------------

?>	<link rel="alternate" type="application/rss+xml" title="Chatlog" href="<?php echo @$Config["URL"]; ?>rss/chat" />
	<link rel="alternate" type="application/rss+xml" title="News" href="<?php echo @$Config["URL"]; ?>rss/news" />
	<meta http-equiv="Page-Enter" content="blendTrans(Duration=1.0)" />
	<meta http-equiv="Page-Exit" content="blendTrans(Duration=0.5)" />
</head>

<?php

// ---------------------------------------------------------------------------
// SCRIPTS
// ---------------------------------------------------------------------------

if (!empty($JS)) {
	$JS=is_array($JS)?array_unique($JS):array($JS);
	for ($i = 0; $i < count($JS); $i++) {
?>
<script language="javascript" src="js/<?php echo htmlspecialchars($JS[$i]); ?>.js"></script>

<?php
	}
	
}

// ---------------------------------------------------------------------------
// HEAD
// ---------------------------------------------------------------------------

if (is_array(@$HEAD))
{
	foreach ($HEAD as $e) {
		if (!$e=trim($e)) continue;
		echo $e;
		echo "\n\n";
	}
}
	

// ---------------------------------------------------------------------------
// BODY
// ---------------------------------------------------------------------------
?><body>

<?php

locale('website/menu');

include(STYLE.'header.php');
