# buderus_km200
PHP interface for Buderus Logomatic web KM200 with Homematic IP

Code basiert auf Entwicklung von kunigunde (https://homematic-forum.de/forum/viewtopic.php?f=19&t=28320&sid=022e9c231efd989bcbe29ba8f999c8f8#p403768), allerdings ohne Dashboard.

Beschreibung/Verwendung:
- lokale SQL Datenbank erstellen
- Dateien auf den lokalen Webserver laden
- SERVERADRESSE/PFAD/index.php aufrufen
Damit startet automatisch das Install-Skript.
- ggf. config/config.php Datei bearbeiten für die eigenen Parameter (siehe XXX Platzhalter)
- writetoCCU.php Datei bearbeiten für die eigenen Parameter (siehe XXX Platzhalter)
- Cron Job erstellen, um regelmässig (z.B. 1 mal pro Stunde) die beiden Skripte "ilent_update_DB.php" und "writetoCCU.php" aufzurufen, um die Werte in die eine SQL-Datenbank und in die HomematicIP CCU zu schreiben.

Ich benutze als Umgebung eine Synology NAS mit WebStation und PHP 7.3. Für HomematicIP die CCU3.
