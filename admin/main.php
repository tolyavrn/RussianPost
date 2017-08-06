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

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
	$query = mysql_query("SELECT id, hash, login FROM usr WHERE id = '".intval($_COOKIE['id'])."' and login='admin'",$link);
    $userdata = mysql_fetch_array($query);

    if(($userdata[1] !== $_COOKIE['hash']) or ($userdata[0] !== $_COOKIE['id']))
    {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
    }
    else
    {
        //echo "Привет, ".$userdata[2].".";	
    }
}
else
{
	header("Location: index.php"); 
}

$query1 = mysql_query("SELECT * FROM api_pochta.usr",$link); // WHERE login<>'admin'
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<title>Админка</title>
		<meta charset="utf-8">
		<meta name="author" content="tolya-vrn@yandex.ru">
		<meta name="publisher" content="Anatolii Ponomarev">
		<meta name="keywords" content="">
		<link rel="stylesheet" href="../css/datatables.css">
		<link rel="stylesheet" href="../css/jquery-ui.css">
		<link rel="stylesheet" href="../js/DataTables-1.10.15/css/dataTables.jqueryui.css">
		<link rel="stylesheet" href="../js/DataTables-1.10.15/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="../css/styleadmin.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type='text/javascript' src='../js/jquery.dataTables.js'></script>
		<script type='text/javascript' src='../js/dataTables.jqueryui.js'></script>
		<script type='text/javascript' src='js/script.js'></script>
		<script type='text/javascript' src='js/jquery.cookie.js'></script>
	</head>
	<body>
		<div id="wrapper">
			<div class="addblock">
				<input type="button" class="ui-button ui-corner-all ui-widget exitbtn" onclick="click_exit()" title="Выйти" />
			</div>
			<main id="main">
				<div class="addblock">
					<input type="button" class="ui-button ui-corner-all ui-widget btnaction" value="Категория РПО" onclick="categoryRPO()" /><input type="button" class="ui-button ui-corner-all ui-widget btnaction" value="Вид РПО" onclick="typerpo()" /><input type="button" class="ui-button ui-corner-all ui-widget btnaction" value="Добавить нового пользователя" onclick="adduser()" />
				</div>
				<section class="section-form-russianpost">
					<table id="users" cellspacing="0">
						<thead>
							<tr>
								<td>№</td>
								<td>Логин</td>
								<td>Дата создания</td>
								<td>Комментарий</td>
								<td>Хэш</td>
								<td>Статус</td>
								<td>Действия</td>
							</tr>
						</thead>
					</table>
					<div id="newuser" title="Добавить нового пользователя">
						<form id="form-russianpost" name="form-russianpost" method="post" action="index.php?dt=<?php echo time();?>">
							<fieldset>
								<p>
									<label for="login">Логин</label><input name="login" id="login" type="text" value="" required pattern=".{5,45}" title="Длина логина должна быть от 5 до 45 символов" />
								</p>
								<p>
									<label for="password">Пароль</label> <input name="password" id="password" type="password" value="" required pattern=".{6,16}" title="Длина пароля должна быть от 6 до 16 символов" />
								</p>
								<p>
									<label for="comment">Комментарий</label> <input name="comment" id="comment" type="text" value="" required />
								</p>
								<p style="text-align:right;">
									<input name="submit" type="submit" value="Добавить" class="ui-button ui-corner-all ui-widget" /><input name="close" type="button" value="Отмена" class="ui-button ui-corner-all ui-widget" onclick="clclose()" />
								</p>
							</fieldset>
						</form>
					</div>
					<div id="changepsw" title="Смена пароля администратора">
						<form id="form-russianpost1" name="form-russianpost1" method="post" action="">
							<fieldset>
								<p>
									<label for="password">Новый пароль</label> <input name="newpassword" id="newpassword" type="password" value="" required pattern=".{6,16}" title="Длина пароля должна быть от 6 до 16 символов" />
								</p>
								<p>
									<label for="password1">Повторите пароль</label> <input name="password1" id="password1" type="password" value="" required pattern=".{6,16}" />
								</p>
								<p style="text-align:right;">
									<input name="submit" type="submit" value="Изменить" class="ui-button ui-corner-all ui-widget" /><input name="close" type="button" value="Отмена" class="ui-button ui-corner-all ui-widget" onclick="clclose1()" />
								</p>
							</fieldset>
						</form>
					</div>
					<div id="categoryRPO" title="Категория РПО">
						<table id="tcategoryRPO">
							<thead>
								<tr>
									<td>Код</td>
									<td>Название</td>
									<td>Действие</td>
								</tr>
							</thead>
						</table>
					</div>
					<div id="typerpo" title="Вид РПО">
						<table id="ttyperpo">
							<thead>
								<tr>
									<td>Код</td>
									<td>Название</td>
									<td>Действие</td>
								</tr>
							</thead>
						</table>					
					</div>
				</section>
			</main>	
		</div>
	</body>
</html>