<?php
/*
Text datei:
service;short_text;long_text
/system;sys kurz;sys lang
/system/appliance/CHpumpModulation;sys 3 kurz;sys 3 lang

AUSGABE:
array_0 = 1 ergibt dies:
Array
(
    [/system] => Array
        (
            [service] => /system
            [short_text] => sys kurz
            [long_text] => sys lang
        )

    [/system/appliance/CHpumpModulation] => Array
        (
            [service] => /system/appliance/CHpumpModulation
            [short_text] => sys 3 kurz
            [long_text] => sys 3 lang
        )

)
array_0 = ''  ergibt dies:
Array
(
    [0] => Array
        (
            [service] => /system
            [short_text] => sys kurz
            [long_text] => sys lang
        )

    [1] => Array
        (
            [service] => /system/appliance/CHpumpModulation
            [short_text] => sys 3 kurz
            [long_text] => sys 3 lang
        )

)
*/


function txt_array($filename='', $delimiter=';',$array_0 = 1){
  if(!file_exists($filename) || !is_readable($filename)){
    return FALSE;
  }
  $header = NULL;
  $data = array();
  if (($handle = fopen($filename, 'r')) !== FALSE){
    while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE){
      if(!$header){
        $header = array();
        foreach ($row as $val){
          $header_raw[] = $val;
          $hcounts = array_count_values($header_raw);
          $header[] = $hcounts[$val]>1?$val.$hcounts[$val]:$val;
        }
      }else{
        if($array_0 == ''){
          $data[$row[0]] = array_combine($header, $row);        
        }else{
          $data[] = array_combine($header, $row);        
        }

      }
    }
  fclose($handle);
  }
    return $data;
}
?>