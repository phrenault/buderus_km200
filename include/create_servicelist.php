<?php
//Subservices erstellen
require_once './config/config.php';
require_once './config/define_constants.php';
require_once './functions/func_get_subservices.php';

$forbidden_keywords = file(FORBIDDEN_SERVICES, FILE_IGNORE_NEW_LINES);

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);
if ($mysqli->connect_error) {die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);}
if (mysqli_connect_error()) {die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());}
// hole Main Services aus DB
$get_query = "SELECT * FROM `main_services` where active = 1 ORDER by ID DESC";

if ($result = $mysqli->query($get_query)) {
    while ($main_service = $result->fetch_assoc()) {
      $service = array(0 => $main_service["name"]);
      //print_r ($service);
      //while ($row = $result->fetch_assoc()) {
      // printf ("%s : %s \n", $row["name"], $row["active"]);
	  //}
      $level_1 = get_subservices($service);
      $level_2 = get_subservices($level_1);
      $level_3 = get_subservices($level_2);
      $level_4 = get_subservices($level_3);
      $level_5 = get_subservices($level_4);
      
      $sub_services = array_merge($service, $level_1, $level_2, $level_3, $level_4, $level_5);
      sort($sub_services);
     
      foreach ($sub_services as $service) {
        $get_query = "SELECT * FROM `sub_services` WHERE `name` = '".$service."' LIMIT 1";

        if ($get_result = $mysqli->query($get_query)) {
          $allready_exist = $get_result->num_rows;
          if($allready_exist == 0){
            if (!in_array($service, $forbidden_keywords)) {
                  $mysqli->query("
                  INSERT INTO  `sub_services` (
                                                `id` ,
                                                `main_service_id` ,
                                                `name` ,
                                                `active`
                                              )
                                              VALUES (
                                                NULL , 
                                                '".$main_service["id"]."' ,
                                                '".$service."',
                                                '1'
                                              )
                  ");   
            }else{
                  $mysqli->query("
                  INSERT INTO  `sub_services` (
                                                `id` ,
                                                `main_service_id` ,
                                                `name` ,
                                                `active`
                                              )
                                              VALUES (
                                                NULL , 
                                                '".$main_service["id"]."' ,
                                                '".$service."',
                                                '0'
                                              )
                  "); 
            }          
          }
          $get_result->close();        
        }
      }
    }
    $result->free();
}
$mysqli->close();

/*
$Main_services = file(MAIN_SERVICES, FILE_IGNORE_NEW_LINES);

$level_1 = get_subservices($Main_services);

$level_2 = get_subservices($level_1);

$level_3 = get_subservices($level_2);

$level_4 = get_subservices($level_3);

$level_5 = get_subservices($level_4);

$Services = array_merge($Main_services, $level_1, $level_2, $level_3, $level_4, $level_5);
sort($Services);

$fp = fopen(CREATED_SERVICELIST, 'w'); 
foreach($Services as $values) fputs($fp, $values."\r\n"); 
fclose($fp);
echo '<h2>'.CREATED_SERVICELIST .' erstellt. Bitte aktualisieren</h2>';
*/        
?>