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
  
  $get_query = "SELECT * FROM `main_services` ORDER by id ASC";
  
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);
  if ($mysqli->connect_error) {die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);}
  if (mysqli_connect_error()) {die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());}
  
  if ($result = $mysqli->query($get_query)) {
    echo '<ul class="collection ">';  
      while ($service = $result->fetch_assoc()) {
        echo '
            <li class="collection-item">
              <div>'.$service['name'].'
                <div class="switch secondary-content">
                  <label>
                    Off';
        if($service['active'] == '1'){
          echo '<input type="checkbox" name="Mainservices" id="'.$service['id'].'" onclick="save_checkbox(this.name, this.id ,this.checked);" checked >';
        }else{
          echo '<input type="checkbox" name="Mainservices" id="'.$service['id'].'" onclick="save_checkbox(this.name, this.id ,this.checked);"         >';
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
  $result->free();
  
  $mysqli->close();
?>
