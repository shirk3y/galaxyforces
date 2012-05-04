<?php

// ===========================================================================
// Acess control {access.php)
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	0.2
//	Created:	2004-12-02
//	Modified:	2005-02-08
//	Author(s):	zoltarx@o2.pl
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
// Description:
//
// This is basic access control that allows you to deny/allow selected ip's.
//
// If $Config['AccessControl'] is set to TRUE this module is
// included by common.php.
//
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
// Requires: common.php, config.php
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of phpSynapse project. See documentation for details.
// ===========================================================================

if (!defined('__ACCESS_PHP__')) {
	$access = TRUE;

	if ($Config['AccessControl']) {
		if ($Config['Policy']) {
			switch ($Config['Policy']) {
				case 'accept':
					if ($Config['DenyIP'])
						foreach ($Config['DenyIP'] as $m)
							if ($ip == $m) {
								$access = FALSE;
								break;
							}
					break;
				case 'deny':
					$access = FALSE;
					if ($Config['AllowIP'])
						foreach ($Config['AllowIP'] as $m)
							if ($ip == $m) {
								$access = TRUE;
								break;
							}
					break;
			}
		}
	}
	if (! $access) die;

	define('__ACCESS_PHP__', TRUE);
}
else if ($Config['Debug']) error($Lang['File'] . ' <b>' . $_SERVER['PHP_SELF'] . '</b> ' . $Lang['DefinedMoreThanOnce']);
