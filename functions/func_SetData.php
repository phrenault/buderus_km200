<?php
require_once './config/config.php';
require_once './include/crypt_key.php';

function km200_SetData( $REST_URL, $Value ){
  $content = json_encode(
    array("value" => $Value)
  );
  $options = array('http' => array('method' => "PUT",
                                  'header' => "Content-type: application/json\r\n" .
                                  "User-Agent: TeleHeater/2.2.3\r\n",
                                  'content' => km200_Encrypt( $content )
                                  )
                  );
  
  $context = stream_context_create( $options );
  @file_get_contents('http://' . HOST . $REST_URL,false,$context);
}

function km200_Encrypt( $encryptData )
{
	// add PKCS #7 padding
	$blocksize = mcrypt_get_block_size( MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB	);
	$encrypt_padchar = $blocksize - ( strlen( $encryptData ) % $blocksize );
	$encryptData .= str_repeat( chr( $encrypt_padchar ), $encrypt_padchar );
	// encrypt
	return base64_encode(
		mcrypt_encrypt(
			MCRYPT_RIJNDAEL_128, CRYPT_KEY, $encryptData, MCRYPT_MODE_ECB, ''
		)
	);
} 
?>