<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0", false);
header("Cache-Control: max-age=0", false);
header("Pragma: no-cache");
require_once('../lib/config.php');
require_once('../lib/lib.php');

$link=mysql_connect($servers, $user, $password) or die("Could not connect: " . mysql_error());
mysql_select_db($db,$link);
mysql_query('SET NAMES utf8 COLLATE utf8_general_ci');

if (!isset($_GET['tp']))
	$tp=0;
else
	$tp=$_GET['tp'];

$query1 = mysql_query("SELECT * FROM rpo WHERE typerpo=".$tp." and block=0 ORDER BY id",$link);
echo '{';
echo '"data": [';
$count=0;
while ($data = mysql_fetch_array($query1))
{
	if ($count>0)
		echo ',';
	echo '{';
	echo '"code":"'.$data[1].'",';
	echo '"name":"'.$data[2].'"';
	echo '}';
	$count++;
}
echo ']';
echo '}';
?>