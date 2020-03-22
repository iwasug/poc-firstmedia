<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));
if (!empty($_POST))
{
    //echo json_encode($data);

    $url = "https://fm1.firstmedia.com/FMCOMAPIRest/Content/Homepage";

    $account = $data->account;

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

    header("Content-Type: application/json; charset=UTF-8");
}
else
{
    header('HTTP/1.0 403 Forbidden');
    echo "Access Denied";
}

?>