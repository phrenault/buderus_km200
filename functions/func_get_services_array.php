<?php
//require_once './config/config.php';
require_once '/volume1/web/buderus_km200/config/config.php';

function get_services_array($what, $main_id=''){
  $get_query = '';
  // Subservices
  if($what == 'all_sub')$get_query =                     "SELECT * FROM `sub_services` ORDER by main_service_id ASC";
  if($what == 'all_active_sub')$get_query =              "SELECT * FROM `sub_services` where active = 1 ORDER by main_service_id ASC";  
  if($what == 'all_sub_with_main_id')$get_query =        "SELECT * FROM `sub_services` where main_service_id  = '".$main_id."' ORDER by id ASC";
  if($what == 'all_active_sub_with_main_id')$get_query = "SELECT * FROM `sub_services` where main_service_id  = '".$main_id."' AND active = 1 ";
  //Mainservices
  if($what == 'all_main')$get_query =        "SELECT * FROM `main_services` ORDER by id ASC";
  if($what == 'all_active_main')$get_query = "SELECT * FROM `main_services` where active = 1 ORDER by id ASC";
  
  $return =array();
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);
  if ($mysqli->connect_error) {die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);}
  if (mysqli_connect_error()) {die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());}
  
  if ($result = $mysqli->query($get_query)) {
      while ($service = $result->fetch_assoc()) {
        $return[$service['id']] = $service['name'];
      }
  }
  /*
  echo '<pre>';
  print_r($return);
  echo '</pre>';
  */
  $result->free();
  
  $mysqli->close();
  
  return $return;
}
?>