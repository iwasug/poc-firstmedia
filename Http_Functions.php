<?php
    class Http_Functions {
        public function PostData($url, $json)
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json')
            );
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }

        function callAPI($method, $url, $data){
            $curl = curl_init($url);
            switch ($method){
               case "POST":
                  curl_setopt($curl, CURLOPT_POST, 1);
                  if ($data)
                     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                  break;
               case "PUT":
                  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                  if ($data)
                     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
                  break;
               default:
                  if ($data)
                     $url = sprintf("%s?%s", $url, http_build_query($data));
            }
            // OPTIONS:
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               'Content-Type: application/json',
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            // EXECUTE:
            $result = curl_exec($curl);
            if(!$result){die("Connection Failure");}
            curl_close($curl);
            return $result;
         }

         function callAPI_v2($method, $url, $data){
            $opts = array('http' =>
            array(
                'method'  => $method,
                'header'  => "Content-Type: application/json\r\n",
                'content' => $data,
                'timeout' => 60
            )
            );
                                
            $context  = stream_context_create($opts);
            $result = file_get_contents($url, false, $context);
            return $result;
         }


         function handOver($conversationId)
         {
            $token = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJpS216TTVkenRIWmprdmdSY3VrVHgxTzJ2SFlTM0U5YmVJME9XbXRNR1ZzIn0.eyJqdGkiOiJmNDRhOGNhNS1jZjE4LTRjZDQtYTY2Yy0wOGNhNmZiZjk3ZjkiLCJleHAiOjE5MDEwMDY2OTcsIm5iZiI6MCwiaWF0IjoxNTg1NjQ2Njk3LCJpc3MiOiJodHRwOi8vaW50ZXJuYWwtZmMtdXNlMS0wMC1rZXljbG9hay1vYXV0aC0xMzA3MzU3NDU5LnVzLWVhc3QtMS5lbGIuYW1hem9uYXdzLmNvbS9hdXRoL3JlYWxtcy9wcm9kdWN0aW9uIiwiYXVkIjoiMTc5NGZjMmUtOWI0ZC00NGZhLTlkMDEtYWUyMDExODY1MDk0Iiwic3ViIjoiMjYwMWQ2MDQtZjE2Ny00MjhjLThiZGUtZDJmMzcyYmMzNDE5IiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiMTc5NGZjMmUtOWI0ZC00NGZhLTlkMDEtYWUyMDExODY1MDk0IiwiYXV0aF90aW1lIjowLCJzZXNzaW9uX3N0YXRlIjoiYTg5N2JmNmMtY2FhMC00YTUxLWJjNDEtZmRjNDM2MjY3NGIzIiwiYWNyIjoiMSIsImFsbG93ZWQtb3JpZ2lucyI6W10sInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJhZ2VudDp1cGRhdGUgbWVzc2FnZTpjcmVhdGUgYWdlbnQ6Y3JlYXRlIGRhc2hib2FyZDpyZWFkIHJlcG9ydHM6ZXh0cmFjdDpyZWFkIHJlcG9ydHM6cmVhZCBhZ2VudDpyZWFkIGNvbnZlcnNhdGlvbjp1cGRhdGUgdXNlcjpkZWxldGUgY29udmVyc2F0aW9uOmNyZWF0ZSBvdXRib3VuZG1lc3NhZ2U6Z2V0IG91dGJvdW5kbWVzc2FnZTpzZW5kIHVzZXI6Y3JlYXRlIHJlcG9ydHM6ZmV0Y2ggdXNlcjp1cGRhdGUgdXNlcjpyZWFkIHJlcG9ydHM6ZXh0cmFjdCBjb252ZXJzYXRpb246cmVhZCIsImNsaWVudEhvc3QiOiIxOTIuMTY4LjEyOC4xNTkiLCJjbGllbnRJZCI6IjE3OTRmYzJlLTliNGQtNDRmYS05ZDAxLWFlMjAxMTg2NTA5NCIsImNsaWVudEFkZHJlc3MiOiIxOTIuMTY4LjEyOC4xNTkifQ.LciZnXS7tcW8VnA3RBkOJpEBfAnj2RvGN2hlHqk3oPqoxecnpAvVqWvPS-tO9imc2-Q6lvUHh6Nz0SkpHhgYkpPpQTkJCxUOHQJ7lM4ODNE37ZuHWR8AUn3-qYhzkPOvx5o1zxDROlqAsmE0z0DjZcR8CrcrQSTjTUs6MIxOpLfwE6bacAk9ILEW18AzmVkFoo9Zz0x5uLq1gPSANcl88bMUTUuaB1fvmLsmAsSOkGvkpDgzSqcv0bxjJiItjPmH9Snqdxu7Yadt8pgnAaWQkJUbQy7Jb9KxJ5gAmml4sDmKyVbWXke45yKbfSTkob9USyYV9w6rwh2zpaZyEvTeFQ";
            $agent = "c6759a15-d602-4a2a-8a71-72aa29a1cc4d";

            $url = "https://api.freshchat.com/v2/conversations/$conversationId";

            $jsonData = array(
               //'CustomerAccount' => '33747501',
               'conversation_id' => $conversationId,
               'status' => 'assigned',
               'assigned_agent_id' => $agent,
               'app_id' => 'e1d62e57-2274-4054-94ec-df5f59bc98ce',

           );

            $data = json_encode($jsonData);

            $headers = array(
               'Content-Type: application/json',
               'Authorization: Bearer '. $token
           );

            $opts = array('http' =>
            array(
                'method'  => 'PUT',
                'header'  => $headers,
                'content' => $data,
                'timeout' => 60
            )
            );
                                
            $context  = stream_context_create($opts);
            $result = file_get_contents($url, false, $context);
            return $result;

         }
    }
?>