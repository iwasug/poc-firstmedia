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

    $ch = curl_init($url);

    $jsonDataEncoded = json_encode($jsonData);

    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);
    
    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
    
    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
    
    //Execute the request
    $result = curl_exec($ch);
    curl_close($ch);
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($result);
}
else
{
    header('HTTP/1.0 403 Forbidden');
    echo "Access Denied";
}

?>