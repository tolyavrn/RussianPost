<?php
	header('Content-Type: application/json');
	require_once('lib/lib.php');
	require_once('lib/config.php');
	
	$link=mysql_connect($servers, $user, $password) or die("Could not connect: " . mysql_error());
	mysql_select_db($db,$link);
	
	$headers = getallheaders();
	
	if (isset($headers['Authorization']))
		$login = $headers['Authorization'];
	else
		$login = 'none';
	
	if (isset($headers['X-User-Authorization']))
		$hash = $headers['X-User-Authorization'];
	else
		$hash = 'none';
	
	$browser_string = $_SERVER["HTTP_USER_AGENT"]; 
	$browser = GetBrowser($browser_string);
	
	if ($browser!='none' or IsAuth($headers['Authorization'],$headers['X-User-Authorization'],$link))
	{
		$data = json_decode(file_get_contents('php://input'), true);
		
		/*$result = array(
							'delivery-time' => array(
													'max-days'=>0,
													'min-days'=>1
												),
							'avia-rate' => array(
													'rate'=>0,
													'vat' =>1
												),
							'fragile-rate' => array(
													'rate'=>2,
													'vat' =>3
												),
							'ground-rate' => array(
													'rate'=>4,
													'vat' =>5
												),
							'insurance-rate' => array(
													'rate'=>6,
													'vat' =>7
												),
							'notice-rate' => array(
													'rate'=>8,
													'vat' =>9
												),
							'oversize-rate' => array(
													'rate'=>8,
													'vat' =>9
												),
							'total-rate' => 10,
							'total-vat'	 => 11
						);*/
		
		$sock = fsockopen("tls://otpravka-api.pochta.ru", 443, $errno, $errstr);
		//$sock = stream_socket_client("tls://otpravka-api.pochta.ru:443", $errno, $errstr);
		if (!$sock) die("$errstr ($errno)\n");

		//$data = '{"courier": false,"declared-value": 0,"dimension": {"height": 10,"length": 10,"width": 10},"fragile": false,"index-from": "200961","index-to": "117209","mail-category": "ORDINARY","mail-type": "ONLINE_PARCEL","mass": 100,"payment-method": "CASHLESS","with-order-of-notice": true,"with-simple-notice": false}';
		$data = json_encode($data);
		
		fwrite($sock, "POST /1.0/tariff HTTP/1.1\r\n");
		fwrite($sock, "Accept-Encoding: gzip,deflate\r\n");
		fwrite($sock, "Authorization: AccessToken 9a9mk3FmmR1E84cn7FHMlz9Kjm5NHAC6\r\n");
		fwrite($sock, "X-User-Authorization: Basic dmlrdG9yQG90ZGVsZG9zdGF2b2sucnU6MTIzNDU2cVE=\r\n");
		fwrite($sock, "Content-Type: application/json;charset=UTF-8\r\n");
		fwrite($sock, "Content-Length: ".strlen($data)."\r\n");
		fwrite($sock, "Host: otpravka-api.pochta.ru\r\n");
		fwrite($sock, "Connection: close\r\n");
		fwrite($sock, "User-Agent: Apache-HttpClient/4.1.1 (java 1.5)\r\n");
		fwrite($sock, "\r\n");
		fwrite($sock, $data);
		
		$body = "";
		while (!feof($sock))
			$body .= fgets($sock, 100);

		fclose($sock);
		$body = substr($body,stripos($body,'{'));
		$body = substr($body,0,strrpos($body,'}')+1);
		
		echo $body;
	}
	else
	{
		echo 'Browser none';
	}
?>