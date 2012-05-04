<?php
	$index = 'emots';
	$auth = true;

	require('include/header.php');

	tablebegin($Lang['Emoticons'], 500);
	subbegin();
?>

<div align="center">

<?php
$thumb_title = 1;  // set to 1 if you want the image filename below the thumb, 0 otherwise

// full size images title
$full_title = 1;  // set to 1 if you want the image filename below the the full size image, 0 otherwise

// how many thumbnails should appear per row?
$cols = 4;  // 3 looks the best, but you may want more or less, depending on the size of your thumbnails

// how many thumbnails per page?
$max_thumbs = 0;  // a multiple of $cols looks the best, or 0 for all thumbnails on one page

// thumbnail directory name
$thumbs_dir = "images/smiles";  // just make sure your directory name is inside double quotes

// full size image directory name
$full_dir = "images/smiles";  // just make sure your directory name is inside double quotes

// captions directory name
$captions_dir = "images/smiles";  // if you don't want captions at all, don't worry about this

// extension name
$ext = "php";  // just incase you are using a different extension name for your script; if your server is not set for "index.$ext to be the index page, put "0".

// captions extension
$cext = "inc";  // use whatever you're comfortable with

// show random option for single page view
$showrand = 0;  // to turn it off, switch 1 to 0

// print footer options
$print_footer = "print_footer"; // put in the name of the function you use to print the footer of your page.  if you don't use one, just leave it as it is.

/********** end editable variables **********/

// figure out this script's name
$self = $HTTP_SERVER_VARS['PHP_SELF'];

if (basename($self) == "index.$ext") {
	$self = str_replace(basename($self), "", $self);
}

// do you have an existing function to close your page?  if not, use this default...
if (!function_exists($print_footer)) {
	function print_gallery_footer() {
?>

</body>
</html>
<?php
	}
	$print_footer = 'print_gallery_footer';
}

// our error function, cleanly exits the script on user errors
function imgerror($error) {
	global $print_footer;
	print "<p><b>$error</b></p>\n\n";
	$print_footer();
	exit();
}

// get image size function
function gallery_imgsize($image) {
	$size = GetImageSize($image);
	return "width=$size[0] height=$size[1]";
}

// check for directories
if(!is_dir($thumbs_dir)) {
  imgerror('Directory "'.$thumbs_dir.'" does not exist.');
}
if(!is_dir($full_dir)) {
  imgerror('Directory "'.$full_dir.'" does not exist.');
}

// get contents of $thumbs_dir
$dir = @opendir($thumbs_dir) or imgerror('Can\'t open ' . $thumbs_dir . ' directory');
$thumbs = array();
while($thumb = readdir($dir)) {
	if(preg_match('/(jpg$|jpeg$|gif$|tif$|bmp$|png$)/', $thumb))
		array_push($thumbs, $thumb);
}

sort($thumbs);

// lowest displayed image in the array
// use http_get_vars incase register_globals is off in php.ini
if (!isset($HTTP_GET_VARS['i'])) {
	$i = 0;
}
else {
	$i = $HTTP_GET_VARS['i'];
}

// check to see if all thumbs are meant to be displayed on one page
if ($max_thumbs == 0) {
	$max_thumbs = sizeof($thumbs);
	$mt_check = 1;
}
else {
	$mt_check = 0;
}

// thumbnail view
if (is_numeric($i)) {
	// check to see which thumbnail to start with
	if (!$mt_check && $i > 0) {
		$start = $max_thumbs * ($i - 1);
	}
	else {
		$start = 0;
	}
	// are they looking for thumbs pages that don't exist?
	if ($start > sizeof($thumbs)) {
		print '<a href="' . $self . '">index</a>' . "\n\n";
		imgerror('Sorry, there are no images to display on this page');
	}
?>
<table width="80%" cellspacing=0 cellpadding=10 border=0>

<tr>
<?php
	// loop through $thumbs and display $max_thumbs per page
	for($count = 1; $count <= $max_thumbs; $start++) {
		// break if past max_thumbs
		if ($start >= sizeof($thumbs)) {
			break;
		}
		
		// print new row after predefined number of thumbnails
   	if(($count % $cols == 1) && $count != 1 && $cols > 1) {
			print "</tr>\n\n<tr>\n";
		}
		else if ($cols == 1) {
			print "</tr>\n\n<tr>\n";
		}
		
   	// open cell
		print '<td align="center" width="' . (floor(100 / $cols)) . '%">';
		
   	// insert thumb
		print '<a href="' . $self . '?i=' . rawurlencode("$thumbs[$start]") . '"><img src="' . $thumbs_dir . '/' . rawurlencode("$thumbs[$start]") . '" ';
		print gallery_imgsize("$thumbs_dir/$thumbs[$start]");
		
		// alt information
		print ' alt="Wait ' . $thumbs[$start] . '"></a>';
		
		// image title
   	if($thumb_title) {
			$title = explode(".", str_replace("Icon_", "",  ucfirst($thumbs[$start])));
			print "\n<br><a href=\"${_SERVER['PHP_SELF']}?chat=:${title[0]}:\">:${title[0]}:";
		}
		
		// close cell
		// supress line break for screen readers, but force a line break for lynx
		print '<br style="visibility: hidden; volume: silent"></td>' . "\n";
		$count++;
	}
?>
</tr>

</table>
<?php
	// thumbs page nav
	if (!$mt_check) {
		print "\n<p>";
		// how many total thumbs pages, including a "remainder" page if needed
		$pages = ceil(sizeof($thumbs) / $max_thumbs);
		for ($count = 1; $count <= $pages; $count++) {
			if ($count == 1) {
				if ($count == $i || $i == 0) {
					print $count;
				}
				else {
					print "<a href=\"$self\">$count</a>";
				}
			}
			else {
				if ($count == $i) {
					print " | $count</a>";
					}
				else {
					print " | <a href=\"$self?i=$count\">$count</a>";
				}
			}
		}
		print '</p>';
	}
}


// single image view
else if (file_exists("$full_dir/$i")) {
	// find where it is in the array
	$key = array_search($i, $thumbs);
	if (is_null($key)) {
		$key = -1;
	}

	// navigation
	print '<p>';
	// previous
	if($key >= 1) {
		print '<a href="' . $self . '?i=' . rawurlencode($thumbs[$key - 1]) . '">&laquo; previous</a> | ';
	}
	else {
		print '&laquo; previous | ';
	}
	// index
	print '<a href="' . $self . '">index</a>';
	// random
	if ($showrand != 0) {
		$random = array_rand($thumbs, 2);
		print ' | <a href="' . $self . '?i=' . rawurlencode($thumbs[$random[0]]) . '">random</a>';
	}
	// next
	if($key != (sizeof($thumbs) - 1)) {
		print ' | <a href="' . $self . '?i=' . rawurlencode($thumbs[$key + 1]) . '">next &raquo;</a>';
	}
	else {
		print ' | next &raquo;';
	}
	print "</p>\n\n";
	// image
	print '<img src="' . $full_dir . '/' . rawurlencode($i) . '" ';
	print gallery_imgsize("$full_dir/$i");
	
	// alt information
	print ' alt="';
  if(!$full_title) {
		print $i;
	}
	print "\" border=0>\n\n";

	if($full_title) {
		$title = explode(".", str_replace("_", " ", ucfirst($i)));
		print "<div class=\"fulltitle\">$title[0]</div>\n\n";
	}

	// numerically show what image it is in the series; hide this if image isn't in the series
	if ($key >= 0) {
		// add 1 so that the first image is image 1 in the series, not 0
		print '<div class="series">' . ($key + 1) . ' of ' . sizeof($thumbs) . "</div>\n\n";
	}

	// caption (optional)
	if (file_exists("$captions_dir/$i.$cext")) {
		print '<div class="caption">';
		require("$captions_dir/$i.$cext");
		print '</div>';
	}
}

// no image found
else {
?><p><a href="<?php echo $self; ?>">index</a></p>

<?php
	imgerror('Sorry, that image does not exist...');
}

	subend();
	tableend('Galaxy Forces', 500);

	require('include/footer.php');
