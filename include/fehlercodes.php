<script  type="text/javascript">
$(document).ready(function() {
  $('select').material_select();
  
  $("#select_stoer_code").change(function(){
    var stoer_code=$(this).children('option:selected').val();
    $("#select_zusatz_code").load("ajax_update_fehlercodes.php",{
      'what': 'stoer_code_changed',
      'stoer_code': stoer_code
    });
  });
  
  $("#select_zusatz_code").change(function(){
    var stoer_code=$("#select_stoer_code").children('option:selected').val();
    var zusatz_code=$(this).children('option:selected').val();
    $("#result").load("ajax_update_fehlercodes.php",{
      'what': 'zusatz_code_changed',
      'stoer_code': stoer_code,
      'zusatz_code': zusatz_code
    });
  });   
});
</script>

<?php
  require_once './config/config.php';
  
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);
  if ($mysqli->connect_error) {die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);}
  if (mysqli_connect_error()) {die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());}

  if(mysqli_query($mysqli,"DESCRIBE  fehler_codes ")){
    //Tabellen existieren bereits nichts tun  
  }else{
    require_once './functions/func_mysqli_import_sql.php';
  // Tabellen für Fehler erstellen
    // import
    $file = './fehlercodes.sql'; // sql data file
    $args = file_get_contents($file); // get contents
    mysqli_import_sql($args, DB_HOST,  DB_USER, DB_PW, DB_NAME); // execute
  // Tabelle erstellt      
  }
  
  $stoer_code_options = '';
  $get_stoer_codes_query = "SELECT * FROM `fehler_codes` GROUP by stoer_code ORDER by stoer_code ASC";              
    if ($result_stoer_code = $mysqli->query($get_stoer_codes_query)) {
      while ($stoer_code = $result_stoer_code->fetch_assoc()) {
        $stoer_code_options .= '<option value="'.$stoer_code['stoer_code'].'">'.$stoer_code['stoer_code'].'</option>';
      }
    }
    $result_stoer_code->free();


  $mysqli->close();
?> 
<div class="row">
  <div class="col l6 m3 s12"></div>
  
  <div class="input-field col l2 m3 s12">
    <select id="select_stoer_code">
      <option value="" disabled selected>bitte w&auml;hlen</option>
      <?php echo $stoer_code_options; ?>
    </select>
    <label>St&ouml;rcode:</label>
  </div>
  
  <div class="input-field col l2 m3 s12">
    <select id="select_zusatz_code">
      <option value="" disabled selected>erst St&ouml;rcode w&auml;hlen</option>
    </select>
    <label>Zusatzcode:</label>
  </div>
  
  <div class="input-field col l2 m3 s12"></div>
</div>

<div class="row">
  <div class="col s12" id="result"></div>
</div>