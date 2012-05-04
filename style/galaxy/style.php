<?php

global $Style;

$Style['Stylesheet'] = 'style/galaxy/style.css';
$Style['ShortcutIcon'] = 'favicon.ico';

global $HANDLER;

$HANDLER['style_box_head'] = 'tablebegin';
$HANDLER['style_box_foot'] = 'tableend';

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
	global $Config;
	$at=($id?" id=\"$id\"":'').($width?" width=\"$width\"":'').($height?" height=\"$height\"":'').($align?" align=\"$align\"":'');
	?>
<table class="box"<?php echo $at?>><tr><td>

<?php

	if (@$Config["style.galaxy.use_div"]) {
	
	?>
<div class="box-top"><div class="box-top-left"></div><div class="box-top-right"></div><div class="box-top-middle"><div><div class="box-title"><?php
	if ($header) { ?><table><tr><td class="box-title-left"></td><td class="box-title-middle"><?php echo $header; ?></td><td class="box-title-right"></td></tr></table><?php }
	?></div></div></div></div>
	<?php

	} else {
	
	?>
<table class="box-top"><tr><td class="box-top-left"></td><td class="box-top-middle">
<?php
	if ($header) { ?><table><tr><td class="box-title-left"></td><td class="box-title-middle"><?php echo $header; ?></td><td class="box-title-right"></td></tr></table><?php }
	?></td><td class="box-top-right"></td></tr></table>
<?php

	}
	
	?>

<div class="box-content-top"><div><div><b></b></div></div></div>

<?php
	
	
/*
	
<div style="clear:both"></div>

TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST <br />
	';
	

			<div class="box-title">
				<div class="box-title-left"></div>
				<div class="box-title-middle">
				<div>
					MIDDLE
				</div>
				</div>
				<div class="box-title-right"></div>
			</div>

*/
	
	
	?>
<table class="box-content-outer"><tr><td class="box-content-left"></td><td class="box-content">

<?php
	
}

function tableend($footer = '', $prefix = "\t")
{
	global $Config;	
	?>

</td><td class="box-content-right"></td></tr></table>

<div class="box-content-bottom"><div><div><b></b></div></div></div>

<?php

	if (@$Config["style.galaxy.use_div"]) {

	?>
<div class="box-bottom"><div class="box-bottom-left"></div><div class="box-bottom-right"></div><div class="box-bottom-middle"><div><div class="box-status"><?php

	if ($footer) { ?><table><tr><td class="box-status-left"></td><td class="box-status-middle"><?php echo $footer; ?></td><td class="box-status-right"></td></tr></table><?php }
	?></div></div></div></div>
<?php

	} else {
	
	?>
<table class="box-bottom"><tr><td class="box-bottom-left"></td><td class="box-bottom-middle"><?php

	if ($footer) { ?><table><tr><td class="box-status-left"></td><td class="box-status-middle"><?php echo $footer; ?></td><td class="box-status-right"></td></tr></table><?php }
	?></td><td class="box-bottom-right"></td></tr></table>
<?php

	}


	?>
</td></tr></table>

<br />	
<?php 

}

function tablebreak($prefix = "\t") {
?>
	<div class="table-break"><div></div></div>
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
