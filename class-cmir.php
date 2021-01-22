<?php
class coinmaster
{
	const CURL_TIMEOUT = 3600;
	const CONNECT_TIMEOUT = 30;
	protected $_cookieFileLocation = './cookie.txt';

	private function Curl($method, $url, $header, $data, $cookie)
	{
		$this->_cookieFileLocation = dirname(__FILE__) . '/cookie.txt';
		//Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36
		//Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36
		//Mozilla/5.0 (iPhone; CPU iPhone OS 14_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 LightSpeed [FBAN/MessengerLiteForiOS;FBAV/294.0.0.60.128;FBBV/262406393;FBDV/iPhone12,8;FBMD/iPhone;FBSN/iOS;FBSV/14.2;FBSS/2;FBCR/;FBID/phone;FBLC/th-Qaau;FBOP/0]
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array()));
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::CONNECT_TIMEOUT);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		if ($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		if ($data) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		if ($cookie) {
			curl_setopt($ch, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_cookieFileLocation);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_cookieFileLocation);
		}
		$output = curl_exec($ch);
		curl_close($ch);  
		return $output;
	}
	private function header()
	{
		$header = array(
			"Expect: 100-continue",
			"Connection: keep-alive",
			"Host: vik-game.moonactive.net"
		);
		return $header;
	}
	public function setCookiFileLocation($path)
	{
		$this->_cookieFileLocation = $path;
	}
	private function headerwhittoken($devicetoken)
	{
		$header = array(
			"Expect: 100-continue",
			"Connection: keep-alive",
			"X-CLIENT-VERSION: 3.5.191",
			"Cookie: cme=global;",
			"Content-Type: application/x-www-form-urlencoded",
			"Authorization: Bearer " . $devicetoken,
			"Host: vik-game.moonactive.net"
		);
		return $header;
	}
	private function gen_uuid()
	{
		return sprintf(
			'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0x0fff) | 0x4000,
			mt_rand(0, 0x3fff) | 0x8000,
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff)
		);
	}
	private function gettokenfb()
	{
		$access_tokenfb = [
			'1037120606711249|cL1nNHRgPj1EsFrrKDG-26FePOM',
			'111660834022401|WhdA3EIzKPynFarF23m3M1nd4SQ',
			'1586205888257326|6dShRNgKQ0adhG5bHwN_HHsZkxE',
			'1723523247814358|sQq-xgYisKsu8f-YOIYRUg1Ml1w',
			'1754582024695406|lNASfnK254LszhhQZoyjO8EigQM',
			'2299913480152618|XZfHCSON7wSEMLSHq3ffrhp4EGY',
			'2649527288634087|jwrYhxu-0MroqJkbeT5gd9xEI0Y',
			'2738314886393290|8R8L2EaJnHz8IbGnARmUu47PF-0',
			'279645990018178|rhSQVq6r1bYo-lS2sM47UThxRaw',
			'317114656075656|MhBD4r9o9aP-hwNI-ya0q2ryvdM',
			'3460783750672092|Fvtafm9px70t9m2hT-M_b75ypJ8',
			'383880679398496|vxFRNk1aT-MvU-24Doe0fv5uBvQ',
			'3920543907968468|k7oYX0VcCpmMRFDMP2l2LI5MBaA',
			'409012247149210|hi131l6XWw74FVMnpKW2GtFVIoU',
			'4103385286344225|q_SwQP09cL7ipMIzUD57Wqi4WlA',
			'425782572130545|c1DT4N0-zySnyAZryb0iOFPh5SE',
			'670835880297746|w4ZI3oD5c1a4C7RFWwbNzrOPENo',
			'703051320325435|B60NRK6IcL424hnJ1KggKuML_d0',
			'983739412127363|OR3FsghdD6IdEA3K7Urb0vRudo8'
		];
		$bz = 0;
		do {
			$facebookgen = $this->Curl("GET", "https://graph.facebook.com/670835880297746/accounts/test-users?access_token=" . $access_tokenfb[$bz] . "&installed=true&permissions=read_stream&method=post", false, false, false);
			$token = json_decode($facebookgen, true);
			$bz++;
			if ($bz >= 20) {
				$bz = 0;
				shuffle($access_tokenfb);
			}
		} while (isset($token['error']));
		//echo $access_tokenfb[$bz];
		return $token['access_token'];
	}
	private function Login($deviceID, $devicetoken)
	{
		$data = "Device%5budid%5d=" . $deviceID . "&API_KEY=viki&API_SECRET=coin&Client%5bversion%5d=3.5_fband&Device%5bchange%5d=20201105_5&fbToken=&seq=0";
		$login = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/login", $this->headerwhittoken($devicetoken), $data, true);
		$info = json_decode($login, true);
		$res = array(
			"deviceID" => $deviceID,
			"info" => array(
				"change_timestamp" => $info['change_timestamp'],
				"profile" => $info['profile'],
				"sessionToken" => $info['sessionToken'],
				"userId" => $info['userId']
			)
		);
		return json_encode($res, JSON_UNESCAPED_SLASHES);
	}
	private function Loginfbgame($deviceID, $devicetoken, $userid, $fbtoken)
	{
		$data = "Device%5budid%5d=" . $deviceID . "&API_KEY=viki&API_SECRET=coin&User%5bfb_token%5d=" . $fbtoken . "&p=fb&Client%5bversion%5d=3.5.191_fband&Device%5bchange%5d=20201105_5";
		$startlogin = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/" . $userid . "/update_fb_data", $this->headerwhittoken($devicetoken), $data, true);
		return $startlogin;
	}
	private function GenerateFakeID()
	{
		$deviceID = $this->gen_uuid();
		$data = array(
			'deviceId' => $deviceID
		);
		$start = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/authentication/register", $this->header(), $data, true);
		$register = json_decode($start, true);
		$startlogin = $this->Login($deviceID, $register['deviceToken']); //รับค่าทั่งหมด
		$startlogin = json_decode($startlogin, true); //แปลงเป็น array
		$this->deviceID = $startlogin['deviceID'];
		$this->nonfbuserId = $startlogin['info']['userId'];
		$this->sessionToken = $startlogin['info']['sessionToken'];
		echo "FAKE ID GENERATED\n";
	}
	private function LoginFakeID($link)
	{
		$facetoken = $this->gettokenfb();
		$startloginfb = $this->Loginfbgame($this->deviceID, $this->sessionToken, $this->nonfbuserId, $facetoken);
		$startloginfb = json_decode($startloginfb, true);

		if (empty($startloginfb['userId'])) {
			$this->addspin($link);
			exit();
		}
		$this->userId = $startloginfb['userId'];
		$this->fbUserId = $startloginfb['fbUserId'];
		$this->fbToken = $startloginfb['fbToken'];
		echo "FAKE ID ONLINE\n";
	}
	public function addspin($inviteLink)
	{
		//ตัดให้เหลือแค่รหัสเชิญ
		$GetArray = preg_match_all('/~[^}]*?s=m/', $inviteLink, $a);
		if ($GetArray == NULL) {
			preg_match_all('/~[^}]*/', $inviteLink, $a);
			$edit1 = str_replace('~', '', $a[0]);
			$edit2 = str_replace('', '', $edit1[0]);
			$code = $edit2;
		} else {
			$edit1 = str_replace('~', '', $a[0]);
			$edit2 = str_replace('?s=m', '', $edit1[0]);
			$code = $edit2;
		}
		//สร้างลิงค์ฉบับเต็ม
		$getuseridaddlink = $this->Curl("GET", "https://vik-game.moonactive.net/external/users/~" . $code . "/invite?s=m", false, false, true);
		preg_match_all('/https:\/\/coinmaster.onelink.me(.*)/', $getuseridaddlink, $pora);
		$url = str_replace('&amp;', '&', $pora[0][0]);
		$url_components = parse_url($url);
		parse_str($url_components['query'], $params);
		$getuseridaddlink2 = $params['c'];
		echo "Your ID: ".$getuseridaddlink2."\n";
		if($getuseridaddlink2=="expired"){
			
			return true;
		}
		$this->GenerateFakeID();//เปิดเกมส์สร้างไอดีเครื่อง 
		$this->LoginFakeID($inviteLink);//สร้างเฟซปลอมมาพ่วงกับไอดีเครื่อง
		
		$dataStart = "Device%5budid%5d=" . $this->deviceID . "&API_KEY=viki&API_SECRET=coin&Device%5bchange%5d=20201105_4&fbToken=" . $this->fbToken . "&locale=th&1604586433725=delete";
		$dataconfig = "Device%5budid%5d=" . $this->deviceID . "&API_KEY=viki&API_SECRET=coin&Device%5bchange%5d=20201105_5&fbToken=" . $this->fbToken . "&locale=th&map%5blocale%5d=th";
		$balanceconfig = "Device%5budid%5d=" . $this->deviceID . "&API_KEY=viki&API_SECRET=coin&Device%5bchange%5d=20201105_5&fbToken=&locale=th&Device%5bos%5d=Android&Client%5bversion%5d=3.5.210&extended=true&config=all&segmented=true&include%5b0%5d=pets&include%5b1%5d=vquestRewards";
		$dataHouse1 = "Device%5budid%5d=" . $this->deviceID . "&API_KEY=viki&API_SECRET=coin&Device%5bchange%5d=20201105_4&fbToken=" . $this->fbToken . "&locale=th&item=House&state=0&include%5b0%5d=pets";
		$dataHouse2 = "Device%5budid%5d=" . $this->deviceID . "&API_KEY=viki&API_SECRET=coin&Device%5bchange%5d=20201105_4&fbToken=" . $this->fbToken . "&locale=th&item=House&state=1&include%5b0%5d=pets";
		$dataFarm = "Device%5budid%5d=" . $this->deviceID . "&API_KEY=viki&API_SECRET=coin&Device%5bchange%5d=20201105_4&fbToken=" . $this->fbToken . "&locale=th&item=Farm&state=0&include%5b0%5d=pets";
		$dataShip = "Device%5budid%5d=" . $this->deviceID . "&API_KEY=viki&API_SECRET=coin&Device%5bchange%5d=20201105_4&fbToken=" . $this->fbToken . "&locale=th&item=Ship&state=0&include%5b0%5d=pets";
		$dataaccept_invitation = "Device%5budid%5d=" . $this->deviceID . "&API_KEY=viki&API_SECRET=coin&Device%5bchange%5d=20201105_5&fbToken=&locale=th&inviter=" . $getuseridaddlink2;
		//echo "URL: https://vik-game.moonactive.net/api/v1/users/".$this->userId."/accept_invitation?$dataaccept_invitation\n";
		//กดรับไอดีจากคำเชิญ
		$accept_invitation = $this->Curl("POST", "https://vik-game.moonactie.net/api/v1/users/" . $this->userId . "/accept_invitation", $this->headerwhittoken($this->sessionToken), $dataaccept_invitation, true);
		preg_match_all('/Access denied/', $accept_invitation, $output_array);
		if(count($output_array)>0){
			echo $getuseridaddlink;
			echo "\n";
			echo $getuseridaddlink2;
			echo "\n";
			echo "https://vik-game.moonactive.net/external/users/~".$code."/invite?s=m\n";
			echo "https://vik-game.moonactive.net/api/v1/users/".$this->userId."/accept_invitation?$dataaccept_invitation\n";
			echo $accept_invitation;
			echo "\n";
			echo "FAIL PROCESS\n";
			readline("Enter to Exit");
			return true; 
		}else{
			echo "ACCEPT YOUR INVITATION\n";
		}
		$config = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/" . $this->userId . "/config", $this->headerwhittoken($this->sessionToken), $dataconfig, true);
		echo "CHOOSE LANG\n";
		$balance = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/" . $this->userId . "/balance", $this->headerwhittoken($this->sessionToken), $balanceconfig, true);
		echo "ACCEPT REWARD\n";
		$datafriends = "Device%5budid%5d=" . $this->deviceID . "&API_KEY=viki&API_SECRET=coin&Device%5bchange%5d=20201105_5&fbToken=" . $this->fbToken . "&locale=th&non_players=500&p=fb&snfb=true";
		$friends = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/" . $this->userId . "/friends", $this->headerwhittoken($this->sessionToken), $datafriends, true);
		echo "DISALLOW FRIENDLIST\n";
		$upgread = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/" . $this->userId . "/upgrade", $this->headerwhittoken($this->sessionToken), $dataHouse1, true);
		echo "OPEN VILLAGE\n";
		echo "SPIN:";
		$coun = 1;
		for ($i = 1; $i <= 18; $i++) {
			$coun++;
			$dataspin = "Device%5budid%5d=" . $this->deviceID . "&API_KEY=viki&API_SECRET=coin&Device%5bchange%5d=20201105_4&fbToken=" . $this->fbToken . "&locale=th&seq=" . $coun . "&auto_spin=False&bet=1&Client%5bversion%5d=3.5.210_fband";
			$Spin = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/" . $this->userId . "/spin", $this->headerwhittoken($this->sessionToken), $dataspin, true);
			echo $i . " ";
		}
		echo "\n";
		$start = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/" . $this->userId . "/read_sys_messages", $this->headerwhittoken($this->sessionToken), $dataStart, true);
		echo "READ MESSAGE\n";
		$upgreadHouse = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/" . $this->userId . "/upgrade", $this->headerwhittoken($this->sessionToken), $dataHouse2, true);
		echo "UPGRADE VILLAGE\n";
		$upgreadFarm = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/" . $this->userId . "/upgrade", $this->headerwhittoken($this->sessionToken), $dataFarm, true);
		echo "UPGRADE FARM\n";
		$upgreadShip = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/" . $this->userId . "/upgrade", $this->headerwhittoken($this->sessionToken), $dataShip, true);
		echo "UPGRADE SHIP\n";
		$dataconfigloop = "Device%5budid%5d=" . $this->deviceID . "&API_KEY=viki&API_SECRET=coin&Device%5bchange%5d=20201105_5&fbToken=" . $this->fbToken . "&locale=th&map%5bMaxXP%5d=4";
		$configloop = $this->Curl("POST", "https://vik-game.moonactive.net/api/v1/users/" . $this->userId . "/config", $this->headerwhittoken($this->sessionToken), $dataconfigloop, true);
		return $accept_invitation;
	}
}
