<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Method Not Allowed', true, 405);
    echo "GET method requests are not accepted for this resource";
    exit;
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

date_default_timezone_set('Asia/Jakarta');
$date = date('Y-m-d H:i:s');
$formula = $date . '#FM#Freshwork#2020@fc9631fFreshwork';
$token = md5($formula);
$data = json_decode(file_get_contents("php://input"));
if (!empty($data))
{
    $response = array("status" => 0, "message" => "", "data" => null);
    $tag = $data->tag;
    require_once 'DB_Functions.php';
    require_once 'Http_Functions.php';
    switch ($tag)
	{
        case 'account':
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

            $http = new Http_Functions();
        
            $jsonDataEncoded = json_encode($jsonData);

            $make_call = $http->callAPI_v2('POST', $url, $jsonDataEncoded);

            $result = json_decode($make_call);

            if($result->Rcode == "104")
            {
                $response["status"] = 200;
                $response["message"] = "";
                $response["data"] = $result;
                echo json_encode($response);
            }
            else
            {
                $response["status"] = 500;
                $response["message"] = $result->Result;
                echo json_encode($response);
            }

        break;
        case 'inbound':

            $db = new DB_Functions();

            $userId = $data->userId; 
            $content = $data->content; 
            $respond = $data->respond; 
            $inbondCode = $data->inbondCode;

            $ret = $db->insertInbound($userId, $content, $respond, $inbondCode);

            if($ret)
            {
                $response["status"] = 200;
                $response["message"] = "Ok";
                echo json_encode($response);
            }
            else
            {
                $response["status"] = 500;
                $response["message"] = "InternalServerError";
                echo json_encode($response);
            }
        break;
        case 'get_inbound':

            $db = new DB_Functions();

            $userId = $data->userId; 
            $inbondCode = $data->inbondCode;

            $ret = $db->getInbound($userId, $inbondCode);

            if(count($ret) != 0)
            {
                $response["status"] = 200;
                $response["message"] = "";
                $response["data"] = $ret;
                echo json_encode($response);
            }
            else
            {
                $response["status"] = 500;
                $response["message"] = "InternalServerError";
                echo json_encode($response);
            }
        break;

        default:
            $response["status"] = 400;
            $response["message"] = "Bad Request";
            echo json_encode($response);
        break;
    }

    header("Content-Type: application/json; charset=UTF-8");
}
else
{
    header('Access Denied', true, 403);
    echo "Access Denied";
}

?>