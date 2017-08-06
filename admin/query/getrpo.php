<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0", false);
header("Cache-Control: max-age=0", false);
header("Pragma: no-cache");
require_once('../../lib/config.php');
require_once('../../lib/lib.php');

$link=mysql_connect($servers, $user, $password) or die("Could not connect: " . mysql_error());
mysql_select_db($db,$link);
mysql_query('SET NAMES utf8 COLLATE utf8_general_ci');

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
	$query = mysql_query("SELECT id, hash, login FROM usr WHERE id = '".intval($_COOKIE['id'])."' and login='admin'",$link);
    $userdata = mysql_fetch_array($query);

    if(($userdata[1] !== $_COOKIE['hash']) or ($userdata[0] !== $_COOKIE['id']))
    {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
		exit;
    }
    else
    {
        
    }
}
else
{
	header("Location: index.php"); 
	exit;
}

$tp=$_GET['tp'];

$query1 = mysql_query("SELECT * FROM rpo WHERE typerpo=".$tp." ORDER BY id",$link);
echo '{';
echo '"data": [';
$count=0;
while ($data = mysql_fetch_array($query1))
{
	if ($count>0)
		echo ',';
	echo '{';
	echo '"Код":"'.$data[1].'",';
	echo '"Название":"'.$data[2].'",';
	
	if ($data[4]==1)
		echo '"Действие":"<input type=\'button\' class=\'ui-button btnaction\' onclick=\'unblockrpo('.$data[0].')\' value=\'Разблокировать\' />"';
	else
		echo '"Действие":"<input type=\'button\' class=\'ui-button btnaction\' onclick=\'blockrpo('.$data[0].')\' value=\'Заблокировать\' />"';
	echo '}';
	$count++;
}
echo ']';
echo '}';
?>