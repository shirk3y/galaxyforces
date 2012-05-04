<?php

$Lang['ErrorInstall1']='Wygląda na to, że wszystko jest już zainstalowane/zaktualizowane, więc ze względów bezpieczeństwa dostęp do tego pliku został zablokowany!<br />Zauważ, że ten plik (<b>install.php</b>) powinien zostać usunięty po poprawnej instalacji!';
$Lang['Install1']='Instalacja Galaxy Forces';
$Lang['Warning']='Ostrzeżenie';

$Lang['InstallationFinished']='Installation finished. You may want to visit your site now. If any errors occured you will need to correct them or start installation process again.<p />Remember that you will need to protect your configuration file and delete this script (<b>install.php</b>)!';
$Lang['WelcomePage']='Idź do strony początkowej';
$Lang['Error']='Błąd';
$Lang['Error1']='Nieznany typ bazy danych!';
$Lang['Error2']="Nie można nawiązać połączenia z bazą danych. Sprawdź ustawienia!";
$Lang['Error3a']="Couldn't create following table(s): ";
$Lang['Error3b']=". Remember that this setup don't remove any of existing tables. Maybe you need to drop some or change table prefix!";
$Lang['Error4']='Błąd tworzenia użytkownika!';
$Lang['Error5']='Configuration file could not be saved. You may wish to save it yourself. Here is the content of the file <b>include/config.php</b>:';
$Lang['Error6']='Błędne zapytania SQL: ';
$Lang['WarningInstall1']='Plik konfiguracyjny <b>include/config.php</b> nie istnieje lub nie jest zapisywalny! W takim wypatku musisz zapisać wygenerowane dane konfiguracji i zapisać je ręcznie na serwerze. Jeśli masz dostęp do powłoki, możesz utworzyć ten plik zmieniając jego uprawnienia tymi poleceniami: <p /><code>touch include/config.php && chmod 666 include/config.php</code>';
$Lang['WarningInstall2']="Log directory <b>log/</b> is not writeable! You need this or you will not be able to update in future. You can do this by the following command: <p><code>chmod 777 log</code></p>Or if you don't want to set global permission to whole directory you should create and set the permissions for three files: <b>log/common.log</b> (write), <b>log/chat.log</b> (write), <b>log/VERSION.txt</b> (read/write) what you can do by typing:<p /><code>touch log/common.log && chmod 622 log/common.log<br />touch log/chat.log && chmod 666 log/chat.log<br />touch log/VERSION.txt && chmod 666 log/VERSION.txt</code>";
$Lang['Install']='Instalacja';
$Lang['Update']='Aktualizacja';
$Lang['InstallMode']='Tryb instalacji';
$Lang['DatabaseType']='Rodzaj bazy danych';
$Lang['DatabaseHost']='Serwer';
$Lang['DatabaseUser']='Użytkownik bazy danych';
$Lang['DatabasePass']='Hasło';
$Lang['DatabaseName']='Nazwa bazy danych';
$Lang['DatabasePrefix']='Table prefix (must *NOT* contain spaces)';
$Lang['DatabaseCreate']='Utwórz bazę danych (użytkownik musi posiadać stosowne uprawnienia)';
$Lang['InitialCreate']='Utwórz użytkownika (zalecane)';
$Lang['CreateWorld']='Utwórz świat';
$Lang['CreateItems']='Utwórz przedmioty';
$Lang['CreateTables']='Utwórz wymagane tabele';
$Lang['DatabaseSettings']='Ustawienia bazy danych';
$Lang['InitialSettings']='Ustawienia początkowe';
$Lang['Install default .htaccess file']='Zainstaluj domyślny plik .htaccess';
