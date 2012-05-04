<?php

// ===========================================================================
// Documentation management {doc.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	0.2
//	Created:	2005-01-16
//	Modified:	2005-10-07
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of phpSynapse project. See documentation for details.
// ===========================================================================

// ----------------------------------------------------------------------------
// Here is the sample of restoring data from filenames written it this format:
//
// i.e. "Title(Author,Publisher)[en]-Version.doc"
// or "[pl](author)Title.sxw"
//
// ----------------------------------------------------------------------------

if (! defined('__DOC_PHP__')) {

	include('include/mimetypes.php');

	locale('mimetypes');

	function readarticles($path, $recursive = true)
	{
		global $MimeType, $Lang;
		$result = '';
		$files = readfiles($path, $recursive);

		foreach ($files as $file) {
			$ext = fext($file);

			if (isset($MimeType[$ext])) {
				$mime = $MimeType[$ext];
				$mimetype = $Lang['mimetypes'][$mime];
			}
			else {
				$mime = '';
				$mimetype = '';
			}

			if (($pos = strrpos($file, '/')) === false) $name = fname($file);
			else $name = fname(substr($file, ++$pos));

			if (($pos = strrpos($name, '-')) === false) $version = '';
			else {
				$version = substr($name, $pos + 1);
				$name = substr($name, 0, $pos);
			}

			if (($pos = strpos($name, '(')) === false) $author = '';
			else {
				$end = strpos($name, ')');
				$author = substr($name, $pos + 1, $end - $pos - 1);
				$name = str_replace("($author)", '', $name);
			}

			if (($pos = strpos($name, '[')) === false) {
				$lang = '';
				$language = '';
			}
			else {
				$end = strpos($name, ']');
				$lang = substr($name, $pos + 1, $end - $pos - 1);
				$name = str_replace("[$lang]", '', $name);
				switch ($lang) {
					case 'en': $language = $Lang['English']; break;
					case 'pl': $language = $Lang['Polish']; break;
					default: $language = '';
				}
			}

			$name = str_replace('_', ' ', $name);

			$result[] = array('file' => $file, 'ext' => $ext, 'size' => filesize($file), 'mime' => $mime, 'mimetype' => $mimetype, 'title' => $name, 'author' => $author, 'version' => $version, 'lang' => $lang, 'language' => $language);
		}

		return $result;
	}

	define('__DOC_PHP__', TRUE);
}
elseif ($Config['Debug']) error("${Lang['File']} <b>${_SERVER['PHP_SELF']}</b> ${Lang['DefinedMoreThanOnce']}");
