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
            <div class="collapsible-body">';
    // Subservice Tabelle
    echo '<table class="striped responsive-table">';
    echo '<tr>';
    echo '<th>ID</th>';
  //  echo '<th>Type</th>';
  //  echo '<th>recordable</th>';
    echo '<th>value</th>';
    echo '<th>unitOfMeasure</th>';
    echo '<th>allowedValues</th>';
    echo '<th>values</th>';  
    echo '</tr>';              
    $Services = get_services_array('all_active_sub_with_main_id', $main_service_id);
    $forbidden_keywords = file(FORBIDDEN_SERVICES, FILE_IGNORE_NEW_LINES);
    foreach ($Services as $service) {
      if (!in_array($service, $forbidden_keywords)) {
     ////////////////
      $json = km200_GetData( $service, 0 );
        if ($json['type'] != 'refEnum'){
          if($json['writeable'] == '1'){
              echo '<tr class="orange lighten-2">';
          }else{
              echo '<tr>';
          }      
            echo '<td>'.$json['id'].'</td>';
  //          echo '<td>'.$json['type'].'</td>';
  //          echo '<td>'.$json['recordable'].'</td>';
            if(isset($json['value'])){
              echo '<td>'.$json['value'].'</td>';          
            }else{
              echo '<td></td>';          
            }
            
            if(isset($json['unitOfMeasure'])){
              echo '<td>'.$json['unitOfMeasure'].'</td>';          
            }else{
              echo '<td></td>';          
            }
            
            if(isset($json['allowedValues'])){
            echo '<td>';
              echo "<pre>";
              print_r($json['allowedValues']);
              echo "</pre>";
            echo '</td>';          
            }else{
              echo '<td></td>';          
            }
            
            if(isset($json['values'])){
              echo '<td>';
                echo "<pre>";
                print_r($json['values']);
                echo "</pre>";
              echo '</td>';         
            }else{
              echo '<td></td>';          
            }
          echo '</tr>';      
        }
      }
    }
      echo '</table>';
    // ende Subservices Tabelle
    echo'   </div>
          </li>';  
  }
  echo '</ul>';
  

?>