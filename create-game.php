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
      $params: array of key/values for the fields you want to change. 
    returns:
      stdClass object  like this:
      stdClass Object
      (
          [success] => 1 (true when response was success, otherwise false)
          [response_code] => 201 (HTTP response code)
          [data] => stdClass Object
              ( ... parsed JSON response, see http://assignr.com/help/api/api-games for details ... )
      )

  */ 


  function createGame($u, $p, $params){
    $curl = curl_init();
    $data = json_encode(array("game" => $params));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "$u:$p");
    curl_setopt($curl, CURLOPT_URL, "https://api.assignr.com/api/v1/games.json");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));    
    ob_start(); 
    curl_exec($curl); 
    $http_status = curl_getinfo($curl,CURLINFO_HTTP_CODE);
    $retrievedhtml = ob_get_clean(); 
    curl_close($curl); 
    $return = new stdClass;
    $return->success = ($http_status === 201 ? true : false);
    $return->response_code = $http_status;
    $return->data = json_decode($retrievedhtml);
    return $return;
  }

  
  $responses[0] = createGame($username, $api_key, array("localized_date" => "tomorrow"));

  // you'll need to pass in a "pattern_name" that matches the name of an officiating pattern
  // in your system. You can also pass in a pattern_id if you know the pattern you want to use.
  
  $responses[1] = createGame($username, $api_key, array(
    "localized_date" => "tomorrow",
    "pattern_name" => "3 officials",
    "localized_time" => "3:00 PM",
    "home_team_name" => "Blue Team",
    "away_team_name" => "Red Team",
    "venue_name" => "Cascade Middle School",
    "age_group_name" => "10U Boys"
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



