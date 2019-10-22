<?php
###Ini Copyright###
###https://github.com/osyduck/Gojek-Register###

include ("function.php");

function nama()
	{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$ex = curl_exec($ch);
	// $rand = json_decode($rnd_get, true);
	preg_match_all('~(&bull; (.*?)<br/>&bull; )~', $ex, $name);
	return $name[2][mt_rand(0, 14) ];
	}
function register($no)
	{
	$nama = nama();
	$email = str_replace(" ", "", $nama) . mt_rand(100, 999);
	$data = '{"name":"' . $nama . '","email":"' . $email . '@gmail.com","phone":"+' . $no . '","signed_up_country":"ID"}';
	$register = request("/v5/customers", "", $data);
	//print_r($register);
	if ($register['success'] == 1)
		{
		return $register['data']['otp_token'];
		}
	  else
		{
      save("error_log.txt", json_encode($register));
		return false;
		}
	}
function verif($otp, $token)
	{
	$data = '{"client_name":"gojek:cons:android","data":{"otp":"' . $otp . '","otp_token":"' . $token . '"},"client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e"}';
	$verif = request("/v5/customers/phone/verify", "", $data);
	if ($verif['success'] == 1)
		{
		return $verif['data']['access_token'];
		}
	  else
		{
      save("error_log.txt", json_encode($verif));
		return false;
		}
	}
	function login($no)
	{
	$nama = nama();
	$email = str_replace(" ", "", $nama) . mt_rand(100, 999);
	$data = '{"phone":"+'.$no.'"}';
	$register = request("/v4/customers/login_with_phone", "", $data);
	//print_r($register);
	if ($register['success'] == 1)
		{
		return $register['data']['login_token'];
		}
	  else
		{
      save("error_log.txt", json_encode($register));
		return false;
		}
	}
function veriflogin($otp, $token)
	{
	$data = '{"client_name":"gojek:cons:android","client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e","data":{"otp":"'.$otp.'","otp_token":"'.$token.'"},"grant_type":"otp","scopes":"gojek:customer:transaction gojek:customer:readonly"}';
	$verif = request("/v4/customers/login/verify", "", $data);
	if ($verif['success'] == 1)
		{
		return $verif['data']['access_token'];
		}
	  else
		{
      save("error_log.txt", json_encode($verif));
		return false;
		}
	}
function claim($token)
	{
	$data = '{"promo_code":"GOFOODBOBA07"}';
	$claim = request("/go-promotions/v1/promotions/enrollments", $token, $data);
	if ($claim['success'] == 1)
		{
		return $claim['data']['message'];
		}
	  else
		{
      save("error_log.txt", json_encode($claim));
		return false;
		}
	}
echo "===========================================================\n";
echo "####### GAK ADA GUJEK YANG AMAN BY SGBTEAM MAKASSAR #######\n";
echo "############## FOLLOW LAH IG W @ABBACUGAMING ##############\n";
echo "===========================================================\n";
echo "[+] LU MAU LOGIN APA REGIS COK?? LOGIN = 1 & REGIS = 2: ";
$type = trim(fgets(STDIN));
if($type == 2){
echo "[+] MANTAP ANJING LO MILIH REGIS\n";
echo "[+] ISI 62 KLO LU MAU DAPTAR AKUN INDOG TRUS 1 UNTUK REGIS NOMER LUAR PANTEQ\n";
echo "[+] ISI NO HP LO ANJING: ";
$nope = trim(fgets(STDIN));
$register = register($nope);
if ($register == false)
	{
	echo "[-] GAGAL AMBIL OTP ASUUUUU, NOMERNYA UDAH KEREGIS, COBA GNTI YG LAEN!\n";
	}
  else
	{
	echo "[+] MASUKIN OTP MU YA PANTEQ BKN KNTL MU: ";
	// echo "Enter Number: ";
	$otp = trim(fgets(STDIN));
	$verif = verif($otp, $register);
	if ($verif == false)
		{
		echo "[-] GAGAL REGIS AKUNMU ASU GK TAU JUGA KENAPA BISA GINI!\n";
		}
	  else
		{
		echo "[=] MANTAP ANJING DAPAT POCER DARI NADIEM\n";
		$claim = claim($verif);
		if ($claim == false)
			{
			echo "[-] GAGAL DAPAT POCER ASU ANJING SIALAN, COBA LAGI LAH PANTEQ\n";
			}
		  else
			{
			echo $claim . "\n";
			}
		}
	}
}else if($type == 1){
echo "[+] MANTAP ANJING LO MILIH LOGIN\n";
echo "[+] ISI 62 KLO LU MAU DAPTAR AKUN INDOG TRUS 1 UNTUK REGIS NOMER LUAR PANTEQ\n";
echo "[+] MASUKIN NOPEMU ANJING: ";
$nope = trim(fgets(STDIN));
$login = login($nope);
if ($login == false)
	{
	echo "[-] ASU GAGAL AMBIL OTP!\n";
	}
  else
	{
	echo "[+] MASUKIN OTP MU YA PANTEQ BKN KNTL MU: ";
	// echo "Enter Number: ";
	$otp = trim(fgets(STDIN));
	$verif = veriflogin($otp, $login);
	if ($verif == false)
		{
		echo "[=] MANTAP ANJING DAPAT POCER DARI NADIEM!\n";
		}
	  else
		{
		echo "[=] MANTAP ANJING DAPAT POCER DARI NADIEM\n";
		$claim = claim($verif);
		if ($claim == false)
			{
			echo "[-] GAGAL DAPAT POCER ASU ANJING SIALAN, COBA LAGI LAH PANTEQ\n";
			}
		  else
			{
			echo $claim . "\n";
			}
		}
	}
}
?>
