<?php

// ===========================================================================
// Style {style.php}
// ===========================================================================

define('TAB', "\t");
define('LF', "\n");
define('BR', '<br />');
define('NL', "\t<br />\n");

define('STYLE', $ROOT.'style/'.$Config['Style'].'/');

$LINKBOXBEGIN = "<br />";
$LINKBOXEND = "<br />";
$LINKBOXPREFIX = "";
$LINKBOXPOSTFIX = "&nbsp;&gt;&gt;";

function swf($id, $path, $width, $height, $bgcolor = 'none', $prefix = "\t\t", $wmode = '')
{
	if ($wmode) {
		$wmode = "wmode=\"$wmode\" ";
		$param = "<param name=\"wmode\" value=\"$wmode\">";
	}
	else $param = '';
	echo "$prefix<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,79,0\" id=\"$id\" width=\"$width\" height=\"$height\">\n";
	echo "$prefix<param name=\"movie\" value=\"$path\"><param name=\"quality\" value=\"high\">$param<param name=\"bgcolor\" value=\"$bgcolor\">\n";
	echo "$prefix<embed name=\"$id\" src=\"$path\" quality=\"high\" ${wmode}bgcolor=\"$bgcolor\" width=\"$width\" height=\"$height\" type=\"application/x-shockwave-flash\" swLiveConnect=\"true\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed>\n";
	echo "$prefix</object>\n";
}

function sound($file)
{
	global $Player;
	if (! (@$Player['soundsoff'])) swf($file, "sounds/$file.swf", 1, 1, '#000000');
}
 
// ---------------------------------------------------------------------------
// Style
// ---------------------------------------------------------------------------

function style_linkcaption($caption)
{
	if (function_exists('custom_linkcaption')) return custom_linkcaption($caption);
	global $PRELINK, $POSTLINK;
	return @$PRELINK.$caption.@$POSTLINK;
}

function style_boxbegin($caption='')
{
	global $BOXBEGIN, $BOXCAPTIONBEGIN, $BOXCAPTIONEND;
	if ($caption = str_eval($caption)) {
		if ($BOXCAPTIONBEGIN || $BOXCAPTIONEND) return $BOXCAPTIONBEGIN.$caption.$BOXCAPTIONEND;
		return $BOXBEGIN.$caption;
	}
	return ($BOXBEGIN ? '' : $BOXCAPTIONBEGIN.$BOXCAPTIONEND).$BOXBEGIN;
}

function style_boxend($status='')
{
	global $BOXEND, $BOXSTATUSBEGIN, $BOXSTATUSEND;
	if (!@$BOXENDBEGIN && (@$BOXSTATUSBEGIN || @$BOXSTATUSEND)) return @$BOXSTATUSBEGIN.str_eval($status).@$BOXSTATUSEND;
	return (($status = str_eval($status)) ? @$BOXSTATUSBEGIN.$status.@$BOXSTATUSEND : '').@$BOXEND;
}

function style_boxbreak()
{
	global $BOXBREAK;
	return $BOXBREAK;
}

function style_linkbox($location, $caption, $style='')
{
	global $LINKBOXBEGIN, $LINKBOXEND, $LINKBOXPREFIX, $LINKBOXPOSTFIX;
	return @$LINKBOXBEGIN.'<a href="'.$location.($style ? '" class="'.$style: '').'">'.@$LINKBOXPREFIX.$caption.@$LINKBOXPOSTFIX.'</a>'.$LINKBOXEND;
}

function style_menu_galaxy()
{	
	global $Menu, $Lang, $Style, $Media;
	global $logged;
	global $Player, $Colony;
	
	if (!$logged) $group=$clan=$colony=false;
	else {
		$group=$Player['usergroup'];
		$clan=$Player['clan'];
		$colony=!empty($Colony);
	}

	ob_start();
	
	$i=0;
	while ($i<count($Menu))
	{
		$m=$Menu[$i];
		$x=++$i;
		if 
		(
			($u=@$m['*'])&&$u!='*'&&
			(
			$u=='-'&&$logged||
			$u=='+'&&!$logged||
			$u=='@'&&!$group||
			$u=='%'&&!$clan||
			$u=='#'&&!$colony||
			strlen($u)&&$u[0]=='@'&&$group!=substr($u,1)||
			strlen($u)&&$u[0]=='%'&&$clan!=substr($u,1)
			)
		)
		continue;			

		$id=isset($m['$'])?$m['$']:$x;
		
		if (isset($m['@'])) $link=$m['@'];
		elseif (isset($m['$'])) $link=$m['$'];
		else $link="menu-$x.php";

		if (ctype_alnum($link)) $link.='.php';		

		if ($ext=str_to_alnum(@$Style['MenuIconExtension'])) $ext='.'.$ext; else $ext='.gif';	
		if ($dim=(int)@$Style['MenuIconDimesion']) $dim='-'.$dim; else $dim="";
	
		$image=isset($m['&'])?$m['&']:'icon'.$x.$dim.$ext;
	
		$lang=isset($m['_'])?$m['_']:"Menu$id";
		if (isset($Lang[$lang])) $lang=$Lang[$lang];
		
		if ($id=="-") {
			echo @$Style['menu.separator'];
			continue;
		}
		
		echo @$Style['menu.item.prefix'];
		if ($link) echo '<a href="'.$link.'">';
		echo $lang;
		if ($link) echo '</a>';
		echo @$Style['menu.item.suffix'];
//		echo "$id\n$link\n$lang\n$image\n";
	}

	$out = ob_get_contents();
	
	ob_end_clean();
	
	if (!$out) return;
	
	echo @$Style['menu.prefix'];
	echo $out;
	echo @$Style['menu.suffix'];
}	

function style_box_head($title="")
{
	if (handler_call("style_box_head", $title)) return;
	echo '
<div>
';
	if ($title) echo '
	<div class="title">
	'.$title.'
	</div>
';
	echo '
	<div>
';
}

function style_box_foot($status="")
{
	if (handler_call("style_box_foot", $status)) return;
	echo '
	</div>
';
	if ($status) echo '
	<div class="status">
	'.$status.'
	</div>
';
	echo '
</div>
';
}

function style_module_section($elements, $id="", $section="box")
{
	global $Style, $Lang;
	
	if (!is_array($elements)) $elements=array($elements);
	if (!count($elements)) continue;

	$content="";
	
	foreach ((array)$elements as $element)
	{
		if (empty($element)) continue;

		ob_start();
		module($element, "box");
		$out = trim(ob_get_contents());
		ob_end_clean();

		if (empty($out)) continue;
		
		$div = ($id ? $id.'-' : '').$section.'-'.(@++$index);
		$class = 'class-'.$element;

		ob_start();
		style_box_head(@$Lang["module.".$element.".caption"]);
		echo '
<div id="'.$div.'" class="'.$class.'">
'.$out.'
</div>
';
		style_box_foot(@$Lang["module.".$element.".status"]);
		$out = ob_get_contents();
		ob_end_clean();
		$content.=$out;
	}

	if (!$content) return;
	
	echo '
<div'.(empty($id)?'':' id="'.$id.'"').'>
'.$content.'
</div>
';

}

include(STYLE.'style.php');
