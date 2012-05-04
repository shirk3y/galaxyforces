<?php

// ===========================================================================
// Emoticons {emoticons.php)
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.5
//	Created:	2004-08-12
//	Modified:	2005-10-24
//	Author(s):	zoltarx,lander,unk
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Synapse project (http://phpsynapse.sourceforge.net).
// ===========================================================================

if (!defined('__EMOTICONS__')) {

$files = array();

function emoticons($s='')
{
	global $Emoticons;
	if (function_exists('str_ireplace')) return str_ireplace(array_keys($Emoticons), $Emoticons, $s);
	else return str_replace(array_keys($Emoticons), $Emoticons, $s);
}

function emotia($name, $src) {
	global $ROOT, $Emoticons;
	$Emoticons[$name] = '<img src="'.$ROOT.'images/emoticons/'.$src.'" alt="" />';
}

function reademots($DIR='')
{
	global $files;
	if ($dh = @opendir(@$ROOT."images/emoticons/".$DIR)) {
		while (false !== ($filename = readdir($dh))) {
			if ($filename[0] != '.') {
				if (is_dir("images/emoticons/".$DIR.$filename)) reademots($DIR.$filename."/");
				elseif (('gif' == ($ext = fext($filename))) || ($ext == 'png') || ($ext == 'jpg') || ($ext == 'jpeg')) {
						$files[] = $DIR.$filename;
				}
			}
		}
	}
}

reademots();

if ($files) foreach ($files as $file) emotia(':'.fname($file).':', $file);

emotia(':???:', 'default/question3.gif');
emotia(':??:', 'default/question2.gif');
emotia(':?:', 'default/question.gif');
emotia(':!!!:', 'default/exclaim3.gif');
emotia(':!!:', 'default/exclaim2.gif');
emotia(':!:', 'default/exclaim.gif');
emotia(':)', 'default/smile.gif');
emotia(':-)', 'default/smile.gif');
emotia(';)', 'default/eye.gif');
emotia(';-)', 'default/wink.gif');
emotia(';]', 'default/wink.gif');
emotia(':D', 'default/biggrin.gif');
emotia(';D', 'default/bigsmile.gif');
emotia(':-D', 'default/biggrin.gif');
emotia(';-D', 'default/bigsmile.gif');
emotia(':(', 'default/sad.gif');
emotia(':-(', 'default/sad.gif');
emotia(';(', 'default/cry.gif');
emotia(';-(', 'default/cry.gif');
emotia('8P', 'default/8P.gif');
emotia(':P', 'default/jezyk.gif');
emotia(':-P', 'default/jezyk.gif');
emotia(';P', 'default/jezyk2.gif');
emotia(';-P', 'default/jezyk2.gif');
emotia(':-/', 'default/evil.gif');
emotia(':/', 'default/evil.gif');
emotia(':-[', 'default/sad.gif');
emotia(':[', 'default/sad.gif');
emotia('8)', 'default/cool.gif');
emotia('8-)', 'default/cool.gif');
emotia(':-X', 'default/mad.gif');
emotia(':-*', 'default/kiss.gif');
emotia(':*', 'default/kiss.gif');
emotia(';-*', 'default/kiss2.gif');
emotia(';*', 'default/kiss2.gif');
emotia(':]', 'default/krzywy.gif');
emotia(':-]', 'default/krzywy.gif');
emotia(':>', 'default/cunning.gif');


arsort($Emoticons);

define('__EMOTICONS_PHP__', TRUE);

}
