<?php
/*
// ===========================================================================
// Abstract class {db/null.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.5
//	Modified:	2008-07-02
//	Created:	2005-09-01
//	Authors:	Filip Golewski <zoltarx@o2.pl>
//	Package:	Vaco Web Library
//	Licence:	GPLv3
// ---------------------------------------------------------------------------

// ===========================================================================
=pod

=head1 NAME

null.php - Abstract class

=head1 CHANGES

2008-04-10	1.4

Structure changed to provide additional compatibility functions

2007-11-26	1.3

Almost final release

=head1 DESCRIPTION

This is a prototype of a database driver - consists of all methods you need
to define in your own driver. Use this file to create new drivers.

=head1 LICENCE

Copyright (C) 2007 Filip Golewski

You may use, copy, distribute, or modify this work under the terms 
of the package license which is available in the separate file LICENSE.txt.

=cut
// ===========================================================================
*/

$CLASSNAME = 'null_db';
$EXPERIMENTAL = true;

if (phpversion()<'5.0.0'&&!class_exists($CLASSNAME)||!class_exists($CLASSNAME, false)) {

class null_db
{
	var $layer = 'null';
	var $host, $user, $password, $name, $prefix;
	var $port, $mode, $persistent, $charset;
	var $queries, $last;

	function null_db($Database = array())
	{
		$this->user = @$Database['user'];
		$this->password = @$Database['password'];
		$this->name = @$Database['name'];
		$this->prefix = @$Database['prefix'];
		$this->host = @$Database['host'];

		$this->port = @$Database['port'];
		$this->mode = @$Database['mode'];
		$this->persistent = @$Database['persistent'];
		$this->charset = @$Database['charset'];
	}

	function connect()
	{
		return false;
	}
	
	function escape($value)
	{
		return addslashes($value);
	}

	function query($sql='')
	{
		$this->queries++;
		$this->last=str_replace('#__', $this->prefix, $sql);
		return false;
	}
	
	function fetch_row()
	{
		return array();
	}

	function fetch_all()
	{
		return array();
	}
	
	function table_rows($table)
	{
		return false;
	}

	function num_rows()
	{
		return false;
	}

	function affected_rows()
	{
		return false;
	}

	function error()
	{
		return '';
	}

	function close()
	{
		return false;
	}

	function exists($table)
	{
		return false;
	}
	
	function set_charset()
	{
		return false;
	}
	
	function __destruct()
	{
	}

	function fetchrow() { return $this->fetch_row(); }
	function fetchall() { return $this->fetch_all(); }
	function numrows() { return $this->num_rows(); }
	function rows($table) { return $this->table_rows($table); }
	function affectedrows() { return $this->affected_rows(); }
	function setcharset($charset='') { return $this->set_charset($charset); }

}

}
