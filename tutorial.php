<?php

// ===========================================================================
// Welcome {tutorial.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	0.1
//	Modified:	2006-08-28
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces SSE project.
// ===========================================================================

$auth = true;
require('include/header.php');

locale('help/main');
$pagename = $Lang['Help'];

$subject = getvar('subject');

// ===========================================================================
// SECTIONS
// Config
// Name = section name in locale (help/main.php)
// Class = link color (in css file)
// Type = default is section from locales, br - break, hr - header (requires name); link - external url
// ===========================================================================

$Help = array(
  'galaxy'=>array('name'=>'Galaxy','class'=>'minus'),
  'sse'=>array('name'=>'Sse'),
  'br1'=> array('type'=>'break'),
  'game'=>array('name'=>'Game'),
  'interface'=>array('name'=>'Interface'),
  'hero'=>array('name'=>'Hero'),
  'colony'=>array('name'=>'Colony'),
  'clan'=>array('name'=>'Clan'),
  'br2'=> array('type'=>'break'),
  'hr1'=> array('name'=>'GameWorld','type'=>'header'),
  'story'=>array('name'=>'Story'),
  'universe'=>array('name'=>'Universe'),
  'places'=>array('name'=>'Places'),
  'races'=>array('name'=>'Races'),
  'br'=> array('type'=>'break'),
  'faq'=>array('name'=>'FAQ'),
  'technical'=>array('name'=>'Technical'),
  'br3'=> array('type'=>'break'),
  'rules'=>array('name'=>'Regulations','type'=>'link','url'=>'regulations.php'),
  'ads'=>array('name'=>'Advertisement','type'=>'link','url'=>'welcome.php?view=advertisement'),
);

// ===========================================================================
// MAIN
// ===========================================================================

tablebegin($pagename,600);
  subbegin('images/design1.jpg');
  echo '<div align="center"><font class="h3">'.$Lang['Help'].'</font></div>';
  echo '<p class="result" />'.$Lang['TutorialInfo'].'</b><br /><br /> ';
  echo '<table width="100%"><tr><td width="150" valign="top">';
  tableimg('images/pw.gif', 72, 72, 'gallery/technology/managementtechnology.jpg', 64, 64, 'tutorial.php', 'center');
  echo '<br />';

// Index generator
foreach ($Help as $help => $h) {
  switch(@$h['type']) {
    case 'break':
      echo '<br />';
      break;
    case 'header';
      echo '<b class="plus">'.$Lang[strcap(@$h['name'])].'</b><br>';
      break;
    case 'link';
      $class = (@$h['class'] ? 'class="'.$h['name'].'"' : '');
      echo '<a href="'.$h['url'].'"'.$class.'">'.$Lang[strcap(@$h['name'])].'  &raquo;</a><br />';
      break;
    default;
      $class = (@$h['class'] ? 'class="'.$h['name'].'"' : '');
      echo '<a href="?subject='.$help.'"'.$class.'">'.$Lang[strcap(@$h['name'])].'  &raquo;</a><br />';
      break;
  }
}

  echo '</td><td valign="top">';

// Welcome index
if (!$subject) { ?>
  <i><p class="result" /><?php echo $Lang['TutorialMain']; ?></i>
<?php
  echo '<br /><br /><a href="welcome.php?view=help">'.$Lang['HelpfulLinks'].' &raquo;</a><br /><br /><br />';
  tablebegin('',300);
  echo '<img src="gallery/splash/solar_0'.rand(1, 4).'.gif" alt="Galaxy Forces SSE" />';
  tableend(); 
}

else if ($subject) {
  $locale = $Config['Language'];
  if (file_exists("{$ROOT}locale/$locale/help/$subject.php")) @include("{$ROOT}locale/$locale/help/$subject.php");
  else echo '<h3 class="red">'.$Lang['Error'].'</h3><div align="center"><b class="result">'.$Lang['ErrorNotExists'].'</b></div>';
}

echo '</td></tr>';
echo '</table>';
echo '<div align="center"><font class="result" /><i>'.$Lang['CopyPermissions'].'</i></font></div>';

subend();
tableend($pagename);

require('include/footer.php');
