<?php

require('include/header.php');

tablebegin("GF ${Lang['MenuPropaganda']}", 500);

?>	<script>
	<!--

	function view($name, $title) {
		$whatever = window.open('view.php?img='+$name+'&title='+$title,'JavaScript'+$name,'toolbar=no,menubar=no,location=no,personalbar=no,scrollbars=no,directories=no,status=no,resizable=yes,width=760,height=560');
	}

	//-->
	</script>

	<br />
	<b><?php echo $Lang['Wallpapers']; ?></b><br />
	<br />
<?php

$files = readfiles('propaganda/wallpapers');

foreach($files as $n) {
	echo "\t<a href=\"javascript:view('$n', '')\">\n";
	tableimg('images/pw.gif', 72, 72, $n, 64, 64);
	echo "\t</a>\n";
}

echo "\t<br />\n";

tableend('Galaxy Forces', 500);

require('include/footer.php');
