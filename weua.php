<?PHP  
set_time_limit(0);

################################
#                              #
# Author: VTrim                #
# Email: vit777ok@gmail.com    #
#                              #
###############################

//Ваші дані для входу на сайт
$email = 'test@mail.ua';
$password = 'testpass';

$MailList = 'mail.txt'; //файл,куди будуть записуватись мейли
$Cookie = 'WEUACookie.txt';
$weua = curl_init('https://weua.info/?view=auth');
curl_setopt($weua, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($weua, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($weua, CURLOPT_REFERER, 'https://weua.info/');
curl_setopt ($weua, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);  
curl_setopt($weua, CURLOPT_POST, true);
curl_setopt($weua, CURLOPT_POSTFIELDS, 'email='.$email.'&password='.$password.'&authbutton=Увійти');
curl_setopt($weua, CURLOPT_COOKIEJAR, $Cookie);  
curl_setopt($weua, CURLOPT_COOKIEFILE, $Cookie); 
curl_setopt($weua, CURLOPT_RETURNTRANSFER, true); 
$result = curl_exec($weua);
curl_close($weua);
if(mb_stristr($result,'Невірний пароль') or mb_stristr($result,'Даний логін відсутній')) 
{
exit('Невдалий вхід!');
}
else 
{
//далі змінні ID сторінок з якого почати і яким закінчити
$StartID = 1; //з ID 1
$FinishID = 100; //до ID 100

for($id=$StartID; $id<=$FinishID; ++$id)
{
$page = curl_init('https://weua.info/id'.$id.'');
curl_setopt($page, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($page, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt ($page, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);  
curl_setopt($page, CURLOPT_COOKIEFILE, $Cookie); 
curl_setopt($page, CURLOPT_RETURNTRANSFER, true); 
$pageID = curl_exec($page);
if(preg_match("|<a href='mailto:(.*)'>|", $pageID, $mail))
{
$m = file($MailList);
$mail = "$mail[1]\n";

if (!in_array($mail, $m)) 
{
file_put_contents($MailList, $mail, FILE_APPEND);
}

echo 'ID '.$id.' - '.$mail.'<br/>';
}
else
{
echo 'ID '.$id.' - Email або сторінка недоступні...<br/>';
}
}
}

?>
