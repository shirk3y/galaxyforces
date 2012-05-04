<?php

require('include/header.php');

locale('website/links');

tablebegin('Galaxy Forces', 500);


echo "\t<h3>${Lang['FriendsInCrime']}</h3>\n\n";

//echo "\t<a href=\"http://galaxy.help.prv.pl/\"><img src=\"propaganda/banners/galaxyhelp.200x60.gif\"></a><br />\n\t<br />\n";
//echo "\t<a href=\"http://sfm.galaxy.prv.pl/\"><img src=\"propaganda/banners/sfm.200x60.gif\"></a><br />\n\t<br />\n";
//echo "\t<a href=\"http://a3.affe.pl/\"><img src=\"propaganda/banners/a3.200x120.gif\"></a><br />\n\t<br />\n";
echo "\t<a href=\"http://grayscale.scene.pl/\"><img src=\"propaganda/banners/grayscale.120x24.gif\"></a><br />\n\t<br />\n";
echo "\t<a href=\"http://www.scene.pl/\"><img src=\"propaganda/banners/scenepl.60x30.jpg\"></a><br />\n\t<br />\n";
echo "\t<a href=\"http://www.auralplanet.com/\"><img src=\"propaganda/banners/auralplanet.120x60.jpg\"></a><br />\n\t<br />\n";
//echo "\t<a href=\"http://www.korosulame.prv.pl/\"><img src=\"propaganda/banners/korosulame.130x96.gif\"></a><br />\n\t<br />\n";

tablebreak();


echo "\t<h3>${Lang['OtherStuff']}</h3>\n\n";

echo "\t<a href=\"http://www.scansoft.com/\"><img src=\"propaganda/banners/scansoft.100x30.gif\"></a><br />\n\t<br />\n";
echo "\t<a href=\"http://www.ivo.pl/\"><img src=\"propaganda/banners/ivona.80x30.gif\"></a><br />\n\t<br />\n";
echo "\t<a href=\"http://getfirefox.com/\"><img src=\"propaganda/banners/firefox.178x60.png\"></a><br />\n\t<br />\n";

tableend('Galaxy Forces', 500);

require('include/footer.php');
