<?php	
	function GetBrowser($browser_string)
	{
		$opera='Opera';$operaLimit=strlen($opera);
		$operaMini='OperaMini';$operaMiniLimit=strlen($operaMini);
		$fireFox='Firefox';$fireFoxLimit=strlen($fireFox);
		$chrome='Chrome';$chromeLimit=strlen($chrome);
		$internetExplorer='MSIE';$internetExplorerLimit=strlen($internetExplorer);
		$browser='none';
		
		$limit=strlen($browser_string);
		for($i=0;$i<=$limit;$i++)
		{
			$thisBrowserOpera=substr($browser_string, $i, $operaLimit);
			if($thisBrowserOpera==$opera)
			{
				$browser=$opera;
				break;
			}
			
			$thisBrowseroperaMini=substr($browser_string, $i, $operaMiniLimit);
			if($thisBrowseroperaMini==$operaMini)
			{
				$browser=$operaMini;
				break;
			}
			
			$thisBrowserfireFox=substr($browser_string, $i, $fireFoxLimit);
			if($thisBrowserfireFox==$fireFox)
			{
				$browser=$fireFox;
				break;
			}
			
			$thisBrowserchrome=substr($browser_string, $i, $chromeLimit);
			if($thisBrowserchrome==$chrome)
			{
				$browser=$chrome;
				break;
			}
			
			$thisBrowserinternetExplorer = substr($browser_string, $i, $internetExplorerLimit);
			if($thisBrowserinternetExplorer==$internetExplorer)
			{
				$browser=$internetExplorer;
				break;
			}
		}
		
		return $browser;
	}
	
	function generateCode($length=6) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
		$code = "";

		$clen = strlen($chars) - 1;  
		while (strlen($code) < $length) 
		{
			$code .= $chars[mt_rand(0,$clen)];  
		}

		return $code;
	}
	
	function IsAuth($login,$hash, $link)
	{
		$query = mysql_query("SELECT id, login FROM usr WHERE login = '".$login."' and `hash`='".$hash."' and `lock`=0",$link);
		if (mysql_num_rows($query)>0)
			return true;
		else
			return false;
	}
?>