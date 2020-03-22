<?php

date_default_timezone_set('Asia/Jakarta');
$date = date('Y-m-d H:i:s');
$formula = $date . '#FM#Freshwork#2020@fc9631fFreshwork';
$token = md5($formula);
$json_str = file_get_contents('php://input');
//echo $formula . '</br>' . $token;
if (!empty($json_str) {
    $url = "https://fm1.firstmedia.com/FMCOMAPIRest/Content/Homepage";

    $obj = json_decode($json_str);    
    $tag = $obj->tag;

    echo $json_str;

    $jsonData = array(
        //'CustomerAccount' => '33747501',
        'CustomerAccount' => $tag,
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


    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($result);
}
else
{
    header('HTTP/1.0 403 Forbidden');
    echo "Access Denied";
}

?>