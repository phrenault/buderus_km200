<?php
/*
kunigunde 2017_12_12

Da die verschiedenen Heizungen unterschiedliche Services zur Verfügung stellen 
kann nicht direkt die default text eingetragen werden da nicht bekannt ist ob dieser Text überhaubt benötigt wird.
Aus diesem Grund die etwas komplizierte Vorgehensweise in dieser Datei, somit aber flexibel einsetzbar.

Falls Tabelle 'services_text' nicht existiert wird diese erstellt.
jetzt wird geschaut ob bereits ein Text hinterlegt ist.
Falls nicht wird in der config/default_services_text.txt Datei nachgeschaut ob ein Defaulttext hinterlegt ist wenn ja wird dieser Text eingetragen wenn nein nur ein dummy.
alle Texte sind danach direkt im Browser editierbar. 
*/
?>
<script  type="text/javascript">
$(document).ready(function() {
    $('.subbtn').click(function(){
    
      var fieldId = $(this).attr('id');
      
      $('#result').load('ajax_update_services_text.php', {
         'sub_id': fieldId,
         'short_text': $('#short_text_' + fieldId).val(),
         'long_text': $('#long_text_' + fieldId).val()
      });
      
      return false;

      });
});
</script>
<div id="result"></div>
<?php
  require_once './config/config.php';
  require_once './config/define_constants.php';
  require_once './functions/func_txt_array.php';

  
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);
  if ($mysqli->connect_error) {die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);}
  if (mysqli_connect_error()) {die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());}

// Tabelle Texte erstellen falls noch nicht existiert
$create_query = "
CREATE TABLE IF NOT EXISTS `services_text` (
  `sub_service_id` int(11) NOT NULL,
  `short_text` varchar(15) DEFAULT 'No Entry',
  `long_text` varchar(50) DEFAULT 'Noch kein Eintrag',
  UNIQUE KEY `sub_service_id` (`sub_service_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                "; 
$mysqli->query($create_query);
// Tabelle erstellt
$default_text_array = txt_array(DEFAULT_SERVICES_TEXT,';','');
/*
echo "<pre>";
print_r($default_text_array);
echo "</pre>";
*/  
            
  $get_main_query = "SELECT * FROM `main_services` ORDER by id ASC";
  if ($result_main = $mysqli->query($get_main_query)) {
    
    echo '<ul class="collapsible" data-collapsible="accordion">';  
      while ($main_service = $result_main->fetch_assoc()) {
        echo '
          <li>
            <div class="collapsible-header">';
              echo '            
              <label for="Main'.$main_service['id'].'">'.$main_service['name'].'</label>
            </div>
            <div class="collapsible-body">
              <!-- liste start -->';

                $get_sub_query = "SELECT * FROM `sub_services` WHERE `main_service_id` = '".$main_service['id']."' ORDER by id ASC";              
                  if ($result_sub = $mysqli->query($get_sub_query)) {
                    echo '<ul class="collection">';
                    while ($sub_service = $result_sub->fetch_assoc()) {
                      // Check ist Subservice text bereits vorhanden?
                      $check_sub_query = "SELECT * FROM `services_text` WHERE `sub_service_id` = '".$sub_service['id']."' ";
                      if ($result_check_sub = $mysqli->query($check_sub_query)) {
                        $sub_text = $result_check_sub->fetch_assoc();
                        if(!$sub_text['sub_service_id']){//noch keinen Eintrag in DB gefunden
                          // schauen ob bereits ein Eintrag in der defaultliste vorhanden ist
                          if(array_key_exists($sub_service['name'],$default_text_array) ){//Defaultwert existiert
                            // Eintrag erstellen mit default Werten
                            $insert_sub_text_dummy_query = "INSERT INTO  `services_text` ( `sub_service_id` ,
                                                                                          `short_text` ,
                                                                                          `long_text`
                                                                                          )
                                                                                          VALUES (
                                                                                          '".$sub_service['id']."',  '".$default_text_array[$sub_service['name']]['short_text']."',  '".$default_text_array[$sub_service['name']]['long_text']."'
                                                                                          );";
                            $short_text = 'value="'.$default_text_array[$sub_service['name']]['short_text'].'"';
                            $long_text = 'value="'.$default_text_array[$sub_service['name']]['long_text'].'"'; 
                          }else{//kein Defaultwert gefunden, 'no Entry' Eintrag erstellen
                            $insert_sub_text_dummy_query = "INSERT INTO  `services_text` ( `sub_service_id` ,
                                                                                          `short_text` ,
                                                                                          `long_text`
                                                                                          )
                                                                                          VALUES (
                                                                                          '".$sub_service['id']."',  'No Entry',  'Noch kein Eintrag'
                                                                                          );";
                            $short_text = 'placeholder="No Entry"';
                            $long_text = 'placeholder="Noch kein Eintrag"';                           
                          }
                          $mysqli->query($insert_sub_text_dummy_query);

                        }else{
                          // Eintrag bereits vorhanden, nichts tun nur auslesen
                          if($sub_text['short_text'] != 'No Entry'){
                            $short_text = 'value ="'.$sub_text['short_text'].'"';
                          }else{
                            $short_text = 'placeholder="No Entry"';
                          }
                          if($sub_text['long_text'] != 'Noch kein Eintrag'){
                            $long_text = 'value ="'.$sub_text['long_text'].'"';
                          }else{
                            $long_text = 'placeholder="Noch kein Eintrag"';
                          }                          
                        }
                      }
                      $result_check_sub->free();
                      
                      echo '
                          <li class="collection-item">
                            <form class="col s12">
                              <div class="row">
                                <div class="input-field col l3 m3 s12">'.$sub_service['name'].'</div>
                                  <div class="input-field col l2 m3 s12">
                                    <i class="material-icons prefix">mode_edit</i>
                                    <input '.$short_text.' id="short_text_'.$sub_service['id'].'" type="text" class="validate" maxlength="15">
                                    <label for="short_text_'.$sub_service['id'].'">short</label>
                                  </div>
                                <div class="input-field col l7 m6 s12">
                                  <i class="material-icons prefix">mode_edit</i>
                                  <input '.$long_text.' id="long_text_'.$sub_service['id'].'" type="text" class="validate" maxlength="50">
                                  <label for="long_text_'.$sub_service['id'].'">long</label>
                                  <button class="subbtn btn waves-effect waves-light" type="submit" id="'.$sub_service['id'].'" <!-- style="display: none;" --> >
                                    Submit<i class="material-icons right">send</i>
                                  </button>
                                </div>
                              </div>
                            </form>
                          </li>    
                      ';      
                    }
                    echo '</ul>';
                  }
                  $result_sub->free();
          

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