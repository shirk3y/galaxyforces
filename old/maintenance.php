<?php

// ===========================================================================
// Maintenance page {maintenance.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.0
//	Created:	2005-11-11
//	Modified:	2005-11-11
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Synapse project (http://phpsynapse.sourceforge.net).
// You may only use, modify, copy or distribute this content under the terms
// of GNU General Public License (GPL) or Synapse Artistic Licence (SAL).
// See LINCENSE file for details.
// =========================================================================== 

@include('include/config.php');

unset($Database);

$Config['Internal'] = true;
$Config['Maintenance'] = true;
$Config['Debug'] = false;

require('include/header.php');

locale('website/maintenance');

echo '<h3>'.$Lang['SiteMaintenance'].'</h3>';

require('include/footer.php');
