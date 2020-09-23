# buderus_km200
PHP interface for Buderus KM200 with Homematic IP

Beschreibung: 
- config/config.php Datei bearbeiten für die eigenen Parameter (siehe XXX Platzhalter)
- install.php aufrufen für die Erstinstallation
- writetoCCU.php Datei bearbeiten für die eigenen Parameter (siehe XXX Platzhalter)
- Cron Job erstellen, um regelmässig (z.B. 1 mal pro Stunde) die beiden Skripte "ilent_update_DB.php" und "writetoCCU.php" aufzurufen, um die Werte in die eine SQL-Datenbank und in die HomematicIP CCU zu schreiben.
