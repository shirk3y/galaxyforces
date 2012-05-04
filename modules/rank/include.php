<?php

// -------------------------------------------------------------------
// Ranks
// -------------------------------------------------------------------

function rank_load() {
	global $db, $prefix, $Config;

	$row = explode('|', $Config['Ranks']);
	foreach ($row as $v) {
		$tmp = explode(',', $v);

		$Ranks[ $tmp[0] ]['en_name'] = $tmp[1];
		$Ranks[ $tmp[0] ]['pl_name'] = $tmp[2];
		$Ranks[ $tmp[0] ]['style'] = @$tmp[3];
		$Ranks[ $tmp[0] ]['chat'] = @$tmp[4];
		//list($Ranks[$tmp[0]]['en_name'], $Ranks[$tmp[0]]['pl_name'], $Ranks[$tmp[0]]['style']) = array($tmp[1], $tmp[2], $tmp[3]);
	}
	return $Ranks;
}

function rank_update ($Ranks)
{
	global $db, $prefix;
		
	$packed = '';
	foreach ( $Ranks as $k => $v )
	{
		$packed .= ($packed ? '|' : '').$k.','.implode(',', $v);
	}
	$db->query("UPDATE `${prefix}config` SET `config_value`='".$packed."' WHERE `config_key`='Ranks' LIMIT 1;"); echo mysql_error();
}

function rank_styles() {
	global $Ranks;
	$styles = "	<style type=\"text/css\">\n	<!--\n";

	foreach ($Ranks as $k => $v) {
		$styles .= "		$k{".$v['style']."}\n";
	}
	$styles .= "	-->\n	</style>\n";

	return $styles;
}

function rank($name, $rank = '') {
	global $Ranks;

	if ($rank) {
		if (isset($Ranks[$rank])) {
			return '<font style="'.$Ranks[$rank]['style'].'">'.$name.'</font>';
		}
	}
	return $name;
}
