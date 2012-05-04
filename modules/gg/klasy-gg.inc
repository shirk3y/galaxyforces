<?php 
#  BRAMKA WWW2GG V2.9 (2009r) W CALOSCI W PHP A WIEC MULIPLATFORMOWA
#  MOZLIWA DO ZAMIESZCZENIA NA SERWERACH BEZ OBSLUGI CGI
# 
#  (C) Copyright 2001-2009 Piotr Mach <pm@ggadu.com>
#  Nowych wersji szukaj na http://gg.wha.la
#
#  Bramka powstala dziêki opisowi protoko³u gadu-gadu
#  z projektu EKG http://dev.null.pl/ekg/
#
# nazwy pakietów pochodz± w wiêkszosci z EKG

define("GG_WELCOME",                0x0001);
define("GG_LOGIN",                  0x000c);
define("GG_LOGIN60",                0x0015);
define("GG_LOGIN_OK",               0x0003);
define("GG_LOGIN_FAILED",           0x0009);
define("GG_NEW_STATUS",             0x0002);
define("GG_STATUS",                 0x0002);
define("GG_STATUS_NOT_AVAIL",       0x0001);
define("GG_STATUS_NOT_AVAIL_DESCR", 0x0015);
define("GG_STATUS_AVAIL",           0x0002);
define("GG_STATUS_AVAIL_DESCR",     0x0004);
define("GG_STATUS_BUSY",            0x0003);
define("GG_STATUS_BUSY_DESCR",      0x0005);
define("GG_STATUS_INVISIBLE",       0x0014);
define("GG_NOTIFY",                 0x0010);
define("GG_NOTIFY_REPLY",           0x000c);
define("GG_NOTIFY_REPLY60",         0x0011);
define("GG_LIST_EMPTY",             0x0012);
define("GG_USER_NORMAL",            0x0003);
define("GG_USER_BLOCKED",           0x0004);
define("GG_SEND_MSG",               0x000b);
define("GG_CLASS_MSG",              0x0004);
define("GG_CLASS_CHAT",             0x0008);
define("GG_CLASS_ACK",              0x0020);
define("GG_SEND_MSG_ACK",           0x0005);
define("GG_ACK_DELIVERED",          0x0002);
define("GG_ACK_QUEUED",             0x0003);
define("GG_RECV_MSG",               0x000a);
define("GG_LOGIN_FAILED2",          0x000B);
define("GG_ACK_MBOXFULL",           0x0004);
define("DISCONNECTED",              0x0100);
define("GG_PUBDIR50_REQUEST",       0x0014);
define("GG_PUBDIR50_REPLY",         0x000e);
define("GG_PUBDIR50_SEARCH",        0x0003);


/* Zawiera funkcje nizszego poziomu ,z tej klasy dziedziczy klasa www2gg */
class GaduGadu
{
  var $fp;                              // polaczenie w sesji gadu-gadu
  var $wiadomosci = array();            // tablica z odebranymi wiadomosciami
  var $status_kontaktu = array();       // odebrane dane adresata wiadomosci
  var $status_dostarczenia = array();   // tablica ze stanami dostarczenia wiadomosci
  var $wyniki_szukania = array();       // wyniki wyszukiwania w katalogu
  var $debug = FALSE;                   // tryb debug
    
  function GaduGadu() // konstruktor 
  {
    mt_srand((double)microtime() * 1000000);
  }
  
  // zwraca stan dostarczenia wiadomosci o danym seq
  function status_dostarczenia ($seq)
  {
    if ($this->status_dostarczenia[$seq])
      return $this->status_dostarczenia[$seq];
    else
      return 0;
  }
  
  // wersje gadu gadu > 0x14 (4.8.9, 4.9.2, 5.x itp.) 
  function oblicz_nowy_hash ($haslo, $klucz) {  //takie efekty bitwy z php i jego signed int ;-)
  
    $x0=0; $x1=0; $y0=0; $y1=0; $z=0; $tmp=0;
    $y0 = ($klucz << 16) >> 16; $y1 = $klucz >> 16 ;

    for ($i=0; $i<strlen($haslo); $i++) {
      $x0 = ($x0 & 0xFF00) | ord($haslo[$i]); $x1 &= 0xFFFF;
      $y0 ^= $x0; $y1 ^= $x1;
      $y0 += $x0; $y1 += $x1;
      $x1 <<= 8; $x1 |= ($x0 >> 8); $x0 <<= 8;
      $y0 ^= $x0; $y1 ^= $x1;
      $x1 <<= 8; $x1 |= ($x0 >> 8); $x0 <<= 8;
      $y0 -= $x0; $y1 -= $x1;
      $x1 <<= 8; $x1 |= ($x0 >> 8); $x0 <<= 8;
      $y0 ^= $x0; $y1 ^= $x1;
      $z = $y0 & 0x1F;

      $y0 &= 0xFFFF; $y1 &= 0xFFFF;
      if ($z <= 16) {
        $tmp= ($y1 << $z) | ($y0 >> (16-$z));
        $y0 = ($y1 >> (16-$z)) | ($y0 << $z);
        $y1 = $tmp;    
      } else {
        $tmp= $y0 << ($z-16);
        $y0 = ($y0 >> (32-$z)) | ( (($y1 << $z) >> $z) << ($z-16) );
        $y1 = ($y1 >> (32-$z)) | $tmp;
      }
      $y0 &= 0xFFFF; $y1 &= 0xFFFF;
    }
    $hash = hexdec(sprintf("%04x%04x", $y1, $y0));
    settype($hash, 'integer');

  return $hash;
  }

  function znajdz_serwer($numer)
  {
    $http_fp = @fsockopen("appmsg.gadu-gadu.pl", 80, $errno, $errstr, 3); //timeout=3s
    if (!$http_fp) {
      $this->Debug
             ("BRAK POLACZENA Z APPMSG.GADU-GADU.PL, MOZE BYC PRZECIAZONY: $errno - $errstr<BR>\n");
    } else {
      $get = "GET /appsvc/appmsg.asp?fmnumber=<$numer> HTTP/1.0\r\n";
      $get.= "Host: appmsg.gadu-gadu.pl\r\n";
      $get.= "User-Agent: Mozilla/4.7 [en] (Win98; I)\r\n";
      $get.= "Pragma: no-cache\r\n\r\n";
      fputs($http_fp, $get);
	  for ($i=0,$res='';$i++<4;)
        $res.= fgets($http_fp, 128);          
      fclose($http_fp);
      $this->Debug("Odpowied¼ z appmsg: ".$res);
      if (preg_match("/\s([\d\.]{8,16})\:([\d]{1,5})\s/", $res, $adr)) {
        $ret = array($adr[1], $adr[2]); // host, port
        $this->Debug("Uzyskano adres hosta z appmsg.gadu-gadu.pl: ".$adr[1].":".$adr[2]);
        return $ret;
      }
    }
    # Losowanie jednego z hostow gadu-gadu w przypadku gdy nie udaje sie uzyskaæ
    # adresu hosta z appmsg.gadu-gadu.pl, ta lista mo¿e siê w przysz³osci zmieniæ
    $ip = array(84, 82, 80, 79, 77, 69, 68, 44, 43, 42, 40, 35, 24, 33, 6, 7, 4, 5); //wystarczy:)
    $host = '91.197.13.'.$ip[rand(0,sizeof($ip)-1)];
    $port = 8074;
    $this->Debug ("Nie udalo sie uzyskac hosta od appmsg, uzyto domyslnego $host:$port");

  return array ($host, $port);
  }

  function login($numer, $haslo, $host, $port, $opis = "", $wersja = 0x22)
  {
    $this->fp = @fsockopen($host, $port, $errno, $errstr, 10); //timeout = 10s
    $this->Debug("Logowanie do $host:$port ...");
    if (!$this->fp) {
      $this->Debug("PROBLEM Z POLACZENIEM: $errno - $errstr<BR>\n");
      return DISCONNECTED;
    }
    if (!$data = fread($this->fp, 12)) {
      $this->Debug("Polaczenie nieoczekiwanie zamkniete<BR>\n");
      return DISCONNECTED;
    }
    $tab = unpack("Vtyp/Vrozmiar/Vklucz", $data);
    $this->Debug("Otrzymano pakiet z kluczem hasla: ", $tab, bin2hex($data));
    // wersja - 0x16-GG 4.9.x, 0x19-5.0.3
    $hash = $this->oblicz_nowy_hash($haslo, $tab['klucz']); //dla wersji gg >= 0x14
    $this->Debug("Obliczono hash hasla ");
    
    $data = pack("VVVVVVvVvVvCa".strlen($opis), GG_LOGIN60, 0x1F + strlen($opis), 
                 $numer, $hash, ($opis)?GG_STATUS_AVAIL_DESCR:GG_STATUS_AVAIL, 
                 $wersja, 0, 0, 0, 0, 0, 0x14, $opis ); //0xbe

    fwrite($this->fp, $data);

    $this->Debug("Wyslano pakiet logowania: ".bin2hex($data));
    # Przy podaniu zlego hasla moze natychmiast zerwac polaczenie
    if (!$data1 = fread($this->fp, 8))
      return GG_LOGIN_FAILED2;

    $tab = unpack("Vlogin_status/Vrozmiar", $data1);
    if ($tab['rozmiar']>0) 
      fread($this->fp, $tab['rozmiar']);
    $this->Debug("Odpowiedz na logowanie ", $tab);
    
  return $tab['login_status'];     //Wynik logowania GG_LOGIN_OK lub GG_LOGIN_FAILED
  }


  function wyslij_liste_kontaktow ($uin=0)
  {
    if ($uin)
      $data = pack ("VVVC",GG_NOTIFY, 5, $uin, GG_USER_NORMAL);// lista kontaków z jednym numerem
    else 
      $data = pack ("VV",GG_LIST_EMPTY, 0);                    // pusta lista
    
    $this->Debug("Wyslano liste kontaktów".bin2hex($data));
  
  return fwrite($this->fp,$data);
  }

  function wyslij_wiadomosc($adresat, $tresc, $potwierdzenie = TRUE)
  {
    $tresc = www2ggtxt::iso2cp($tresc);
    $seq = mt_rand();
    
    $data = pack("VVVVVa".strlen($tresc)."C", GG_SEND_MSG, 0x0d + strlen($tresc), $adresat,
            $seq, ($potwierdzenie)?GG_CLASS_MSG:GG_CLASS_MSG | GG_CLASS_ACK, $tresc, 0);
    $this->Debug("Wyslano pakiet wiadomo¶ci : ".bin2hex($data), $data);
    
    $this->status_dostarczenia[$seq] = FALSE; //zmieni sie przy otrzymaniu potwierdzenia
    
    if (!fwrite($this->fp, $data)) 
      return FALSE;

  return $seq;
  }

  function odbierz_dane ($auto_odpowiedz = "")
  {
    if (!$data = fread($this->fp, 8))
      return FALSE;
    $tab = unpack("Vtyp/Vrozmiar", $data);

    # Odebranie wszystkich oczekujacych wiadomosci
    while ($tab['typ'] == GG_RECV_MSG || $tab['typ'] == GG_NOTIFY_REPLY60 
           || $tab['typ'] == GG_STATUS) {
      $data = fread($this->fp, $tab['rozmiar']);

      switch($tab['typ']) {
        case GG_RECV_MSG:
        $tab = unpack("Vnadawca/Vseq/Vtime/Vclass/A*wiadomosc", $data);
        $this->Debug("Otrzymano wiadomosc ", $tab);
        if ($auto_odpowiedz)
          $this->wyslij_wiadomosc($tab['nadawca'], $auto_odpowiedz, FALSE); //bez potwierdzen
        array_push($this->wiadomosci, $tab);
        break;
        case GG_STATUS:
        case GG_NOTIFY_REPLY60:
        $this->Debug ("Otrzymano odpowiedz ze statusem kontaktu ".bin2hex($data));
        $tab = unpack("Vuin/Vstatus", $data); 
        $tab['uin'] = $tab['uin'] & 0xFFFFFF;
        $this->status_kontaktu[$tab['uin']] = $data;
        $this->Debug ($tab, $this->status_kontaktu);
        break;
      }
      
      $data = fread($this->fp, 8);
      $tab = unpack("Vtyp/Vrozmiar", $data);
    }

    # odebranie ostatnich pakietów
    if (!$data = fread($this->fp, $tab['rozmiar']))
      return FALSE;

    if ($tab['typ']==GG_SEND_MSG_ACK) {
      $tab = unpack("Vstatus/Vadresat/Vseq", $data);
      $this->Debug("Otrzymano potwierdzenie wiadomosci ", $tab);
      $this->status_dostarczenia[$tab['seq']] = $tab['status'];
    } else if ($tab['typ']==GG_PUBDIR50_REPLY) {
      $tab = unpack("Cunknown/Vczas/A*results", $data);
      $this->Debug("Otrzymano wyniki szukania ", $tab);
      $this->wyniki_szukania = $tab['results'];
    }
    
  return TRUE;
  }

  function logoff ($opis = "") 
  {
    $data = pack("VVVa".strlen($opis), GG_NEW_STATUS, 0x04 + strlen($opis), 
                 ($opis)?GG_STATUS_NOT_AVAIL_DESCR:GG_STATUS_NOT_AVAIL, $opis);
    fwrite($this->fp, $data);
    $this->Debug("Wyslano pakiet logoff : ".bin2hex($data));
    fclose($this->fp);
  }

  function Debug()
  {
    if (!$this->debug) 
      return;
    foreach (func_get_args() as $info) {
      if (is_array($info))
        print_r($info);
      else
        echo "<BR>\nDEBUG: $info";
    }
    flush();
  }

} // koniec klasy GaduGadu



Class www2gg extends GaduGadu
{
  var $numer = "";
  var $haslo = "";
  var $OPIS_W_STATUSIE_PO_ZALOGOWANIU = "";
  var $OPIS_W_STATUSIE_PO_WYLOGOWANIU = "";
  var $ilosc_prob = 3;    // ile razy probowac sie zalogowac i wyslac wiadomosc
  var $error = FALSE;
  var $auto_odpowiedz;    // gdy rozna od "" wtedy przy odebraniu wiad nadawca zostanie 
                          // poinformawany ze jest to numer bramki i wiadomosc nie dotarla

  function www2gg($numer, $haslo) 
  {
    $this->GaduGadu();      // trzeba wywolywac konstuktor recznie
    $this->numer = $numer;
    $this->haslo = $haslo;
  }

  function ustaw_opisy ($opis1 ,$opis2)
  { 
    # konwersja kodowania na CP-1250 i skrócenie statusów opisowych do 41 znaków
    $this->OPIS_W_STATUSIE_PO_ZALOGOWANIU = www2ggtxt::iso2cp(substr($opis1,0,41));
    $this->OPIS_W_STATUSIE_PO_WYLOGOWANIU = www2ggtxt::iso2cp(substr($opis2,0,41));
  }
  
  function wiadomosc($adresat, $tresc, $DODAWAJ_SPACJE_W_LINKACH = TRUE)
  {
    # Usuniecie dodatkowych \ przy w³aczonym magic_quotes w php.ini
    if (get_magic_quotes_gpc())
      $tresc = stripslashes($tresc);

    # Modyfikacja linkow www, email i slow www, pl ktore sa filtrowane przez blokade "antyspamowa"
    if ($DODAWAJ_SPACJE_W_LINKACH)
      $tresc = www2ggtxt::odlinkuj($tresc);

    # Sprawdzenie danych czy sie nadaja do wysy³ania:)
    if ($adresat == "") {
      $this->error = "Wpisz numer adresata";
      return FALSE;
    }
    if (!is_numeric($adresat) or ($adresat <= 100) or ($adresat > 999000000)) {
      $this->error = "Adresat ma byc numerkiem gadu - gadu";
      return FALSE;
    }
    if (strlen($tresc) == 0) {
      $this->error = "Nie wpisale¶ tre¶ci wiadomo¶ci";
      return FALSE;
    }
    if (strlen($tresc) > 1999) {
      $this->error = "Dlugo¶æ Wiadomo¶ci nie mo¿e przekroczyæ 2000 znaków";
      return FALSE;
    }
    if (($this->numer < 100) or ($this->numer > 999000000) or ($this->haslo == "")) {
      $this->error = "Wpisz poprawnie numer i has³o nadawcy";
      return FALSE;
    }
    if ($adresat == $this->numer) {
      $this->error = "Nie mo¿esz wys³aæ wiadomo¶ci sam do siebie";
      return FALSE;
    }

    for ($i = 1; $i <= $this->ilosc_prob; ++$i) {
      $this->Debug("Proba $i");

      # Pyta o host z jakim nalezy sie polaczyc lub losuje domyslny w przypadku niepowodzenia 
      list ($host, $port) = $this->znajdz_serwer($this->numer);

      # Logowanie do serwera GG 
      switch ($this->login($this->numer, $this->haslo, $host, 
              $port, $this->OPIS_W_STATUSIE_PO_ZALOGOWANIU)) 
      {

      # Wysy³anie wiadomo¶ci gdy GG_LOGIN_OK 
      case GG_LOGIN_OK:
          if ($this->wyslij_liste_kontaktow ($adresat)) {
            if ($seq = $this->wyslij_wiadomosc($adresat, $tresc))
            if ($this->odbierz_dane($this->auto_odpowiedz))  // czy wysylac odp. ze bramka
              $this->logoff($this->OPIS_W_STATUSIE_PO_WYLOGOWANIU);
                return $seq;  //sukces :)
          } else {
            $this->Debug("Po³±czenie zerwane po zalogowaniu"); break;
          }

      case DISCONNECTED:
          $this->Debug("Polaczenie odrzucone dla: $host
          ".gethostbyaddr($host).": $port"); break;

      case GG_LOGIN_FAILED:
          $this->error = "<BR>LOGIN FAILED - z³e has³o";
          return FALSE;

      case GG_LOGIN_FAILED2:
          $this->error = "<BR>LOGIN FAILED. "; return FALSE;

      default:
          $this->error = '<BR>LOGIN FAILED - hm, to nie powinno sie zdarzyc:)'; 
            return FALSE;
      }
    } // for i..

    $this->error = "<BR>LOGIN FAILED - po³±czenie odrzucone $i krotnie<BR>\n"
                ."Mo¿esz spróbowaæ jeszcze raz (odswie¿ stronê)\n";
  return FALSE;
  } // function wiadomosc

} // koniec klasy www2gg


#
# funckje do przekszta³cania i wy¶wietlania tekstu 
#

class www2ggtxt
{
  function www2ggtxt() { die ("to jest statyczna klasa"); }
  
  function wyswietl_status_odbiorcy($data)
  {
    if (strlen($data)<8) 
      return "";
    $tab = unpack("Vuin/Vstatus", $data); 

    $opisy_stanow = array(GG_STATUS_AVAIL => "dostêpny",
                          GG_STATUS_AVAIL_DESCR => "dostêpny z opisem",
                          GG_STATUS_BUSY => "zaraz wracam",
                          GG_STATUS_BUSY_DESCR => "zaraz wracam z opisem",
                          GG_STATUS_NOT_AVAIL_DESCR => "niedostêpny lub niewidoczny z opisem");
    
    switch ($tab['status']) {
      case GG_STATUS_AVAIL: 
      case GG_STATUS_BUSY: 
        $tab = unpack("Vuin/Vstatus/Vip/vport/Vversion/vunknown/",$data);
        break;
      case GG_STATUS_AVAIL_DESCR: 
      case GG_STATUS_BUSY_DESCR:
        $tab = unpack("Vuin/Vstatus/Vip/vport/Vversion/vunknown/A*opis",$data);
        break;
      case GG_STATUS_NOT_AVAIL_DESCR: 
        $tab = unpack("Vuin/Vstatus/A*opis",$data);
        break;
      default: 
        // Takiego statusu serwer nie powinien zwrocic
        return "";
    }
    $tab['stan_slownie'] = $opisy_stanow[$tab['status']];
    
    if ($tab['opis'])
      list($tab['opis'], $tab['czaspowrotu']) = explode("\0", $tab['opis']);

    if ($tab['czaspowrotu']) {
      $temp=unpack("Vczaspowrotu",$tab['czaspowrotu']);
      setlocale ("LC_TIME", "pl_PL");
      $tab['czaspowrotu']=strftime("<I>Bêdzie: %B %d(%A)  o %H:%M </I>", $temp['czaspowrotu']);
    }
    $tab['uin']  = $tab['uin'] & 0xFFFFFF; // szybki fix:)
    // u³o¿enie informacji do wy¶wietlenia
    $informacja = $tab['uin']." ma stan: <B>".$tab['stan_slownie']."</B>";
    if ($tab['opis']) 
      $informacja.=': "'.$tab['opis'].'" ';
    if ($tab['czaspowrotu']) 
      $informacja.=$tab['czaspowrotu'];
    
  return "<BR>\n".$informacja;
  }

  function wyswietl_wiadomosci ($wiadomosci) 
  {
    $html="";
    foreach ($wiadomosci as $tab)
      $html.=www2ggtxt::wyswietl_wiadomosc($tab['nadawca'], $tab['time'], $tab['wiadomosc']);

  return $html;
  }
  
  function wyswietl_wiadomosc($nadawca, $czas, $wiadomosc)
  {
    # Wykrycue formatowania tekstu w nowszych wersjach i przerobienie go na html
    if (strpos($wiadomosc, "\0")+1 < strlen($wiadomosc)) {
      $fmt = substr($wiadomosc, strpos($wiadomosc, "\0"));
      $wiadomosc = substr($wiadomosc, 0, strpos($wiadomosc, "\0"));
      $wiadomosc = www2ggtxt::konwertuj_formatowanie_na_html($wiadomosc, $fmt);
    }
    $wiadomosc = nl2br($wiadomosc);
    # U³o¿enie htmla do wy¶wietlenia
    $html = "<BR><B>".$nadawca."</B> ".strftime("(%H:%M)<BR>\n",$czas);
    $html.= www2ggtxt::cp2iso($wiadomosc)."<HR>\n";

  return $html;
  }

  function konwertuj_formatowanie_na_html($tresc, $fmt)
  {    
    # Rozbijamy formatowenie na bajty i wstawiamy do tablicy
    $fmt = preg_split('//', $fmt, -1, PREG_SPLIT_NO_EMPTY);
    $i = 1;                    // Wskaznik aktualnego znaku
    $tab = unpack("Ctyp/vsize", $fmt[$i++].$fmt[$i++].$fmt[$i++]);
    # Rozmiar format. i typ=2
    $size = $tab['size'];
    
    $nr = 0;
    $kawalek = "";

    # Tekst sk³ada siê z kawa³ków o innym formatowaniu
    while ($i < $size + 2) {
      $nr++;
      $tab = unpack("vpoz/Cmaska", $fmt[$i++].$fmt[$i++].$fmt[$i++]);
      $kawalek[$nr]['pozycja'] = $tab['poz'];
      $kawalek[$nr]['maska'] = $tab['maska'];

      if ($tab['maska'] & 1)
        $kawalek[$nr]['bold'] = 1;
      if ($tab['maska'] & 2)
        $kawalek[$nr]['italic'] = 1;
      if ($tab['maska'] & 4)
        $kawalek[$nr]['underline'] = 1;
      if ($tab['maska'] & 8) {
        $tab = unpack("CR/CG/CB", $fmt[$i++].$fmt[$i++].$fmt[$i++]);
        $kawalek[$nr]['R'] = $tab['R'];
        $kawalek[$nr]['G'] = $tab['G'];
        $kawalek[$nr]['B'] = $tab['B'];
      }
    }

    # Tworzymy pomocniczy kawalek z pozycja rowna dlugosci tresci
    $poz = 0;
    $kawalek[$nr + 1]['pozycja'] = strlen($tresc);

    # Znalezienie granic textu n podstawie pozycji i dodanie html'a;
    for ($i = 1; $i <= $nr; $i++) {
      $kawalek[$i]['tekst'] = substr($tresc, $poz, $kawalek[$i + 1]['pozycja'] - $poz);
      $poz = $kawalek[$i + 1]['pozycja'];
      $kawalek[$i]['html'] = $kawalek[$i]['tekst'];
      if ($kawalek[$i]['bold'])
        $kawalek[$i]['html'] = '<B>'.$kawalek[$i]['html'].'</B>';
      if ($kawalek[$i]['italic'])
        $kawalek[$i]['html'] = '<I>'.$kawalek[$i]['html'].'</I>';
      if ($kawalek[$i]['underline'])
        $kawalek[$i]['html'] = '<U>'.$kawalek[$i]['html'].'</U>';
      $rgb = sprintf("%02X%02X%02X", $kawalek[$i]['R'],
      $kawalek[$i]['G'], $kawalek[$i]['B']);
      $kawalek[$i]['html'] = '<FONT COLOR="'.$rgb.'">'.$kawalek[$i]['html'].'</FONT>';
      $tresc_html.= $kawalek[$i]['html'];
    }
    array_pop($kawalek);       //Wywalamy pomocniczy kawalek
    
  return $tresc_html;
  }

  function cp2iso($co) {
    return strtr($co, "\xA5\x8C\x8F\xB9\x9C\x9F", "\xA1\xA6\xAC\xB1\xB6\xBC"); }

  function iso2cp($co) {
    return strtr($co, "\xA1\xA6\xAC\xB1\xB6\xBC", "\xA5\x8C\x8F\xB9\x9C\x9F"); }
  
  function dodajspacje ($text) { return preg_replace("/([\.@:])/","\\1 ","$text"); }

  /* zmiana linków np moj@email.pl na moj@ email. pl zeby uniknac usuniêcia */
  function odlinkuj ($text) {

    $search = array (
              "/(\S+([\@\.])+?\S+)|(\S+:\/\/)/e"
              ,"/www/i"
              ,"/http/i"
              ,"/pl/i"
              );
    $replace = array(
              "www2ggtxt::dodajspacje('\\1\\3')"
              ,"w*w"
              ,"ht*p"
              ,"p1"      // filtry moga byc ju¿ nieaktualne
              );  
         
  return preg_replace($search, $replace ,$text);
  }

} // koniec klasy www2ggtxt
