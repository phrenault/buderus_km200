<?php
require_once './config/config.php';

function get_fehler_info_array($stoer_code,$zusatz_code){

  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);
  if ($mysqli->connect_error) {die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);}
  if (mysqli_connect_error()) {die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());}

  $get_fehler_codes_query = "SELECT * FROM `fehler_codes` WHERE `stoer_code` = '".$stoer_code."' AND `zusatz_code` = '".$zusatz_code."'  LIMIT 1";              
    if ($result_fehler_code = $mysqli->query($get_fehler_codes_query)) {
      $fehler_code = $result_fehler_code->fetch_assoc();
      //zugehörige Störungsklasse auslesen
      $get_fehler_klasse_query = "SELECT * FROM `fehler_klassen` WHERE `klasse` = '".$fehler_code['klasse']."' LIMIT 1";              
        if ($result_fehler_klasse = $mysqli->query($get_fehler_klasse_query)) {
          $fehler_klasse = $result_fehler_klasse->fetch_assoc(); 
          $result_fehler_klasse->free();
        }
      $result_fehler_code->free();
    }
  $mysqli->close();
  $fehler_info_array = array_merge($fehler_klasse, $fehler_code);

  return $fehler_info_array;
}
?>