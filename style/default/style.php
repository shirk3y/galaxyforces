<?php

global $Style;

$Style['Stylesheet'] = 'style/default/style.css';
$Style['ShortcutIcon'] = 'favicon.ico';

function subbegin($background='') {
?>	<table <?php echo ($background ? 'background="' . $background . '" ' : ''); ?>width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr height="8"><td colspan="9">&nbsp;</td></tr>
	<tr valign="top">
	<td width="12">&nbsp;</td>
	<td>
<?php
}

function subbreak() {
	echo "\t<td width=\"8\">&nbsp;</td>\n\t<td>\n";
}

function subend() {
?>	<td width="8">&nbsp;</td>
	</tr>
	<tr height="8"><td colspan="9">&nbsp;</td></tr>
	</table>
<?php
}

function intbegin($size = 8, $background = '') {
?>	<table <?php echo ($background ? "background=\"$background\"" : ''); ?>width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr height="<?php echo $size; ?>"><td colspan="3"><img src="images/0.gif" width="<?php echo $size; ?>" height="<?php echo $size; ?>" hspace="0" vspace="0" align="left" /></td></tr>
	<tr valign="top">
	<td width="<?php echo $size; ?>"><img src="images/0.gif" width="<?php echo $size; ?>" height="<?php echo $size; ?>" hspace="0" vspace="0" align="left" /></td>
	<td>
<?php
}

function intend($size = 8) {
?>	<td width="<?php echo $size; ?>"><img src="images/0.gif" width="<?php echo $size; ?>" height="<?php echo $size; ?>" hspace="0" vspace="0" align="left" /></td>
	</tr>
	<tr height="<?php echo $size; ?>"><td colspan="3"><img src="images/0.gif" width="<?php echo $size; ?>" height="<?php echo $size; ?>" hspace="0" vspace="0" align="left" /></td></tr>
	</table>
<?php
}

function tablebegin($header = '', $width = '100%', $height = '0', $id = '', $align = 'center', $cellalign = 'center', $prefix = "\t")
{
	echo "$prefix<table".($id ? " id=\"$id\"" : '').($width ? " width=\"$width\"" : '').($height ? " height=\"$height\"" : '').($align ? " align=\"$align\"" : '').' cellspacing="0" cellpadding="0">'."\n";

	if ($header) { ?><tr valign="middle"><td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="box-headerleft"></td><td class="box-header"><table align="right" height="24" cellspacing="0" cellpadding="0"><tr><td class="box-titleleft"></td><td class="box-title"><?php echo $header; ?></td><td width="12" background="images/b3-right.gif"></td></tr></table></td><td class="box-headerright"></td></tr></table></td></tr><?php }

	?><tr><td class="box-topleft"></td><td class="box-top"></td><td class="box-topright"></td></tr><tr valign="middle"><td class="box-left"><img src="images/0.gif" width="4" /></td><td class="box-content" align="<?php echo $cellalign; ?>"><?php
}

function tableend($footer = '', $prefix = "\t") {
	?></td><td class="box-right"><img src="images/0.gif" width="4" /></td></tr><tr><td class="box-bottomleft"></td><td class="box-bottom"></td><td class="box-bottomright"></td></tr><?php
	
	if ($footer) { ?><tr valign="middle"><td colspan="3"><table width="100%" cellspacing="0" cellpadding="0"><tr><td class="box-footerleft"></td><td class="box-footer"><table align="left" cellspacing="0" cellpadding="0"><tr><td class="box-statusleft"></td><td class="box-status"><?php echo $footer; ?></td><td class="box-statusright"></td></tr></table></td><td class="box-footerright"></td></tr></table></td></tr><?php }

	echo "</table>\n";
}

function tablebreak($prefix = "\t") {
?>	</td><td class="box-right"></td></tr>
	<tr height="5" valign="bottom">
	<td width="4"><img src="images/table-breakleft.gif" alt="" width="4" height="5" /></td>
	<td background="images/table-break.gif"><img src="images/0.gif" alt="" width="4" height="5" /></td>
	<td width="4"><img src="images/table-breakright.gif" alt="" width="4" height="5" /></td>
	<tr valign="middle">
	<td width="4" background="images/table-left.gif"><img src="images/0.gif" alt="" width="4" height="4" /></td>
	<td align="center" background="images/table.jpg">
<?php
}

function tableimg($bg, $bgwidth, $bgheight, $image, $width, $height, $href = '', $align = '', $alt = '', $prefix = "\t\t")
{
	echo "$prefix<table background=\"$bg\" width=\"$bgwidth\" height=\"$bgheight\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"" . ($align ? " align=\"$align\"" : '') . ">\n";
	echo "$prefix<tr height=\"$bgheight\" valign=\"middle\">\n";
	echo "$prefix<td><center>" . ($href ? "<a href=\"$href\">" : '') . "<img src=\"$image\"" . ($alt ? " alt=\"$alt\"" : '') . " width=\"$width\" height=\"$height\" hspace=\"0\" vspace=\"0\" border=\"0\">" . ($href ? '</a>' : '') . "</center></td>\n";
	echo "$prefix</tr>\n$prefix</table>\n";
}

function echotitle($s)
{
	echo "\t<br /><font class=\"h3\">$s</font><br /><br />\n";
}

function anchor($l, $s, $c='')
{
	return '<a'.($c ? ' class="'.$c.'"' : '')." href=\"$l\">$s&nbsp;&gt;&gt;</a>";
}

function echolink($l, $s, $c='')
{
	echo "<a".($c ? ' class="'.$c.'"' : '')." href=\"$l\">$s&nbsp;&gt;&gt;</a>";
}

function echolinkbox($l, $s, $c='')
{
	echo "\t<br /><a".($c ? ' class="'.$c.'"' : '')." href=\"$l\">$s&nbsp;&gt;&gt;</a><br />\n";
}
