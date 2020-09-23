<?php
//#######################################################
//### Author: Philippe Renault, Date: 05.02.2020        #
//###													#
//### ToDo: -											#
//#######################################################

// Diese Sktipt muss über einen Cron Job regelmässig aufgerufen werden, um die Werte in die DB zu schreiben.

  require_once 'config/define_constants.php';
  require_once 'functions/func_GetData.php';
  require_once 'functions/func_get_services_array.php';

  $Main_services = get_services_array('all_active_main');
//  echo "<pre>";
//  print_r($Main_services);
//  echo "</pre>"; 
  foreach ($Main_services as $main_service_id => $main_service) {
    // Subservice Tabelle
    $Services = get_services_array('all_active_sub_with_main_id', $main_service_id);
    $forbidden_keywords = file(FORBIDDEN_SERVICES, FILE_IGNORE_NEW_LINES);
    foreach ($Services as $service) {
      if (!in_array($service, $forbidden_keywords)) {
        $json = km200_GetData( $service, 1 );
        // nichts sichtbares tun, nur DB updaten
        // echo $service .'<br>';
      }
    }
    // ende Subservices Tabelle
  }
?>