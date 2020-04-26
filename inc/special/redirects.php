<?php

if (!defined("SOFAWIKI")) die("invalid acces");

$swParsedName = "Special:Redirects";
$swParsedContent = "";

$start = @$_REQUEST['start'];
$limit = 500;
		

$revisions = swQuery(array('SELECT _name, _link WHERE _content =* #REDIRECT'));

$lines = array();
foreach ($revisions as $row)
{
	
	$name = $row['_name'];
	$link = $row['_link'];
	if (stristr($name,'/')) $name = substr($name,0,strpos($name,'/'));
	$url = swNameURL($name);
	$lines[$url] = '<li><a href="index.php?action=edit&name='.$name.'">'.$name.'</a> to <a href="index.php?name='.$link.'">'.$link.'</a></li> ';

}
sort($lines);
$count = count($lines);

$lines2 = array();
$i =0;
foreach($lines as $line)
{
	if ($i < $start) { $i++; continue;}
	$i++;
	if ($i > $start + $limit) continue;
	$lines2[] = $line;
}


$navigation = '<nowiki><div class="categorynavigation">';
if ($start>0)
	$navigation .= '<a href="index.php?name=special:dead-end-pages&start='.sprintf("%0d",$start-$limit).'"> '.swSystemMessage('back',$lang).'</a> ';
		
$navigation .= " ".sprintf("%0d",min($start+1,$count))." - ".sprintf("%0d",min($start+$limit,$count))." / ".$count;
if ($start<$count-$limit)
	$navigation .= ' <a href="index.php?name=special:dead-end-pages&start='.sprintf("%0d",$start+$limit).'">'.swSystemMessage('forward',$lang).'</a>';
	$navigation .= '</div></nowiki>';

$swParsedContent .= $navigation.join(' ',$lines2).$navigation;

$swParseSpecial = false;


?>