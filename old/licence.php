<?php
	require('include/header.php');

	locale('website/licence');

	tablebegin($Lang['Licence'], 500);

	echo "\t\t<h3>${Lang['RegistrationAgreement']}</h3>${Lang['REGISTRATIONAGREEMENT']}<br /><br />";

	tablebreak();

	echo "\t\t<h3>${Lang['Licence']}</h3>${Lang['LICENCE']}<br /><br />";

	tableend('Galaxy Forces', 500);

	require('include/footer.php');
