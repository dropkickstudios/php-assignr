<?php 

  // CONFIGURATION VARIABLES

  // assignr.com user name
  $username = "your-user-name-here";
  
  // assignr.com API key
  // your API can be found on this page: https://assignr.com/client_applications
  $api_key = "your-api-key-here";

  // END CONFIGURATION VARIABLES

/*
  params:
    $u: assignr.com username
    $p: assignr.com API key
    $gameID: assignr.com game ID of the game you want to update
    $params: array of key/values for the fields you want to change. 
  returns:
    stdClass object  like this:
    stdClass Object
    (
        [success] => 1 (true when response was success, otherwise false)
        [response_code] => 200 (HTTP response code)
        [data] => stdClass Object
            ( ... parsed JSON response, see http://assignr.com/help/api/api-games for details ... )
    )
    
    
*/ 
  function updateGame($u, $p, $gameID, $params){
    $curl = curl_init();
    $data = json_encode(array("game" => $params));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "$u:$p");
    curl_setopt($curl, CURLOPT_URL, "https://api.assignr.com/api/v1/games/$gameID.json");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));    
    ob_start(); 
    curl_exec($curl); 
    $http_status = curl_getinfo($curl,CURLINFO_HTTP_CODE);
    $retrievedhtml = ob_get_clean(); 
    curl_close($curl); 
    $return = new stdClass;
    $return->success = ($http_status === 200 ? TRUE : FALSE);
    $return->response_code = $http_status;
    $return->data = json_decode($retrievedhtml);
    return $return;
    
  }

  
  
  // updating one attribute on the game. only the attributes that are specified will be updated, all others
  // will remain the same.
  $responses[0] = updateGame($username, $api_key, 2790281, array(
    "is_public" => "1"
  ));
  
  // updating multiple attributes on the game
  $responses[1] = updateGame($username, $api_key, 2790281, array(
    "localized_time" => "3:30 PM", 
    "venue_name" => "Kodiak Field 1" 
  ));
  
  foreach ($responses as $response){
    if ($response->success){
      echo "Success. Game ID is " . $response->data->game->id ."\n";
    } else {
      echo "Failed with error code {$response->response_code}.\n";
      if ($response->data){
        echo "Errors are as follows:\n";
        foreach($response->data->error as $errorObject){
          echo " => {$errorObject->message} \n"; 
        }      
      }
    }
  }


