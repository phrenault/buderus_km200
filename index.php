<?php
// Compatible with PHP 7.3 - replaced mcrypt with openssl
// Corrected several minor bugs in include and required_once calls
// Introduced page titles in corresponding files

require 'config/define_constants.php';

//require_once 'functions/func_SetData.php';
//km200_SetData( '/dhwCircuits/dhw1/temperatureLevels/low', 45 );

if (file_exists(INSTALL_FILE)) {// Installation durchführen
  header('Location: install.php');
} else {// Normale Startseite anzeigen
  include 'start_page.php';
}
?>