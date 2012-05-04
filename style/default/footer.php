</td>
	<td width="12">&nbsp;</td>
	<td width="160">
<?php

if (@$Modules) modules($Modules, 'right');
if (@$modules) modules($modules, 'right');

?>	</td>
	<td width="12"></td>
	</tr>
	</table>
</td>
</tr>
<tr valign="center" height="16"><td><img src="images/0.gif" alt="" width="760" height="16" hspace="0" vspace="0" border="0"></td></tr>
<tr id="belt">
<td>
	<table id="belt" width="100%" height="24" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr height="24" valign="center">
	<td width="12" background="images/b1-bg.gif"><img src="images/0.gif" alt="" width="12" height="24" hspace="0" vspace="0" border="0"></td>
<?php if (!empty($Config["Disclaimer"])) { ?>
	<td width="12" background="images/b2-left.gif"><img src="images/0.gif" alt="" width="12" height="24" hspace="0" vspace="0" border="0"></td>
	<td width="160" background="images/b2-bg.gif" align="center">
		<?php echo $Config["Disclaimer"]; ?>
	</td>
	<td width="12" background="images/b2-right.gif"><img src="images/0.gif" alt="" width="12" height="24" hspace="0" vspace="0" border="0"></td>
	<td background="images/b1-bg.gif"><img src="images/0.gif" alt="" width="12" height="24" hspace="0" vspace="0" border="0"></td>
<?php } ?>
	<td background="images/b1-bg.gif">&nbsp;</td>
<?php
if ($Config['Debug']) {
	$timing_stop = explode(' ', microtime());
	$rendertime = number_format((($timing_stop[0]+$timing_stop[1])-($timing_start[0]+$timing_start[1])), 4, $Lang['DecPoint'], ' ');
	echo "\t<td class=\"left\"><img class=\"spacer\" src=\"images/0.gif\" /></td><td class=\"bg\"><acronym title=\"${Lang['RenderTime']} [R]\"><img class=\"icon\" src=\"images/render.gif\" align=\"left\" alt=\"[R]\" /></acronym>&nbsp;<font class=\"capacity\">$rendertime</font>&nbsp;/&nbsp;<font class=\"result\">".$db->queries."</font></td><td class=\"right\"><img class=\"spacer\" src=\"images/0.gif\" /></td><td class=\"div\">&nbsp;</td>";
}

if ($logged && @$stardate) echo "\t<td class=\"left\"><img class=\"spacer\" src=\"images/0.gif\"></td><td class=\"bg\"><acronym title=\"${Lang['CSD']} [T]\"><img class=\"icon\" src=\"images/time.jpg\" align=\"left\" alt=\"[T]\"></acronym>&nbsp;<a href=\"stardate.php\">".div($stardate)."</a></td><td class=\"right\"><img class=\"spacer\" src=\"images/0.gif\"></td><td class=\"div\">&nbsp;</td>";

?>	</table>
</td>
</tr>
</table>
</body>
</html>
