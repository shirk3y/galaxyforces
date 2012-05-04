#  BRAMKA WWW2GG V2.2.1 W CALOSCI W PHP A WIEC MULIPLATFORMOWA
#  MOZLIWA DO ZAMIESZCZENIA NA SERWERACH BEZ OBSLUGI CGI
#
#  (C) Copyright 2001 - 2004 Piotr Mach <pm@gg.wha.la>
#  Bramka powsta³a dziêki opisowi protoko³u gadu-gadu
#  z projektu EKG http://dev.null.pl/ekg/ (GG dla Linuksa)
#
#  LICENCJA : Mo¿esz u¿ywaæ i przerabiaæ skrypt bez ograniczeñ,
#	      jednak by zachowaæ przyzwoitoœc nale¿y wymieniæ autora skryptu 
#	      i link do oryginalnej wersji: http://gg.wha.la gdy tworzysz modyfikacje.
#


Skrypt udaje program gadu-gadu w wersji 6.0.x, wysy³a jedn± wiadomo¶æ pod podany
numerek GG i informuje o jej odebraniu. U¿ycie skryptu jest ca³kowicie legalne.

1. Jak tego u¿ywaæ ? Bardzo prosto:
-----------------------------------

- Potrzebujesz serwer z obs³ug± PHP > 4.0.0 (celowo nie uzywa mozliwosci wyzszych wersji php)
- Skopiuj do katalogu strony plik www2gg.php i funkcje-gg.inc
 (w razie problemów po³±cz te pliki lub wpisz pelna sciezke do funckje-gg.inc)
- W ¿ródle skryptu www2gg.php wpisz nowe konto gg które bêdzie pe³niæ role bramki
- Mo¿esz równie¿ zmieniæ statusy opisowe numeru bramki (tak¿e ustawiæ na puste = "")
- Na stronie w miejscu gdzie chcesz umie¶ciæ bramke wstaw FORM z nastepujacymi polami
    - "adresat"
    - "tresc"
Moga to byæ listy wyboru, pola textowe, hidden, cokolwiek.
Jako action podaj scie¿ke do skryptu. To wszystko.

Przyk³ad:
<FORM METHOD="post" ACTION="www2gg.php">
    <INPUT TYPE="text" NAME="tresc" SIZE=100>
    <INPUT TYPE="text" NAME="adresat" SIZE="7" MAXLENGTH="7">
    <INPUT TYPE="submit" NAME="Submit" VALUE="Wyslij">
</FORM>
Skrypt sprawdzi poprawno¶æ danych, wiêc nie musisz siê o to martwiæ.


2. Wersja rozszerzona bramki
----------------------------

Tak jak w pkt.1 plus mo¿liwo¶æ wysylania z dowolnego konta 

W tym celu dodaj jeszcze:
 - pole wyboru o nazwie "tryb" o warto¶ciach "numer_wlasny" i "numer_bramki"
 - pola "numer" i "haslo" w ktore beda wpisywane odpowiednie dane potrzebne
  do wys³ania wiadomo¶ci.

Patrz plik przyklad1.html

Skrypt automagicznie rozpozna czy jest to wersja prostsza (adresat
i tresc) czy rozszerzona po obecno¶ci pola "tryb" wiêc nie musisz sie o to martwiæ :)



WY¦WIETLANIE WIADOMOSCI OCZEKUJACYCH NA SERWERZE GADU GADU
-----------------------------------------------------------

Po zalogowaniu do serwera gg wysylane sa wiadomo¶æi oczekuj±ce na odebranie
zostan± one wy¶wietlone dla trybu z mo¿liwoscia wpisania konta nadawcy.

Dla trybu prostszego równie¿ zostan± odebrane ale nie bêd± wy¶wietlone (przepadn±) 
poniewa¿ mog³aby je przeczytaæ inna osoba ni¿ ta do której by³y adresowane.

Je¶li chccesz aby by³y zawsze wy¶wietlane ustaw opcje
WYSWIETL_OCZEKUJACE_WIADOMOSCI_DLA_BRAMKI=1 w pliku www2gg.php




Mini Changelog :)
-----------------
30.04.2004 V2.2.1
 - usuniecie drobnego bledu, przez ktory nie dzialalo na starszych wersjach php
23.04.2004 V2.2 
 - uaktualnienie skryptu by udawa³ gg w wersji 6.x bo ni¿sze przesta³y dzia³aæ.
08.02.2002 V2.1 
 - mo¿liwosc ustawienia automatycznej odpowiedzi na wiadomosc "do bramki"
 - odbiektowy (jak na php 4x) kod, bardzo latwy do zastosowania we wlasnych skrytpach
26.01.2003 V2.0 - poprawa filtrow zabopiegajacych blokadzie wiadomosci
 - funkcja liczaca nowy hash logowania co da³o mozliwo¶c udawania gg w wersji 4.9.x
 - mo¿liwo¶æ wpisania statusów opisowych przy wysy³aniu i dla stanu "niedostepny" bramki
 - wyswietlanie statusu razem z opisem i ew. data powrotu adresata wiadomosci
 - przepisanie czê¶ci na obiekty, byæ mo¿e ³atwiej siê teraz u¿ywa w innych skryptach a mo¿e nie:)
 - uaktualnienie listy IP serwerów gadu
06.07.2002 V1.4
 - zmiana linków zabobiegaj±ca blokadzie "antyspamowej" 
15.05.2002 V1.3
 - poprawienie bledow (kto sie spieszy...:)
09.05.2002 V1.2 
 - dodanie formatowania tekstu, zamiana kolorów na html
 - poprawne dzialanie na register_global=off (PHP 4.2.0)
 - pewniejsze dzia³anie, ³adniejszy kod;)
21.03.2002 V1.1 
 - Odbieranie wiadomo¶ci oczekuj±cych na serwerze
 - 3 proby logowania
 - poprawki w ³±czeniu z serwerem
17.10.2001 V1.0
 - pierwsza dzia³aj±ca wersja




_______________________________________
++ <nowe wersje na http://gg.wha.la> ++
