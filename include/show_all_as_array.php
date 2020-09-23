<?php
  require_once './config/define_constants.php';
  require_once './functions/func_GetData.php';
  require_once './functions/func_get_services_array.php';
    
  // 'Liste existiert bereits';
  $Main_services = get_services_array('all_active_main');

  echo '<ul class="collapsible" data-collapsible="accordion" id="messages">';
  foreach ($Main_services as $main_service_id => $main_service) {
    echo' <li>
            <div class="collapsible-header">
              <i class="material-icons">info_outline</i>
              '.$main_service.'
              </div>
            <div class="collapsible-body">
            <ul class="collection">
            ';
    // Subservice Tabelle
    $Services = get_services_array('all_active_sub_with_main_id', $main_service_id);
    $forbidden_keywords = file(FORBIDDEN_SERVICES, FILE_IGNORE_NEW_LINES);
    foreach ($Services as $service) {
      if (!in_array($service, $forbidden_keywords)) {
     ////////////////
      $json = km200_GetData( $service, 0 );
        if ($json['type'] != 'refEnum'){
          if($json['writeable'] == '1'){
              echo '<li class="collection-item orange lighten-2">';
          }else{
              echo '<li class="collection-item">';
          }         
          echo "<pre>";
          print_r($json);
          echo "</pre>";          
          echo '</li>';
        }
      }
    }
    // ende Subservices Tabelle
    echo'   </ul></div>
          </li>';  
  }
  echo '</ul>';
  

?>