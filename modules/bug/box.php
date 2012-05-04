<?php 
if (!defined("BOX_BUG"))
{
?>
<div class="module-bug">

<h1><?php echo @$Lang["Environment"]; ?></h1>

<?php

/**
 *
 * This function require an array (rows) of array (column) with output data
 *
 */
function style_table_sheet($table, $id=null)
{
	global $Lang;
	$class="sheet";
?>
<table <?php if (!empty($id)) echo 'id='.htmlspecialchars($id).' '; ?>class="<?php echo htmlspecialchars($class); ?>" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td><?php echo @$Lang["Variable name"]; ?></td>
<td><?php echo @$Lang["Current value"]; ?></td>
</tr>
</thead>
<tbody>
<?php
	ksort($table);
	for ($row=0; $row<count($table); $row++) {
		ksort($table[$row]);
		echo '<tr class="row'.(1+$row%2).'">';
		foreach ($table[$row] as $column=>$value)
		{
			echo '<td>'.$value.'</td>';
		}
		echo '</tr>
';
	}
?></tbody>
</table>
<?php

}

global $State, $Config, $User, $Player, $Lang, $Colony, $Equipment;

$row=0;
$elid=0;
$idpx="sr-";

//foreach (array('_SERVER', '_COOKIE', 'State', 'Config') as $global)

$global = 'Config';

function dump_hash_as_table($hash=array(), $fmt='%s', $sort=true)
{
	$table=array();
	if ($sort) ksort($hash);
	foreach ($hash as $key=>$value)
	{
		ob_start();
		var_dump($value);
		$value=ob_get_contents();
		ob_end_clean();
		$name=sprintf($fmt, $key);
		$value=htmlspecialchars($value);
		$table[]=array($name, substr($value, 0, 100));
	}
	return $table;
}

/*
?>
<div id="Config" class="chapter" onclick="toggleVisibility('tableConfig', true, false);">
<?php
echo sprintf($Lang["Dump of %s"], '<b>$Config</b>');
?>
</div>
<div id="tableConfig" class="hidden">
<?php
	style_table_sheet($table=dump_hash_as_table($Config, '$Config[%s]'));
?>
</div>

<div id="Lang" class="chapter" onclick="toggleVisibility('tableLang', true, false);">
<?php
echo sprintf($Lang["Dump of %s"], '<b>$Lang</b>');

echo '&nbsp;(';
$n=count($Lang);
if ($n==0) echo $Lang["no elements"];
elseif ($n == 1) echo $Lang["one element"];
elseif ($n < 5) echo sprintf($Lang["%d elements{2}"], $n);
elseif ($n < 20 || $n % 10 == 0 || $n % 10 > 4) echo sprintf($Lang["%d elements{5}"], $n);
else echo sprintf($Lang["%d elements{2}"], $n);
echo ')';

?>
</div>
<div style="overflow:hidden; z-index: -1">
<div id="tableLang" class="hidden">
<?php
	style_table_sheet($table=dump_hash_as_table($Lang, '$Lang["%s"]'), '%30s');
?>
</div>
</div>

<?php
*/


foreach (array('User', 'Lang', 'Config', 'Player', 'Colony', 'Equipment') as $global)
{
	if (!isset($$global)) continue;
	$table = (array)$$global;
	if (!count($table)) continue;
?>
<div id="dump<?php echo $global; ?>" class="chapter" onclick="toggleVisibility('table<?php echo $global; ?>', true, false);">
<?php
	echo sprintf($Lang["Dump of %s"], '<b>$'.$global.'</b>');

	echo '&nbsp;(';

	$n=count($table);
	if ($n==0) echo $Lang["no elements"];
	elseif ($n == 1) echo $Lang["one element"];
	elseif ($n < 5) echo sprintf($Lang["%d elements{2}"], $n);
	elseif ($n < 20 || $n % 10 == 0 || $n % 10 > 4) echo sprintf($Lang["%d elements{5}"], $n);
	else echo sprintf($Lang["%d elements{2}"], $n);
	echo ')';
?>
</div>
<div id="table<?php echo $global; ?>" class="hidden">
<?php
	style_table_sheet($table=dump_hash_as_table($table, '$'.$global.'["%s"]'));
?>
</div>

<?php

}

?>
</div>

<?php

define("BOX_BUG", true);

}
