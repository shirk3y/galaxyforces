<?php

style_module_section(@$Sections["bottom"], "section-bottom");

?>
</td>
	<td width="12">&nbsp;</td>
	<td width="160">
<?php

if (@$Modules) modules($Modules, 'right');

?>	</td>
	<td width="12"></td>
	</tr>
	</table>
</td>
</tr>
<tr valign="center" height="16"><td><img src="images/0.gif" alt="" width="760" height="16" hspace="0" vspace="0" border="0"></td></tr>
<tr id="belt">
<td>
	<table id="status" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr height="24" valign="center">
	<td class="space"></td>
<?php if (!empty($Config["Disclaimer"])) { ?>
	<td class="left"></td>
	<td class="bg" style="width: 150px">
		<?php echo $Config["Disclaimer"]; ?>
	</td>
	<td class="right"></td>
<?php } ?>
<?php

if (isset($Player['exp'])) {
	$exp = round(160 * (($Player['exp'] - $Player['expbegin'])/($Player['exp4level'] - $Player['expbegin'])));  if ($exp < 1) $exp = 1; elseif ($exp > 160) $exp = 160;
	$mp = round(160 * $Player['mp'] / $Player['mpmax']); if ($mp < 1) $mp = 1; elseif ($mp > 160) $mp = 160;
	$hp = round(160 * $Player['hp'] / $Player['hpmax']); if ($hp < 1) $hp = 1; elseif ($hp > 160) $hp = 160;

?>	<td class="space"></td>
	<td class="left"></td>
	<td class="progress">
	<div class="exp" style="width: <?php echo $exp; ?>px"><img src="images/0.gif" class="px" /></div>
	<div class="mp" style="height: 2px; width: <?php echo $mp; ?>px"><img src="images/0.gif" class="px" /></div>
	<div class="hp" style="height: 2px; width: <?php echo $hp; ?>px"><img src="images/0.gif" class="px" /></div>
	</td>
	<td class="right"></td>
<?php

}

echo '<td>&nbsp;</td>';

if ($Config['Debug']) {
	$timing_stop = explode(' ', microtime());
	$rendertime = number_format((($timing_stop[0]+$timing_stop[1])-($timing_start[0]+$timing_start[1])), 4, $Lang['DecPoint'], ' ');
	echo "\t<td class=\"left\"><img class=\"space\" src=\"images/0.gif\" /></td><td class=\"bg\"><acronym title=\"${Lang['RenderTime']} [R]\"><img class=\"icon\" src=\"images/render.gif\" align=\"left\" alt=\"[R]\" /></acronym>&nbsp;<font class=\"capacity\">$rendertime</font>&nbsp;/&nbsp;<font class=\"result\">".$db->queries."</font></td><td class=\"right\"><img class=\"space\" src=\"images/0.gif\" /></td><td class=\"div\">&nbsp;</td>";
}

if ($logged && @$stardate) echo "\t<td class=\"left\"><img class=\"space\" src=\"images/0.gif\"></td><td class=\"bg\"><acronym title=\"${Lang['CSD']} [T]\"><img class=\"icon\" src=\"images/time.jpg\" align=\"left\" alt=\"[T]\"></acronym>&nbsp;<a href=\"stardate.php\">".div($stardate)."</a></td><td class=\"right\"><img class=\"space\" src=\"images/0.gif\"></td><td class=\"div\">&nbsp;</td>";

?>	</table>
</td>
</tr>
</table>
