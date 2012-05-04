<?php

global $db, $prefix, $logged, $Lang, $Config;

if (@$db && $logged && $db->query("SELECT login,usergroup,clan FROM ${prefix}users WHERE online>'".date('YmdHis', time() - 300)."' ORDER BY login;")) {
	tablebegin($Lang['Online']);

	echo '<br />';

	$max = $db->numrows();
	$break = $max > 10 ? 0 : 1;
	$i = 0;

	while ($t = $db->fetchrow()) {
		switch($t['usergroup']) {
			case $Config['Administrators']: $class = 'wheel'; break;
			case $Config['Moderators']: $class = 'capacity'; break;
			case $Config['Forum']: $class = 'forum'; break;
			case $Config['JailChief']: $class = 'jailchief'; break;
			default:
				if ($t['clan'] ==  'Kolonia Karna') $class = 'jail';
				elseif ($t['clan']) $class = 'result';
				else $class = 'plus';
		}
		$class = $class ? " class=\"$class\"" : '';
		$i++;
	 	echo "<a href=\"whois.php?name={$t['login']}\"{$class}>{$t['login']}</a>".($break ? '<br />' : ($i < $max ? ', ' : ''));
 	}
 	
 	echo $i ? ($break ? '<br />' : '<br /><br />') : $Lang['NoUsers'].'<br /><br />';

	tableend("$i ${Lang['User(s)']}");
}
