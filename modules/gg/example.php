<?php

$Config['module.gg.user']='19463577';
$Config['module.gg.secret']='secret';

$Config['module.gg.debug'] = true;

require("include.php");

//ggmessage('0', 'test z bota');

for ($i=1; $i<=100; $i++) {
	$r=0;
	while (!ggmessage('3058406', 'test z bota http://galaxy.alyx.pl/ '.$i)) if (++$r>10) break;
}
