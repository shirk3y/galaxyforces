<?php
	$index = 'rules';
	$auth = true;

	require('include/header.php');

	tablebegin('Regulamin', 500);
	subbegin();

?>


<div align="center">
<br>
Regulamin czata
<br />
<br> 1. Czat jest dostepny dla wszystkich. Jesli chcesz porozmawiac o Galaxy Forces lub innych sprawach bez wulgaryzmow, bez obrazania, milo i kulturalnie ten czat jest dla ciebie. <br />
<br> 2. Nie tolerujemy wulgaryzmow, obrazania innych uzytkownikow, zamieszczania tresci niezgodnych z obowiazujacym prawem. W przypadku pojawienia sie takich tresci uzytkownik zostanie upomniany a w przypadku powtarzania sie takich sytuacji nick takiej osoby moze zostac czasowo lub bezterminowo zablokowany. <br />
<br> 3. Administratorzy zastrzegaja sobie prawo do zablokowania dostepu dowolnemu uzytkownikowi bez podania przyczyny na czas okreslony lub bezterminowo. <br />
<br> 4. Zabronione jest bez zgody administratora zamieszczanie w glownym oknie reklam o charakterze komercyjnym. <br />
<br> 5. W pozostalych przypadkach obowiazuje netykieta oraz ogolne zasady korzystania z czata. <br />
<br> 6. Korzystajac z czata GF akceptujesz powyzszy regulamin. <br />
</div>

<?php

	subend();
	tableend('Galaxy Forces', 500);

	require('include/footer.php');
