<?php
//require_once './config/config.php';
//require_once './include/crypt_key.php';

require_once '/volume1/web/buderus_km200/config/config.php';
require_once '/volume1/web/buderus_km200/include/crypt_key.php';
        
//function km200_GetData( $REST_URL , $db_entry = '1'){
function km200_GetData( $REST_URL , $db_entry){
  $options = array('http' => array('method' => "GET",
                                  'header' => "Accept: application/json\r\n" ."User-Agent: TeleHeater/2.2.3\r\n"
                                  ));
  $context = stream_context_create( $options );
  //echo (( km200_Decrypt(file_get_contents('http://' . HOST . $REST_URL, false, $context)) ));
  $return_json = json_decode(
    km200_Decrypt(file_get_contents('http://' . HOST . $REST_URL, false, $context)),
    true //Achtung! Hier das true (und drüber das Komma) macht aus dem decodierten Objekt ein Array zur weiteren Bearbeitung)
    );
        if($db_entry == '1'){
          // in Db eintragen für evtl. Statisiken
          if($return_json != NULL){
          if(array_key_exists('value', $return_json)){
          
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);
            if ($mysqli->connect_error) {die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);}
            if (mysqli_connect_error()) {die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());}
            // hole vorhergehenden Eintrag aus DB
            $get_query = "SELECT * FROM `saved_values` WHERE `buderus_id` = '".$return_json['id']."' ORDER by ID DESC LIMIT 1";
            if ($result = $mysqli->query($get_query)) {
              $old_entry = $result->fetch_assoc();
              // noch nichts oder der Wert hat sich geändert , sonst nichts eintragen    
              if(($old_entry['value'] == NULL OR $return_json['value'] != $old_entry['value']) AND $return_json['id'] != '/gateway/DateTime' ){
                $mysqli->query("
                INSERT INTO  `saved_values` (
                                              `id` ,
                                              `timestamp` ,
                                              `buderus_id` ,
                                              `value`
                                            )
                                            VALUES (
                                              NULL , 
                                              CURRENT_TIMESTAMP ,
                                              '".$return_json['id']."',
                                              '".$return_json['value']."'
                                            )
                ");
              }
              $result->free();
            }         
            $mysqli->close();
          }
          } // end DB eintragen        
        }
  return  $return_json;
}

function km200_Decrypt( $decryptData )
{
	//$decrypt = (mcrypt_decrypt( MCRYPT_RIJNDAEL_128, CRYPT_KEY, base64_decode($decryptData), MCRYPT_MODE_ECB, '' ) );
    $decrypt = (openssl_decrypt( base64_decode($decryptData) , 'aes-256-ecb' , CRYPT_KEY, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, '') ); 
    // remove zero padding
	$decrypt = rtrim( $decrypt, "\x00" );
	// remove PKCS #7 padding
	$decrypt_len = strlen( $decrypt );
	$decrypt_padchar = ord( $decrypt[ $decrypt_len - 1 ] );
	for ( $i = 0; $i < $decrypt_padchar ; $i++ )
	{
		if ( $decrypt_padchar != ord( $decrypt[$decrypt_len - $i - 1] ) )
		break;
	}
	if ( $i != $decrypt_padchar )
		return $decrypt;
	else
		return substr(
			$decrypt,
			0,
			$decrypt_len - $decrypt_padchar
		);
}
?>