<?php
error_reporting(0);

define('CONFIGFILE','config/config.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>Installation Script</title>
</head>
<?php
$step = (isset($_GET['step']) && $_GET['step'] != '') ? $_GET['step'] : '';
switch($step){
  case '1':
  requirements_step_1();
  break;
  case '2':
  form_config_step_2();
  break;
  default:
  requirements_step_1();
}
?>
<body>
<?php
/*##############################################################################
                    _______.___________. _______ .______    __  
                   /       |           ||   ____||   _  \  /_ | 
                  |   (----`---|  |----`|  |__   |  |_)  |  | | 
                   \   \       |  |     |   __|  |   ___/   | | 
               .----)   |      |  |     |  |____ |  |       | | 
               |_______/       |__|     |_______|| _|       |_| 
##############################################################################*/                                                                
function requirements_step_1(){
  
  if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] =='weiter zu step 2'){
    //Formular step_1 prüfen
    //Fehlersuche Start
    $pre_error ='';      
    if (phpversion() < '5.0') {
     $pre_error .= 'Mindestens PHP5 sollte vorhanden sein !<br />';
    }
    if (ini_get('max_execution_time') < '300') {
     $pre_error .= 'php.ini max_execution_time Ist zu niedrig. (die Abfrage s&auml;mtlicher Werte an KMxxx dauert sehr lange)<br />';
    }
    if (!extension_loaded('mysqli')) {
     $pre_error .= 'MySQLi DB Erweiterung ben&ouml;tigt !<br />';
    }
    // ob gd wirklich gebraucht wird bin ich nicht sicher
    if (!extension_loaded('gd')) {
     $pre_error .= 'GD Erweiterung ben&ouml;tigt !<br />';
    }
    if (!is_writable(CONFIGFILE)) {
     $pre_error .= CONFIGFILE.' ben&ouml;tigt Schreibrechte !';
    }    
    //Fehlersuche Ende
    
    if($pre_error != ''){
      // nichts tun auf dieser Seite bleiben
      echo '<div align="center" style="color:red;">'.$pre_error.'</div>';      
    }else{
      header('Location: install.php?step=2');
      exit;   
    }   
  }
  
  $ok = ' bgcolor="green">Ok';
  $nok = ' bgcolor="red">Not Ok';
  
  $phpverion_color = (phpversion() >= '5.0') ? $ok : $nok;
  $max_time_color =  (ini_get('max_execution_time') >= '300') ? $ok : $nok;
  $mysqli_loaded =  extension_loaded('mysqli') ? 'On' : 'Off';
  $mysqli_color = extension_loaded('mysqli') ? $ok : $nok;
  $gd_loaded = extension_loaded('gd') ? 'On' : 'Off';
  $gd_color = extension_loaded('gd') ? $ok : $nok; 
  $file_writable = is_writable(CONFIGFILE) ? 'Writable' : 'Unwritable';
  $file_color =  is_writable(CONFIGFILE) ? $ok : $nok;
   
  echo '
  <div align="center">
  <table>
  <tr>
    <td></td>
    <td>Istwert</td>
    <td>benötigt</td>
    <td>Auswertung</td>
  </tr>
  <tr>
   <td>PHP Version:</td>
   <td>'.phpversion().'
   </td>
   <td>5.0+</td>
   <td'.$phpverion_color.'</td>
  </tr>
  <tr>
   <td>php ini <u>max_execution_time</u>:</td>
   <td>'.ini_get('max_execution_time').'</td>
   <td>>=300</td>
   <td'.$max_time_color.'</td>
  </tr>
  <tr>
   <td>MySQL:</td>
   <td>'.$mysqli_loaded.'</td>
   <td>On</td>
   <td'.$mysqli_color.'</td>
  </tr>
  <tr>
   <td>GD:</td>
   <td>'.$gd_loaded.'</td>
   <td>On</td>
   <td'.$gd_color.'</td>
  </tr>
  <tr>
   <td>'.CONFIGFILE.'</td>
   <td>'.$file_writable.'</td>
   <td>Writable</td>
   <td'.$file_color.'</td>
  </tr>
  </table>
  <form action="install.php?step=1" method="post">
   <input type="submit" name="submit" value="weiter zu step 2" />
  </form>
  </div>
  ';
}
/*##############################################################################
                   _______.___________. _______ .______    ___   
                  /       |           ||   ____||   _  \  |__ \  
                 |   (----`---|  |----`|  |__   |  |_)  |    ) | 
                  \   \       |  |     |   __|  |   ___/    / /  
              .----)   |      |  |     |  |____ |  |       / /_  
              |_______/       |__|     |_______|| _|      |____| 
##############################################################################*/
function form_config_step_2(){

  // DB connection
  $database_host=isset($_POST['database_host'])?$_POST['database_host']:"localhost";
  $database_name=isset($_POST['database_name'])?$_POST['database_name']:"";
  $database_username=isset($_POST['database_username'])?$_POST['database_username']:"";
  $database_password=isset($_POST['database_password'])?$_POST['database_password']:"";
  //buderus connection
  $bud_host=isset($_POST['HOST'])?$_POST['HOST']:"";
  $bud_gateway_pw=isset($_POST['GATEWAY_PW'])?$_POST['GATEWAY_PW']:"";
  $bud_priv_pw=isset($_POST['PRIV_PW'])?$_POST['PRIV_PW']:"";
  
  if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] =='weiter zu step 3'){
    //Formular step_2 prüfen
    //Fehlersuche Start
    $pre_error ='';
    
    // ein Feld leer   
    if ( empty($database_host) || empty($database_name) || empty($database_username) || empty($database_password) || empty($bud_host) || empty($bud_gateway_pw) || empty($bud_priv_pw) )
      $pre_error .='Bitte alle Felder ausf&uuml;llen. <br />';    

    if($pre_error == ''){
      // DB Verbindung testen
      $mysqli = new mysqli($database_host, $database_username, $database_password, $database_name);
      
      if ($mysqli->connect_error)
        $pre_error .= 'DB Verbindungsfehler <br />';
      if (mysqli_connect_error())
        $pre_error .= 'DB Verbindungsfehler <br />';    
    }

    if($pre_error == ''){
    // KM verbindung testen
      $km_connection = fsockopen($bud_host, '80', $errno, $errstr, 6);
      if ( ! $km_connection ){
        $pre_error .= 'KMxxx Verbindungsfehler (IP/Name richtig?) <br />';        
      }else{
      //Verbindung zu IP ist möglich schauen ob es auch eine Buderus ist welche antwortet
      require_once './functions/func_GetData.php';
      $test_km_connection = km200_GetData('/system',0);
      if ( $test_km_connection != '1'){
        $pre_error .= 'KM Verbindungsfehler '.print_r($test_km_connection).'<br />';
      }
      }
    }

    //Fehlersuche Ende
    
    if($pre_error != ''){
      // nichts tun auf dieser Seite bleiben
      echo '<div align="center" style="color:red;">'.$pre_error.'</div>';      
    }else{
      echo '<div align="center";">SQL DB und KM200 Verbingung erfoglreich.</div>';
      //config.php schreiben

      $f=fopen(CONFIGFILE,"w");
      $config_inf="<?php
//configuration
// IP Adresse oder DNS-Hostname des KM200
define( 'HOST', '".$bud_host."');
// Gerätepasswort. Achtung: Ohne Bindestriche
define( 'GATEWAY_PW', '".$bud_gateway_pw."');
// Eigenes Passwort wie in der App vergeben
define( 'PRIV_PW', '".$bud_priv_pw."');

//DB Connection
define( 'DB_HOST', '".$database_host."');// DB Host
define( 'DB_USER', '".$database_username."');// DB USER
define( 'DB_PW', '".$database_password."');// DB Password
define( 'DB_NAME', '".$database_name."');// Db Name  
?>";

// ;-) Start nächsten 2 Zeilen nur für korrektes Syntaxhighlighting
?>
<?php
// ;-) Ende nächsten 2 Zeilen nur für korrektes Syntaxhighlighting

      if (fwrite($f,$config_inf)>0){
        fclose($f);
      }
      echo '<div align="center" style="color:green;">';
      echo '1. Datei: '.CONFIGFILE.'erfolgreich erstellt<br />';      
// MAIK DB erstellen und subservices auslesen und eintragen

      echo '2. Tabellen und Mainservices in DB: '.$database_name.' erstellen.<br />';      
      // Tabellen erstellen und Mainservices eintragen
      // import
      $file = dirname(__FILE__).'/install.sql'; // sql data file
      $args = file_get_contents($file); // get contents
      print_r( mysqli_import_sql( $args, $database_host,  $database_username, $database_password, $database_name) ); // execute

      echo '4. Bitte warten, lese Subservices aus<br />';
      include './include/create_servicelist.php';
      echo '5. Subservices in DB erfogreich eingetragen.<br />';

      echo "<hr><h1>Fertig. Jetzt bitte die install.php entfernen/umbenennen</h1><hr><hr>";

      echo '</div>';
      sleep(10);                         
      header('Location: set_mainservices.php');                               
      exit;    
    }
  }

  echo '
        <div align="center">
        <form method="post" action="install.php?step=2">  
        <table>
          <tr>
            <th colspan="2" align="center">Datenbank Verbindung:</th>
          </tr>
          <tr>
            <td>Datenbank Host</td>
            <td><input type="text" name="database_host" value="'.$database_host.'"" size="30"></td>      
          </tr>
          <tr>
            <td>Datenbank Name</td>
            <td><input type="text" name="database_name" size="30" value="'.$database_name.'"></td>      
          </tr>
          <tr>
            <td>Datenbank Username</td>
            <td><input type="text" name="database_username" size="30" value="'.$database_username.'"></td>      
          </tr>
          <tr>
            <td>Datenbank Passwort</td>
            <td><input type="text" name="database_password" size="30" value="'.$database_password.'"></td>      
          </tr>
          <tr>
            <th colspan="2" align="center">KMxxx Daten:</th>
          </tr>    
          <tr>
            <td>IP Adresse oder DNS-Hostname des KM200</td>
            <td><input type="text" name="HOST" size="30" value="'.$bud_host.'"></td>      
          </tr>
          <tr>
            <td>Gerätepasswort. Achtung: Ohne Bindestrich</td>
            <td><input type="text" name="GATEWAY_PW"  size="30" value="'.$bud_gateway_pw.'"></td>      
          </tr>
          <tr>
            <td>Eigenes Passwort wie in der App vergebe</td>
            <td><input type="text" name="PRIV_PW"  size="30" value="'.$bud_priv_pw.'"></td>      
          </tr>
          <tr>
            <td></td>
            <td><input type="submit" name="submit" value="weiter zu step 3"></td>      
          </tr>
        </table>
        </form>
        </div>
  ';
}
/*##############################################################################
                           _______.  ______      __      
                          /       | /  __  \    |  |     
                         |   (----`|  |  |  |   |  |     
                          \   \    |  |  |  |   |  |     
                      .----)   |   |  `--'  '--.|  `----.
                      |_______/     \_____\_____\_______|
##############################################################################*/
function mysqli_import_sql( $args , $dbhost, $dbuser, $dbpass ,$dbname ) {

/* how to use:
// import
$file = dirname(__FILE__).'/data.sql'; // sql data file
$args = file_get_contents($file); // get contents
print_r( mysqli_import_sql( $args, 'localhost',  'user', 'password', 'databasename') ); // execute
*/

  // check mysqli extension installed
  if( ! function_exists('mysqli_connect') ) {
    die(' This scripts need mysql extension to be running properly ! please resolve!!');
  }
	$mysqli = @new mysqli( $dbhost, $dbuser, $dbpass, $dbname );
	if( $mysqli->connect_error ) {
    		print_r( $mysqli->connect_error );
    		return false;
  	}
    $querycount = 11;
    $queryerrors = '';
    $lines = (array) $args;
    if( is_string( $args ) ) {
      $lines =  array( $args ) ;
    }
    if ( ! $lines ) {
      return '' . 'cannot execute ' . $args;
    }
    $scriptfile = false;
    foreach ($lines as $line) {
      $line = trim( $line );
      // if have -- comments add enters
      if (substr( $line, 0, 2 ) == '--') {
          $line = "\n" . $line;
      }
      if (substr( $line, 0, 2 ) != '--') {
        $scriptfile .= ' ' . $line;
        continue;
      }
    }
    $queries = explode( ';', $scriptfile );
    foreach ($queries as $query) {
      $query = trim( $query );
      ++$querycount;
      if ( $query == '' ) {
        continue;
      }
      if ( ! $mysqli->query( $query ) ) {
        $queryerrors .= '' . 'Line ' . $querycount . ' - ' . $mysqli->error . '<br>';
        continue;
      }
    }
    if ( $queryerrors ) {
      return '' . 'There was an error on File: ' . $filename . '<br>' . $queryerrors;
    }
    
    if( $mysqli && ! $mysqli->error ) {
      @$mysqli->close();
    }   
    return '3. Tabellen und Mainservices in DB: '.$database_name.' erfogreich erstellt.<br />';
}


?>