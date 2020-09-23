<script  type="text/javascript">
 function save_checkbox(Main_or_Sub, checkbox_id, on_off) {
    $.post( 'ajax_update_services.php' , {service:Main_or_Sub, 
                                          id : checkbox_id, 
                                          active : on_off 
                                          }, 
       function( response ) {
         //alert(response);
         $( "#result" ).html( response );
       }
    );
 }
</script>
<div id="result"></div>
<?php
  require_once './config/config.php';
  require_once './config/define_constants.php';

  $get_main_query = "SELECT * FROM `main_services` ORDER by id ASC";
  
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);
  if ($mysqli->connect_error) {die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);}
  if (mysqli_connect_error()) {die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());}
  
  

  if ($result_main = $mysqli->query($get_main_query)) {
    echo '<ul class="collapsible" data-collapsible="accordion">';  
      while ($main_service = $result_main->fetch_assoc()) {
        echo '
          <li>
            <div class="collapsible-header">';
              if($main_service['active'] == '1'){
                echo '<input type="checkbox" id="Main'.$main_service['id'].'" checked="checked" disabled="disabled" />';
              }else{
                echo '<input type="checkbox" id="Main'.$main_service['id'].'"                   disabled="disabled" />';
              }
              echo '            
              <label for="Main'.$main_service['id'].'">'.$main_service['name'].'</label>
            </div>
            <div class="collapsible-body">
              <!-- liste start -->';
              if($main_service['active'] == '1'){
                $get_sub_query = "SELECT * FROM `sub_services` WHERE `main_service_id` = '".$main_service['id']."' ORDER by id ASC";              
                  if ($result_sub = $mysqli->query($get_sub_query)) {
                    echo '<ul class="collection">';
                    while ($sub_service = $result_sub->fetch_assoc()) {
                      echo '
                          <li class="collection-item">
                            <div>'.$sub_service['name'].'
                              <div class="switch secondary-content">
                                <label>
                                  ';
                      if($sub_service['active'] == '1'){
                        echo 'Off<input type="checkbox" name="Subservices" id="'.$sub_service['id'].'" onclick="save_checkbox(this.name, this.id ,this.checked);" checked >';
                      }else{
                        $forbidden_keywords = file(FORBIDDEN_SERVICES, FILE_IGNORE_NEW_LINES);
                        if (!in_array($sub_service['name'], $forbidden_keywords)) {
                          echo 'Off<input type="checkbox" name="Subservices" id="'.$sub_service['id'].'" onclick="save_checkbox(this.name, this.id ,this.checked);"         >';                        
                        }else{
                          echo '<i class="material-icons">block</i>Off<input disabled type="checkbox" name="Subservices" id="'.$sub_service['id'].'" >';                        
                        }
                      }
                      echo '                    
                                  <span class="lever"></span>
                                  On
                                </label>
                              </div>
                          </div>
                        </li>    
                      ';      
                    }
                    echo '</ul>';
                  }
                  $result_sub->free();
              }else{
                echo 'Mainservice disabled';
              }              

              echo '
              <!-- liste end -->
            </div>
          </li>
        ';     
      }
    echo '</ul>';      
  }
  $result_main->free();
  
  $mysqli->close();
?>
                

                  