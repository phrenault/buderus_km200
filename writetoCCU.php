<?php
//#######################################################
//### Author: Philippe Renault, Date: 05.02.2020        #
//###													#
//### ToDo: -											#
//#######################################################

// Diese Sktipt muss über einen Cron Job regelmässig (z.B. 1 mal pro Stunde) aufgerufen werden, um die Werte in die CCU zu schreiben.

require_once 'functions/func_GetData.php';

// Homematic CCU Parameter
$CCU_IP = "192.168.178.XXX";
$username = "XXX";
$password = "XXX";
$typ = 1;   // 1 = Textvariable, 2 = Werteliste (Format = wert1;wer2;wert3  -> kein ; am Ende!) 

// Buderus KM200 Parameter die in CCU Systemvariable geschrieben werden sollen
// Variablennamen aus der ersten Spalte sind in der CCU entsprechend anzulegen. Zweite Spalte nicht verändern!
$ccu_array = array(
	'bud_aussen' => '/system/sensors/temperatures/outdoor_t1', 	//Aussentemperatur
	'bud_raum_ist' => '/heatingCircuits/hc1/roomtemperature', 	//Raum-IST Temp. (HK1)
	'bud_raum_soll' => '/heatingCircuits/hc1/currentRoomSetpoint',	//Raum-SOLL Temp. (HK1)	
	'bud_vorlauf' => '/heatSources/actualSupplyTemperature',     //Vorlauf Temp
///'bud_rueck' => '/system/sensors/temperatures/return',		//Rücklauftemperatur - Bei "Nur-Brenner" Anlagen meist nicht vorhanden
	'bud_wasser_ist' => '/dhwCircuits/dhw1/actualTemp',			//Warmwasser-IST Temp. (WW1)	
	'bud_wasser_soll' => '/dhwCircuits/dhw1/currentSetpoint',	//Warmwasser-SOLL Temp. (WW1)
	'bud_druck' => '/system/appliance/systemPressure',			//Druck
	'bud_servicemsg' => '/notifications',						//Servicenachrichten
	'bud_status' => '/system/healthStatus'						//Status als String: "Ok"
	);
//  'Flame Status' => '/heatSources/hs1/flameStatus',
//  'R&uuml;cklauf Temp' =>  '/heatSources/returnTemperature',
//  'Raum-Soll Temp' => '/heatingCircuits/hc1/manualRoomSetpoint',
//  'Modus' => '/heatingCircuits/hc1/operationMode',  // [0] => night, [1] => day, [2] => auto
//  'Modus' => '/dhwCircuits/dhw1/operationMode',

// Start Skript
foreach ($ccu_array as  $key => $val) {
	$sysvar = $key;
	$json = km200_GetData( $val ,0 );
	if(isset($json['value'])){
    	$status = $json['value'];
	}

// Unterscheidung ob einzelne Variable oder Werteliste (hier nur typ=1 verwendet)
// $sysvar = ...; //Name der CCU-Systemvariablen
// $status = $_POST['daten']; //Wert der Variablen
	if ($typ == 1)
	{
		//$HM_Script = "dom.GetObject('".$sysvar."').State(".$status.")";
		$HM_Script = "http://$CCU_IP:8181/rega.exe?state=dom.GetObject('".$sysvar."').State('".$status."')";
	}
	elseif ($typ == 2)
	{
		//$HM_Script = "dom.GetObject('".$sysvar."').ValueList(".$status.")";
		$HM_Script = "http://$CCU_IP:8181/rega.exe?state=dom.GetObject('".$sysvar."').ValueList('".$status."')"; 
	}
	else
	{
		echo "Error - Specify type";
	}
	//print_r($HM_Script);
	
	// Funktionsaufruf zum Schreiben der CCU Systemvariablen
	echo HMRS_HTTP_Post($HM_Script,$username,$password);
}
unset($val);
//Ende Skript


function HMRS_HTTP_Post($HM_Script,$username,$password)
{
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_PORT => "8181",
  CURLOPT_URL => $HM_Script,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_USERPWD => "$username:$password",
  CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: b93e73c7-baa1-3312-0d29-7400f3431b3b"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  //echo $response;
}

}

?>