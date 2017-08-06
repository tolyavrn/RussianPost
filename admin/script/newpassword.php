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

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
	if ($_COOKIE['id']==1)
	{
		$query = mysql_query("SELECT id, hash, login FROM usr WHERE id = '".intval($_COOKIE['id'])."' and login='admin'",$link);
		$userdata = mysql_fetch_array($query);

		if(($userdata[1] !== $_COOKIE['hash']) or ($userdata[0] !== $_COOKIE['id']))
		{
			setcookie("id", "", time() - 3600*24*30*12, "/");
			setcookie("hash", "", time() - 3600*24*30*12, "/");
			exit;
		}
	}
	else
	{
		exit;
	}
}
else
{
	exit;
}
echo 1;
if (!isset($_POST['id']) || !isset($_POST['password']))
	exit;
echo 2;
$id = $_POST['id'];
if ($id==1)
{
	echo 3;
	$password=md5(md5($_POST['password']));

	mysql_query("UPDATE usr SET password='".$password."' WHERE id = '".$id."'",$link);
}
else	
	exit;
?>