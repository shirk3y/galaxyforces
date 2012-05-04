<?php

/**
 * Call handler for action, if there is more than one function set
 * for desired action all existing functions will be called except
 * that first called function returning boolean true will stop
 * processing
 *
 * This function will result false if handler does not exists
 *
 * @param string $action
 * @param array $arguments
 * @return bool
 */
function handler_call($action, $arguments=null)
{
	global $HANDLER;
	if (!isset($HANDLER[$action])) return false;
	foreach ((array)$HANDLER[$action] as $f)
	{
		if (!function_exists($f)) continue;
		if (true === call_user_func_array($f, (array)$arguments)) break;
	}
	return true;
}

/**
 * Return true if action exists in global $HANDLER array
 *
 * @return bool
 */
function handler_exists($action)
{
	global $HANDLER;
	return (isset($HANDLER[$action]));
}
