<?php
require_once 'func_GetData.php';
require_once './config/define_constants.php';

function get_subservices($array){
  $forbidden_keywords = file(FORBIDDEN_SERVICES, FILE_IGNORE_NEW_LINES);
  $return_array=array();
  foreach ($array as $key => $value) {
    if (!in_array($value, $forbidden_keywords)) {
      $json = km200_GetData( $value , 0);
      if(array_key_exists ('references', $json )){
      	$j = count($json['references']);
        for($i=0; $i < $j; $i++) {
          if(array_key_exists ('id', $json['references'][$i])){
            array_push($return_array, $json['references'][$i]['id']);
            echo $json['references'][$i]['id'].'<br>';
          }
        }
      }    
    }
  }
  return $return_array;
}
?>