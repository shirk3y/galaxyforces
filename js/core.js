/*
 * javascript core library
 * @version 14
 * @package OXO
 * @author Filip Golewski <zoltarx@o2.pl>
 */

function getObjById($id)
{
	var $result = false;
	if (document.getElementById) 
	{
		$result = document.getElementById($id);
	}
	else if (document.all) 
	{
		$result = document.all[$id];
	}
	else if (document.layers) 
	{
		$result = document.layers[$id];
	}
	return $result;
}

function setCheckBoxes($form, $name, $checked)
{
	$e = document.forms[$form].elements[$name];
	$c = $e.length;
	if ($c)
	{
		for ($i = 0; $i < $c; $i++)
		{
			$e[$i].checked = $checked;
		}
	}
	else
	{
		$e.checked = $checked;
	}
	return true;
}

function findElement($element)
{
	if (document.all[$element] != null) 
	{
		return document.all[$element];
	}
	return getObjById($element);
}

function toggleVisibility($element, $position, $default)
{
	if (typeof $position != 'boolean')
	{
		$position = false;
	}
	if (typeof $default != 'boolean')
	{
		$default = true;
	}
	$element=findElement($element);
	if ($element == null) 
	{
		alert('Exception: \r\nFunction: toggleVisibility()\r\nReason: Element not found');
		return false;
	}
	if ($element.style.visibility == "")
	{
		$element.style.visibility = $default ? "visible" : "hidden";
	}
	if ($element.style.visibility == 'hidden')	
	{
		$element.style.visibility = 'visible';
		if ($position) 
		{
			$element.style.position = 'relative';
			$element.style.display = 'block';
			$element.style.overflow = 'visible';
		}
	}
	else
	{
		$element.style.visibility = 'hidden';
		if ($position) 
		{
			$element.style.position = 'absolute';
			$element.style.display = 'none';
			$element.style.overflow = 'hidden';
		}
	}
	return true;
}

/**
 * Add function to be executed after document is ready. It
 * supports multiple entries like in this example:
 *
 *   addReadyEvent(myFunctionName);
 *
 *   addReadyEvent(function(){ myFunctionName('myArgument') });
 *
 * Inspired by Adam Eslinger idea
 *
 */
function addReadyEvent($function)
{
	if (typeof window.addEventListener != "undefined")
	{
		window.addEventListener("load", $function, false);
	}
	else if (typeof window.attachEvent != "undefined")
	{
		window.attachEvent("onload", $function);
	}
	else if (window.onload != null) 
	{
		var old = window.onload;
		window.onload = function (e) { old(e); window[$function](); };
	}
    else
	{
		window.onload = $function;
	}
}

function setFocus($element)
{
	$element=findElement($element);
	if ($element == null) 
	{
		alert('Exception: \r\nFunction: setFocus()\r\nReason: Element not found');
		return false;
	}
	$element.focus();
}