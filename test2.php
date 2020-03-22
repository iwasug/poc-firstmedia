<?php

date_default_timezone_set('Asia/Jakarta');
$date = date('Y-m-d H:i:s');
$formula = $date . '#FM#Freshwork#2020@fc9631fFreshwork';
$token = md5($formula);
//echo $formula . '</br>' . $token;
if (!empty($_POST['account'])) {
    $url = "https://fm1.firstmedia.com/FMCOMAPIRest/Content/Homepage";

    $account = $_POST['account'];

    $jsonData = array(
        //'CustomerAccount' => '33747501',
        'CustomerAccount' => $account,
        'Client' => 'Freshwork',
        'OtherInfo' => '',
        'RequestDate' => $date,
        'Token' => $token,
    );

    $options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo $result;
}
else
{
    header('HTTP/1.0 403 Forbidden');
    echo "Access Denied";
}

?>