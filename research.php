<?php

// ===========================================================================
// Research {research.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.1
//	Modified:	2005-10-25
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$auth = true;

require('include/header.php');

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<br />\n\t\t<font class=\"h3\">${Lang['ErrorProblems']}</font><br />\n\t\t<br />\n\t\t<font class=\"error\">$errors</font>\n\t\t<br />\n\t\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br />\n";
	echo "\t\t<br />\n";
	sound('error');
	tableend('<a href="research.php">'.$Lang['GoBack'].'&nbsp;&gt;&gt;</a>');
}

// ===========================================================================
// RESULT
// ===========================================================================

elseif ($result) {
	tablebegin($pagename, 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br />";
	tableend('<a href="research.php">'.$Lang['GoBack'].'&nbsp;&gt;&gt;</a>');
}
 
// ===========================================================================
// RESEARCH
// ===========================================================================

elseif (@$Colony && ($Colony['laboratory'] || $Colony['databank'])) {
	if ($Research) {
		tablebegin($Lang['Research'], 500);

?>	<h3><?php echo $Lang['Researching']; ?></h3>

	<font class="result"><?php echo $Technologies[$Research['name']]['name']; ?></font>, <?php echo $Lang['FullETA']; ?>: <font class="value"><?php echo eta($Research['end'] - $stardate); ?></font><br />
	<br /><a href="javascript:ask('research.php?action=cancelresearch&id=<?php echo $Research['id']; ?>')" class="delete"><?php echo $Lang['Cancel']; ?> &gt;&gt;</a><br />
	<br /><a href="colony.php"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />

	<script>
	<!--
	function ask($url) {
		if (confirm('<?php echo $Lang['RUSure']; ?>')) location.href = $url;
	}
	//-->
	</script>

	<br />
<?php
		tableend($Lang['Research']);
	}
	else {
		tablebegin($Lang['Research']);
?>
	<br />
	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr id="header">
	<td width="12">&nbsp;</td>
	<td width="72">&nbsp;</td>
	<td width="12">&nbsp;</td>
	<td width="100" align="left"><?php echo $Lang['Technology']; ?>:</td>
	<td>&nbsp; &nbsp;</td>
	<td align="center"><?php echo $Lang['Cost']; ?>:</td>
	<td>&nbsp; &nbsp;</td>
	<td align="center"><?php echo $Lang['Level']; ?>:</td>
	<td>&nbsp; &nbsp;</td>
	<td align="center">ETA:</td>
	<td>&nbsp; &nbsp;</td>
	<td>&nbsp; &nbsp;</td>
	<td width="12">&nbsp;</td>
	</tr>
<?php
	$i = 0;

		foreach ($Technologies as $t) {
			$id = ($i % 2) ? ' class="div"' : '';

?>	<tr height="24"<?php echo $id; ?>>
	<td></td>
	<td class="pw"><?php tableimg('images/pw.gif', 72, 72, "gallery/technology/".($t['completed'] ? 'completed' : 'icons')."/${t['id']}.jpg", 64, 64 /* , "description.php?type=technology&back=research.php&subject=${t['id']}" */ ); ?></td>
	<td></td>
	<td>
		<font class="<?php echo $t['completed'] ? 'work' : 'result'; ?>"><?php echo $t['name']; ?></font>
		<br />
		<?php echo $t['description']; ?>
	</td>
	<td></td>
	<td align="center">
<?php
	if (!$t['completed']) {
		if (@$t['credits']) echo '<b>[!]</b>&nbsp;'.div($t['credits']).'<br />';
		if (@$t['energy']) echo '<b>[E]</b>&nbsp;'.div($t['energy']).'<br />';
		if (@$t['silicon']) echo '<b>[S]</b>&nbsp;'.div($t['silicon']).'<br />';
		if (@$t['metal']) echo '<b>[M]</b>&nbsp;'.div($t['metal']).'<br />';
		if (@$t['uran']) echo '<b>[U]</b>&nbsp;'.div($t['uran']).'<br />';
		if (@$t['crystals']) echo '<b>[C]</b>&nbsp;'.div($t['crystals']).'<br />';
	}

?>	</td>
	<td></td>
	<td align="center" class="capacity"><?php echo @$t['level']; ?></td>
	<td></td>
	<td align="center"><?php echo $t['completed'] ? '&nbsp;' : '[&nbsp;<font class="plus">' . eta(1 + round((25 / $Colony['science']) * $t['work'] / log($Colony['scienceforce']))) . '</font>&nbsp;]'; ?></td>
	<td></td>
	<td align="center"><?php echo $t['completed'] ? '<font class="work">' . $Lang['completed'] . '</font>' : '<a href="' . $_SERVER['PHP_SELF'] . '?action=initiate&name=' . $t['id'] . '">' . $Lang['initiate'] . '&nbsp;&gt;&gt;</a>'; ?></td>
	<td></td>
	</tr>
<?php
			$i++;
		}

?>	</table>
	<br />
<?php
		tableend($Lang['Research']);
	}
}

// ===========================================================================
// NOT AVAILABLE
// ===========================================================================

else {
	tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');
	echo '<br /><b>'.$Lang['NotAvailable'].'</b><br /><br /><a href="control.php">'.$Lang['GoBack'].' &gt;&gt;</a><br /><br />';
	tableend($Lang['Research']);
}

require('include/footer.php');
