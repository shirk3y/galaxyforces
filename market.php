<?php

// ===========================================================================
// Market {market.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.2
//	Modified:	2005-10-21
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'control';
$auth = true;

require('include/header.php');

$pagename = $Lang['Market'];

// ===========================================================================
// CHECK
// ===========================================================================

if (!$Colony) $errors .= $Lang['NotAvailable'].'<br />';

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t<br /><font class=\"h3\">${Lang['ErrorProblems']}</font><br /><br />\n\t<font class=\"error\">$errors</font>\n\t<br /><a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />\n";
	sound('error');
	tableend("<a href=\"control.php?rid=$rid\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// RESULT
// ===========================================================================

elseif ($result) {
	tablebegin($pagename, 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("<a href=\"market.php?rid=$rid\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// MARKET
// ===========================================================================

elseif (checkplace('market')) {
	tablebegin($Lang['Market'], 500);

	echo "\t<br /><center><font class=\"h3\">${Lang['MarketWelcome']}</font>";
	if ($action) sound('thankyou'); else sound('market');
	echo "</center>\n";

	$mod = reputationmodifier($Player['reputation']);

	$symbol = array('energy'=>'E','silicon'=>'S','metal'=>'M','uran'=>'U','plutonium'=>'P','deuterium'=>'D','food'=>'F','crystals'=>'C');

	foreach (array('energy', 'silicon', 'metal', 'uran', 'plutonium', 'deuterium', 'food', 'crystals') as $s) {
		if (!$place[$s.'buy']) $place[$s.'buy'] = $place[$s.'buyaverage'];
		elseif ($place[$s.'buy'] < 0.66 * $place[$s.'buyaverage']) $place[$s.'buy'] = 0.66 * $place[$s.'buyaverage'];
		elseif ($place[$s.'buy'] > 1.33 * $place[$s.'buyaverage']) $place[$s.'buy'] = 1.33 * $place[$s.'buyaverage'];

		if (!$place[$s.'sell']) $place[$s.'sell'] = $place[$s.'sellaverage'];
		elseif ($place[$s.'sell'] < 0.66 * $place[$s.'sellaverage']) $place[$s.'sell'] = 0.66 * $place[$s.'sellaverage'];
		elseif ($place[$s.'sell'] > 1.33 * $place[$s.'sellaverage']) $place[$s.'sell'] = 1.33 * $place[$s.'sellaverage'];

		$place[$s.'buy'] *= $mod;
		$place[$s.'sell'] /= $mod;
	}

	echo TAB.'<table id="sub" cellspacing="0" cellpadding="0">'.LF;

	foreach (array('energy', 'silicon', 'metal', 'uran', 'plutonium', 'deuterium', 'food', 'crystals') as $s) {

		if ($place[$s.'buy'] || $place[$s.'sell']) {
			echo TAB.'<tr height="8"><td>&nbsp;</td></tr>'.LF;

			echo TAB.'<tr height="72" valign="middle"><td width="12">&nbsp;</td><td width="72">'.LF;
			tableimg('images/pw.gif', 72, 72, "gallery/resources/icons/$s.jpg", 64, 64, "description.php?subject=$s&back=market.php");
			echo TAB.'</td><td width="12">&nbsp;</td><td align="left"><b>['.$symbol[$s].']</b> '.$Lang[strcap($s)].'<br /><br />';

			if ($place[$s.'buy']) echo '<b>'.$Lang['BuyPrice'].'</b>: <font class="plus">'.div($place[$s.'buy'], 2, $Lang['DecPoint']).'</font> <b>[!]</b><br />';
			if ($place[$s.'sell']) echo '<b>'.$Lang['SellPrice'].'</b>: <font class="result">'.div($place[$s.'sell'], 2, $Lang['DecPoint']).'</font> <b>[!]</b><br />';

			echo '</td><td align="center">';

			if ($place[$s.'buy']) {
				if (isset($Colony[$s.'capacity'])) {
					$m = $Colony[$s.'capacity'] - $Colony[$s];
					$c = floor($Player['credits'] / $place[$s.'buy']);
					if (($max = $c < $m ? $c : $m) < 0) $max = 0; else $max = floor($max);
				}
				else $max = 0;

				echo '<form action="market.php" method="POST">';
				echo '<input type="hidden" name="action" value="buy">';
				echo '<input type="hidden" name="name" value="'.$s.'">';
				echo '<input name="amount" size="5" value="'.$max.'">&nbsp;<input type="submit" value="'.$Lang['Buy'].'"><br /><br />'.(isset($Colony[$s.'capacity']) ? '<b>Max</b>: <font class="capacity">'.div($max).'</font>' : '').'</form><br />';
			}
			else echo '&nbsp;';

			echo '</td><td width="12">&nbsp;</td><td align="center">';

			if ($place[$s.'sell']) {
				$max = floor($Colony[$s]);
				echo '<form action="market.php" method="POST">';
				echo '<input type="hidden" name="action" value="sell">';
				echo '<input type="hidden" name="name" value="'.$s.'">';
				echo '<input name="amount" size="5" value="'.$max.'">&nbsp;<input type="submit" value="'.$Lang['Sell'].'"><br /><br /><b>Max</b>: <font class="result">'.div($max).'</font></form><br />';
			}
			else echo '&nbsp;';

			echo '</td><td width="12">&nbsp;</td></tr>'.LF;
		}
	}

	echo TAB.'<tr height="8"><td>&nbsp;</td></tr>'.LF.TAB.'</table>'.LF;

	tableend("<a href=\"control.php?rid=$rid\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// NOT AVAILABLE
// ===========================================================================

else {
	tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');
	echotitle($Lang['NotAvailable']);
	echo "<font class=\"capacity\">${Lang['PlaceNotAvailable']}</font><br /><br />";
	tableend($back ? "<a href=\"$back\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>" : "${Lang['Error']}: ${Lang['NotAvailable']}");
}

require('include/footer.php');
