<?php 

  // http://developer.yahoo.com/php/howto-cacheRestPhp.html
  // this function is used to cache a response to the file system, avoid hitting the assignr.com API with every request.
  function request_cache($url, $dest_file, $timeout) { 
    if (!file_exists("timeout.txt") || filemtime("timeout.txt") < (time()-$timeout)) { 
      $data = file_get_contents($url); 
      $tmpf = tempnam('/tmp','YWS'); 
      $fp = fopen($tmpf,"w"); 
      fwrite($fp, $data); 
      fclose($fp); 
      rename($tmpf, $dest_file);
      $fp = fopen("timeout.txt","w"); 
      fwrite($fp, "hello!"); 
      fclose($fp);  
      return $data; 
    } else { 
      return file_get_contents($dest_file);
    }
  }

  // enter your assignr.com user name and API key here.
  // your API can be found on this page: https://assignr.com/client_applications
  
  $username = "your-user-name-goes-here";
  $api_key = "super-secret-api-key-goes-here";

  // set some search criteria. you can view any game that you normally have access to, so you may want to limit
  // your search to "future" and "public" games.
  
  $search_criteria= urlencode("in:future is:public");

  $response = request_cache("https://$username:$api_key@api.assignr.com/api/v1/games.json?search=$search_criteria", "games.1.json", 300);
  $json = json_decode($response);
  $row_class="even";
  $found = false;
?>
<html>
  <head>
    <title>assignr.com API Example</title>
      <style type="text/css">

        /* add/modify styles as needed */

        body, tr, td {
          font: normal small / 1.3 Verdana, Arial, Helvetica, sans-serif;
        }
        td{ 
          padding: .25em;     
        }
        tr.odd{ 
          background-color: #EEDB6A 
        }
        th{
          background-color: #617494;
          color: white;
        }
        table{
          border-collapse: collapse;
        }
        a{
          color:#000;
        }
    </style>
  </head>
  <body>
    <table>
      <tr>
        <th>Date</th>
        <th>Time</th>
        <th>Venue</th>
        <th>Age</th>
        <th>Home</th>
        <th>Away</th>
        <th>Referees</th>
      </tr>
  <?php
    while($response){
      foreach ($json->games as $game) {
        $found = true;
        $row_class = ($row_class == "even") ? "odd" : "even";
        echo "<tr class='$row_class'>";
        echo "<td class='date'>$game->localized_date</td>";
        echo "<td class='time'>$game->localized_time</td>";
        echo "<td class='venue'>$game->venue_name</td>";
        echo "<td class='age'>$game->age_group_name</td>";
        echo "<td class='team'>$game->home_team_name</td>";
        echo "<td class='team'>$game->away_team_name</td>";
        echo "<td><ul class='assignments'>";
        foreach($game->existing_assignments as $assignment){
          echo "<li><em>$assignment->position_name</em>: $assignment->name</li>";
        }
        echo "</ul></td>";
        echo "</tr>";
      }
      // make subsequent API calls as needed (for pagination)
      if ($json->page < $json->pages) {
        $page = ($json->page + 1);
        $response = request_cache("https://$username:$api_key@api.assignr.com/api/v1/games.json?search=$search_criteria&page=$page", "games.$page.json",300);
        $json = json_decode($response);
      } else { 
        $response = false;
      }        
    }   
  ?>
  </table>
  <?php
    if(!$found){
      echo "<p><strong>No games found.</strong></p>";
    }
  ?>
  <p><a href="http://assignr.com">Powered by assignr.com Referee Assigning</a></p>
  </body>
</html>
