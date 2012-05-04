
You can create local.css here and add this to your include/config.php to be included just after style includes its own.

	$Config['Stylesheet']="style/local.css";	// path relative to $ROOT

If you use different CSS files, you may consider using $Config['Stylesheet'] as an array:

	$Config['Stylesheet'][]="style/1.css";
	$Config['Stylesheet'][]="style/2.css";
	$Config['Stylesheet'][]="style/3.css";

	