<?php

// ===========================================================================
// Website map {website.php}
// ===========================================================================

$Lang['website'] = '';

$Copyright = '&copy; Copyleft by <a href="GALAXYCREW">GF Crew</a>!';

$Website  = array(
	'control' => array('title' => $Lang['Control']),
	'register' => array('title' => $Lang['Registering']),
	'login' => array('title' => $Lang['Logging']),
	'documentation' => array('title' => $Lang['Documentation']),
	'project' => array('title' => $Lang['Project']),
	'lostpassword' => array('title' => $Lang['LostPassword']),
	'messages' => array('title' => $Lang['Messages']),
	'news' => array('title' => $Lang['News']),
	'galaxy' => array('title' => $Lang['Galaxy']),
	'profile' => array('title' => $Lang['Profile']),
	'production' => array('title' => $Lang['Production']),
	'calendar' => array('title' => $Lang['Calendar']),
	'clan' => array('title' => $Lang['Clan'])
);

if (isset($index))
	if (isset($Website[$index])) {
		$page = isset($Website[$index]['page']) ? $Website[$index]['page'] : (isset($index) ? $index : '');
		$title = isset($Website[$index]['title']) ? $Website[$index]['title'] : (isset($title) ? $title : '');
	}
