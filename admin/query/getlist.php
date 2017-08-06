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

$query1 = mysql_query("SELECT * FROM usr",$link); // WHERE login<>'admin'
echo '{';
echo '"data": [';
$count=0;
while ($data = mysql_fetch_array($query1))
{
	if ($count>0)
		echo ',';
	echo '{';
	echo '"№":"'.$data[0].'",';
	echo '"Логин":"'.$data[1].'",';
	echo '"Дата создания":"'.$data[3].'",';
	echo '"Комментарий":"'.$data[4].'",';
	echo '"Хэш":"'.$data[5].'",';
	if ($data[0]==1)
		echo '"Статус":"<td><input type=\'button\' class=\'btnaction ui-button\' onclick=\'newpassword('.$data[0].')\' value=\'Сменить пароль\' /></td>",';
	else
		if ($data[6]==1)
			echo '"Статус":"<input type=\'button\' class=\'btnaction ui-button\' onclick=\'unblockuser('.$data[0].')\' value=\'Разблокировать\' />",';
		else
			echo '"Статус":"<input type=\'button\' class=\'btnaction ui-button\' onclick=\'blockuser('.$data[0].')\' value=\'Заблокировать\' />",';
	if ($data[0]==1)
		echo '"Действия":"<td></td>"';
	else
		echo '"Действия":"<td><input type=\'button\' class=\'btnaction ui-button\' onclick=\'newhash('.$data[0].')\' value=\'Новый Хэш\' /></td>"';	
	echo '}';
	$count++;
}
echo ']';
echo '}';
?>