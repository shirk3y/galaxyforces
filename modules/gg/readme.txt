#  BRAMKA WWW2GG V2.2.1 W CALOSCI W PHP A WIEC MULIPLATFORMOWA
#  MOZLIWA DO ZAMIESZCZENIA NA SERWERACH BEZ OBSLUGI CGI
#
#  (C) Copyright 2001 - 2004 Piotr Mach <pm@gg.wha.la>
#  Bramka powsta�a dzi�ki opisowi protoko�u gadu-gadu
#  z projektu EKG http://dev.null.pl/ekg/ (GG dla Linuksa)
#
#  LICENCJA : Mo�esz u�ywa� i przerabia� skrypt bez ogranicze�,
#	      jednak by zachowa� przyzwoito�c nale�y wymieni� autora skryptu 
#	      i link do oryginalnej wersji: http://gg.wha.la gdy tworzysz modyfikacje.
#


Skrypt udaje program gadu-gadu w wersji 6.0.x, wysy�a jedn� wiadomo�� pod podany
numerek GG i informuje o jej odebraniu. U�ycie skryptu jest ca�kowicie legalne.

1. Jak tego u�ywa� ? Bardzo prosto:
-----------------------------------

- Potrzebujesz serwer z obs�ug� PHP > 4.0.0 (celowo nie uzywa mozliwosci wyzszych wersji php)
- Skopiuj do katalogu strony plik www2gg.php i funkcje-gg.inc
 (w razie problem�w po��cz te pliki lub wpisz pelna sciezke do funckje-gg.inc)
- W �r�dle skryptu www2gg.php wpisz nowe konto gg kt�re b�dzie pe�ni� role bramki
- Mo�esz r�wnie� zmieni� statusy opisowe numeru bramki (tak�e ustawi� na puste = "")
- Na stronie w miejscu gdzie chcesz umie�ci� bramke wstaw FORM z nastepujacymi polami
    - "adresat"
    - "tresc"
Moga to by� listy wyboru, pola textowe, hidden, cokolwiek.
Jako action podaj scie�ke do skryptu. To wszystko.

Przyk�ad:
<FORM METHOD="post" ACTION="www2gg.php">
    <INPUT TYPE="text" NAME="tresc" SIZE=100>
    <INPUT TYPE="text" NAME="adresat" SIZE="7" MAXLENGTH="7">
    <INPUT TYPE="submit" NAME="Submit" VALUE="Wyslij">
</FORM>
Skrypt sprawdzi poprawno�� danych, wi�c nie musisz si� o to martwi�.


2. Wersja rozszerzona bramki
----------------------------

Tak jak w pkt.1 plus mo�liwo�� wysylania z dowolnego konta 

W tym celu dodaj jeszcze:
 - pole wyboru o nazwie "tryb" o warto�ciach "numer_wlasny" i "numer_bramki"
 - pola "numer" i "haslo" w ktore beda wpisywane odpowiednie dane potrzebne
  do wys�ania wiadomo�ci.

Patrz plik przyklad1.html

Skrypt automagicznie rozpozna czy jest to wersja prostsza (adresat
i tresc) czy rozszerzona po obecno�ci pola "tryb" wi�c nie musisz sie o to martwi� :)



WY�WIETLANIE WIADOMOSCI OCZEKUJACYCH NA SERWERZE GADU GADU
-----------------------------------------------------------

Po zalogowaniu do serwera gg wysylane sa wiadomo��i oczekuj�ce na odebranie
zostan� one wy�wietlone dla trybu z mo�liwoscia wpisania konta nadawcy.

Dla trybu prostszego r�wnie� zostan� odebrane ale nie b�d� wy�wietlone (przepadn�) 
poniewa� mog�aby je przeczyta� inna osoba ni� ta do kt�rej by�y adresowane.

Je�li chccesz aby by�y zawsze wy�wietlane ustaw opcje
WYSWIETL_OCZEKUJACE_WIADOMOSCI_DLA_BRAMKI=1 w pliku www2gg.php




Mini Changelog :)
-----------------
30.04.2004 V2.2.1
 - usuniecie drobnego bledu, przez ktory nie dzialalo na starszych wersjach php
23.04.2004 V2.2 
 - uaktualnienie skryptu by udawa� gg w wersji 6.x bo ni�sze przesta�y dzia�a�.
08.02.2002 V2.1 
 - mo�liwosc ustawienia automatycznej odpowiedzi na wiadomosc "do bramki"
 - odbiektowy (jak na php 4x) kod, bardzo latwy do zastosowania we wlasnych skrytpach
26.01.2003 V2.0 - poprawa filtrow zabopiegajacych blokadzie wiadomosci
 - funkcja liczaca nowy hash logowania co da�o mozliwo�c udawania gg w wersji 4.9.x
 - mo�liwo�� wpisania status�w opisowych przy wysy�aniu i dla stanu "niedostepny" bramki
 - wyswietlanie statusu razem z opisem i ew. data powrotu adresata wiadomosci
 - przepisanie cz�ci na obiekty, by� mo�e �atwiej si� teraz u�ywa w innych skryptach a mo�e nie:)
 - uaktualnienie listy IP serwer�w gadu
06.07.2002 V1.4
 - zmiana link�w zabobiegaj�ca blokadzie "antyspamowej" 
15.05.2002 V1.3
 - poprawienie bledow (kto sie spieszy...:)
09.05.2002 V1.2 
 - dodanie formatowania tekstu, zamiana kolor�w na html
 - poprawne dzialanie na register_global=off (PHP 4.2.0)
 - pewniejsze dzia�anie, �adniejszy kod;)
21.03.2002 V1.1 
 - Odbieranie wiadomo�ci oczekuj�cych na serwerze
 - 3 proby logowania
 - poprawki w ��czeniu z serwerem
17.10.2001 V1.0
 - pierwsza dzia�aj�ca wersja




_______________________________________
++ <nowe wersje na http://gg.wha.la> ++
