<html>
<head>
	<title></title>
	<link rel="stylesheet" href="style.css">
</head>

<body bgcolor="black">
<?php

$img = isset($_GET['img']) ? str_replace('..', '', $_GET['img']) : '';
if (!file_exists($img)) die;

?>
<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<tr valign="middle">
<td align="center">
	<a href="javascript:window.close()">
	<img src="<?php echo $img; ?>" hspace="0" vspace="0" align="center" border="0" />
	</a>
</td>
</tr>
</table>

</body>
<html>
