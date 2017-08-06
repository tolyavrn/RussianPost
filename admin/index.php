<?php require_once('../lib/config.php');
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
		header("Location: main.php"); 
		exit();
    }
}
else
{
	if(isset($_POST['login']) && isset($_POST['password']))
	{
		$query = mysql_query("SELECT id, password FROM usr WHERE login='".$_POST['login']."' and login='admin' LIMIT 1",$link);
		//echo md5(md5($_POST['password']));
		$data = mysql_fetch_row($query);
		//echo $data[0].'---1---'.$hash.'---2--'.$data[1].'-----3---'.md5(md5($_POST['password']));
		if($data[1] === md5(md5($_POST['password'])))
		{
			$hash = md5(generateCode(10));

			mysql_query("UPDATE usr SET hash='".$hash."' WHERE id='".$data[0]."'");
			setcookie("id", $data[0], time()+60*60*24*30);
			setcookie("hash", $hash, time()+60*60*24*30);

			header("Location: main.php"); 
			exit();
		}
		else
		{
			echo "Вы ввели неправильный логин/пароль";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta name="author" content="tolya-vrn@yandex.ru">
		<meta name="publisher" content="Anatolii Ponomarev">
		<meta name="description" content="Калькулятор тарифов Почты России">
		<meta name="keywords" content="">
		<link rel="stylesheet" href="../css/jquery-ui.css">
		<link rel="stylesheet" href="../css/styleadminlogin.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<title>Админка</title>
	</head>
	<body>
		<main id="main">
			<section class="section-form-russianpost">
				<form id="form-russianpost" name="form-russianpost" method="post" action="index.php?dt=<?php echo time();?>">
					<fieldset>
						<legend>Панель администратора</legend>
						<p>
							<label for="login">Логин</label><input name="login" id="login" type="text" value="">
						</p>
						<p>
							<label for="password">Пароль</label> <input name="password" id="password" type="password" value=""><br>
						</p>
						<p style="text-align:right;margin-right:20px;">
							<input name="submit" type="submit" value="Войти" class="ui-button ui-corner-all ui-widget"/>
						</p>
					</fieldset>
				</form>
			</section>
		</main>			
	</body>
</html>