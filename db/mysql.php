<?php
/**
 * Driver for MySQL
 *
 * @package OXO
 * @version 28, 21/01/10
 * @since 1
 * @author Filip Golewski <zoltarx@o2.pl>
 *
 */

$CLASSNAME='mysql_db';
$SUPPORTS=array('mysql');

if (phpversion()<'5.0.0'&&!class_exists($CLASSNAME)||!class_exists($CLASSNAME, false)) {

class mysql_db
{
	var $layer='mysql', $host='localhost', $user, $password, $name, $prefix;
	var $charset='UTF-8', $persistent;
	var $security="BASE64";
	var $link, $result, $queries, $last;
	var $lasterr="";

	function mysql_db($Database=null)
	{
		global $Errors;
		if (!function_exists('mysql_connect')) { $Errors[] = "Extension <b>mysql</b> is missing"; unset($this); return; }
		if (!is_array($Database)) return;
		$this->user = @$Database['user'];
		$this->password = @$Database['password'];
		$this->name = @$Database['name'];
		$this->prefix = @$Database['prefix'];
		if (isset($Database['host'])) $this->host = $Database['host'];
		if (isset($Database['port'])) $this->host .= ':'.$Database['port'];
		if (isset($Database['persistent'])) $this->persistent=$Database['persistent'];
		if (isset($Database['security'])) $this->security=strtoupper($Database['security']);
		if (isset($Database['charset'])) $this->charset=$Database['charset'];
	}

	function connect()
	{
		if (!function_exists('mysql_connect')) return false;
		global $Errors;
		$secret = function_exists('secure_decode') ? secure_decode($this->password, $this->security) : $this->password;
		if ($this->persistent && function_exists('mysql_pconnect'))
			$this->link = @mysql_pconnect($this->host, $this->user, $secret);
		else
			$this->link = @mysql_connect($this->host, $this->user, $secret);
		if ($this->name && !@mysql_select_db($this->name, $this->link)) {
			$this->close();
			return false;
		}
		if (!$this->link) {
			if ($e=$this->error()) $Errors[]=$e;
			return false;
		}
		$this->set_charset();
		return $this->link;
	}

	function escape($value)
	{
		return mysql_escape_string($value);
	}	

	function query($sql='')
	{
		if (!$this->link && !$this->connect()) return false;
		if ($this->result) @mysql_free_result($this->result);
		$this->queries++;
		return $this->result = @mysql_query(str_replace('#__', $this->prefix, $this->last=$sql), $this->link);
	}
	
	function fetch_row()
	{
		return @mysql_fetch_array($this->result, MYSQL_ASSOC);
	}

	function fetch_all()
	{
		$result = array();
		while ($row = @mysql_fetch_array($this->result)) $result[] = $row;
		@mysql_free_result($this->result);
		return $result;
	}

	function table_rows($table)
	{
		$stored = $this->result;
		$result = ($this->query("SHOW TABLE STATUS LIKE `$table`;") and $row = $this->fetchrow()) ? $row['Rows'] : false;
		$this->result = $stored;
		return $result;
	}

	function num_rows()
	{
		return @mysql_num_rows($this->result);
	}

	function affected_rows()
	{
		return @mysql_affected_rows($this->result);
	}

	function error()
	{
		if ($this->lasterr) { $result=$this->lasterr; $this->lasterr=""; return $result; }
		if ($this->link) {
			if ($e=@mysql_errno($this->link)) return "$e: ".mysql_error($this->link);
			else return false;
		}
		return false;
	}

	function close()
	{
		if ($this->result) @mysql_free_result($this->result);
		if (function_exists('mysql_close') && !$this->persistent) @mysql_close($this->link);
	}

	function set_charset($charset=null)
	{
		if (is_null($charset)) $charset=$this->charset;
		if (!$charset) return true;
		switch ($charset=strtoupper($charset)) {
			case 'UTF-8': $charset='UTF8'; break;
			case 'ISO-8859-2': $charset='LATIN2'; break;
		}
		$this->query("SET NAMES $charset");
		//$this->query("SET character_set_client = $charset, character_set_client = $charset, collation_connection=@@collation_database, character_set_results = NULL;");
		return true;
	}

	function __destruct()
	{
		$this->close();
	}
	
	function fetchrow() { return $this->fetch_row(); }
	function fetchall() { return $this->fetch_all(); }
	function numrows() { return $this->num_rows(); }
	function rows($table) { return $this->table_rows($table); }
	function affectedrows() { return $this->affected_rows(); }
	function setcharset($charset='') { return $this->set_charset($charset); }
	function safe($str) { return $this->escape($str); }

}

}
