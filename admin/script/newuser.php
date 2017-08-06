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
}
else
{
	exit;
}

if (!isset($_POST['login'])||!isset($_POST['password']))
	exit;

$login = $_POST['login'];
$password=md5(md5($_POST['password']));
$comment=$_POST['comment'];
$hash = md5(generateCode(10));
echo "INSERT INTO usr (login, password, comment,`hash`,`lock`,created) VALUES ('".$login."','".$password."','".$comment."','".$hash."',0,NOW())";
mysql_query("INSERT INTO usr (login, password, comment,`hash`,`lock`,created) VALUES ('".$login."','".$password."','".$comment."','".$hash."',0,NOW())",$link);
?>