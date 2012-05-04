<?php
     
// ===========================================================================
// RSS Module {rss.php}
// ===========================================================================
     
// ---------------------------------------------------------------------------
//	Version:	1.0
//	Author(s):	zoltarx
//	Created:	2005-06-22
//	Modified:	2005-06-22
// ---------------------------------------------------------------------------
     
// ---------------------------------------------------------------------------
// All dates should be filled in timestamp format (14 character string that
// can be generated using PHP function date("YmdHis")
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

if (! defined('__RSS_PHP__')) {
	
function ee($e, $v, $p="\t\t")
{
	if (is_array($e)) {
		for ($i = 0; $i < count($e); $i++) echo $p.'<'.$e[$i].'>'.$v[$i].'</'.$e[$i].">\n";
	}
	else echo "$p<$e>$v</$e>\n";
}
	
function timestamprss($timestamp)
{
	return gmdate("D, d M Y H:i:s", mktime(substr($timestamp, 8, 2), substr($timestamp, 10, 2), substr($timestamp, 12, 2), substr($timestamp, 4, 2), substr($timestamp, 6, 2), substr($timestamp, 0, 4))) . ' GMT';
}
	
function timestamprdf($timestamp)
{
	$t=gmdate("YmdHis", mktime(substr($timestamp, 8, 2), substr($timestamp, 10, 2), substr($timestamp, 12, 2), substr($timestamp, 4, 2), substr($timestamp, 6, 2), substr($timestamp, 0, 4)));
	return substr($t,0,4).'-'.substr($t,4,2).'-'.substr($t,6,2).'T'.substr($t,8,2).':'.substr($t,10,2).':'.substr($t,12,2);
}

class rss
{
	var $title, $link, $description, $charset;
	var $language, $date, $lastbuilddate, $docs, $generator, $creator, $managingeditor, $webmaster;
	var $items = array();
	
	function rss($title='', $link='', $description='', $charset='')
	{
		$this->title = $title;
		$this->link = $link;
		$this->description = $description;
		$this->charset = $charset ? $charset : 'ISO-8859-1';
		return TRUE;
	}

	function destroy()
	{
		settype(&$this, 'null');
	}

	function item($title,$description='',$date='',$link='')
	{
		$this->items[] = array(
			'title' => $title,
			'description' => $description,
			'date' => $date,
			'link' => $link,
		);
	}

	function build()
	{
		header("Content-Type: text/xml");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		echo '<?xml version="1.0" encoding="'.($this->charset).'"?>'."\n";
		echo '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:mn="http://usefulinc.com/rss/manifest/" xmlns="http://purl.org/rss/1.0/" xmlns:dc="http://purl.org/dc/elements/1.1/">'."\n";
		echo "\t<channel>\n";
		ee(array('title', 'link', 'description'), array($this->title, $this->link, $this->description));
		
		if ($this->language) ee('dc:language', $this->language);
		if ($this->date) ee('dc:date', timestamprdf($this->date));
		if ($this->lastbuilddate) ee('lastBuildDate', $this->lastbuilddate);
		if ($this->docs) ee('docs', $this->docs);
//		if ($this->generator) ee('generator', $this->generator);
		if ($this->generator) ee('dc:generator', $this->generator);
		if ($this->creator) ee('dc:creator', $this->creator);
		if ($this->managingeditor) ee('managingEditor', $this->managingeditor);
		if ($this->webmaster) ee('webMaster', $this->webmaster);

                echo "\t</channel>\n";
		
		for ($i = 0; $i < count($this->items); $i++) {
			$t = $this->items[$i];
			echo "\n\t<item rdf:about=\"${t['title']}\">\n";
			if ($t['title']) ee('title', $t['title']);
			if ($t['description']) ee('description', '<![CDATA['.$t['description'].']]>');
			if ($t['link']) ee('link', $t['link']);
//			if ($t['date']) ee('pubdate', timestamprss($t['date']));
			if ($t['date']) ee('dc:date', timestamprdf($t['date']));
			echo "\t</item>\n";
		}
		echo "</rdf:RDF>\n";
	}
}	

}
