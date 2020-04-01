<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // header('Method Not Allowed', true, 405);
    // echo "GET method requests are not accepted for this resource";
    // exit;
    ?>
     <!DOCTYPE html>
    <html>
    <head>
    <title>Firstmedia Chatbot</title>
    <script>
    function initFreshChat() {
        window.fcWidget.init({
        token: "917622ba-1489-4c42-8811-554312319494",
        host: "https://wchat.freshchat.com"
        });
    }
    function initialize(i,t){var e;i.getElementById(t)?initFreshChat():((e=i.createElement("script")).id=t,e.async=!0,e.src="https://wchat.freshchat.com/js/widget.js",e.onload=initFreshChat,i.head.appendChild(e))}function initiateCall(){initialize(document,"freshchat-js-sdk")}window.addEventListener?window.addEventListener("load",initiateCall,!1):window.attachEvent("load",initiateCall,!1);
    </script>
    </head>
    <body>
    <h1>Firstmedia Chatbot</h1>
    </body>
    </html> 
    <?php
}
else
{
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
            case 'handover':
                $conversationId = $data->conversationId;

                $http = new Http_Functions();
            
                $make_call = $http->handOver($conversationId);

                $result = json_decode($make_call);

                if($result != null)
                {
                    $response["status"] = 200;
                    $response["message"] = "success";
                    $response["data"] = $result;
                    echo json_encode($response);
                }
                else
                {
                    $response["status"] = 500;
                    $response["message"] = "Conversation not found";
                    echo json_encode($response);
                }

            break;
            case 'inbound':

                $db = new DB_Functions();

                $userId = $data->userId; 
                $conversation = $data->conversation; 
                $inbondCode = $data->inbondCode;

                $conve = json_encode($conversation);

                $ret = $db->insertInboundv2($userId, $conve, $inbondCode);

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

                // $userId = $data->userId; 
                // $inbondCode = $data->inbondCode;

                $ret = $db->getInboundV2();

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
            case 'content':

                $db = new DB_Functions();

                $userId = $data->userId; 
                $content = $data->content; 

                $ret = $db->insertContent($userId, $content);

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

            case 'get_content':

                $db = new DB_Functions();

                $ret = $db->getContent();

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
}
?>