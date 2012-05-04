<div id='Clock'>&nbsp;</div>

<?php
	$minuta = substr(date('YmdHis'), 11, 1);
	$sekunda = substr(date('YmdHis'), 12, 2);

	if ($minuta == 9) $minuta2 = 0;
	if ($minuta == 8) $minuta2 = 1;
	if ($minuta == 7) $minuta2 = 2;
	if ($minuta == 6) $minuta2 = 3;
	if ($minuta == 5) $minuta2 = 4;
	if ($minuta == 4) $minuta2 = 0;
	if ($minuta == 3) $minuta2 = 1;
	if ($minuta == 2) $minuta2 = 2;
	if ($minuta == 1) $minuta2 = 3;
	if ($minuta == 0) $minuta2 = 4;

	if ($sekunda ==  0) $sekunda2 = 59;
	if ($sekunda ==  1) $sekunda2 = 58; 
	if ($sekunda ==  2) $sekunda2 = 57;
	if ($sekunda ==  3) $sekunda2 = 56;
	if ($sekunda ==  4) $sekunda2 = 55;
	if ($sekunda ==  5) $sekunda2 = 54;
	if ($sekunda ==  6) $sekunda2 = 53;
	if ($sekunda ==  7) $sekunda2 = 52;
	if ($sekunda ==  8) $sekunda2 = 51;
	if ($sekunda ==  9) $sekunda2 = 50;
	if ($sekunda == 10) $sekunda2 = 49;
	if ($sekunda == 11) $sekunda2 = 48;
	if ($sekunda == 12) $sekunda2 = 47;
	if ($sekunda == 13) $sekunda2 = 46;
	if ($sekunda == 14) $sekunda2 = 45;
	if ($sekunda == 15) $sekunda2 = 44;
	if ($sekunda == 16) $sekunda2 = 43;
	if ($sekunda == 17) $sekunda2 = 42;
	if ($sekunda == 18) $sekunda2 = 41;
	if ($sekunda == 19) $sekunda2 = 40;
	if ($sekunda == 20) $sekunda2 = 39;
	if ($sekunda == 21) $sekunda2 = 38;
	if ($sekunda == 22) $sekunda2 = 37;
	if ($sekunda == 23) $sekunda2 = 36;
	if ($sekunda == 24) $sekunda2 = 35;
	if ($sekunda == 25) $sekunda2 = 34;
	if ($sekunda == 26) $sekunda2 = 33;
	if ($sekunda == 27) $sekunda2 = 32;
	if ($sekunda == 28) $sekunda2 = 31;
	if ($sekunda == 29) $sekunda2 = 30;
	if ($sekunda == 30) $sekunda2 = 29;
	if ($sekunda == 31) $sekunda2 = 28;
	if ($sekunda == 32) $sekunda2 = 27;
	if ($sekunda == 33) $sekunda2 = 26;
	if ($sekunda == 34) $sekunda2 = 25;
	if ($sekunda == 35) $sekunda2 = 24;
	if ($sekunda == 36) $sekunda2 = 23;
	if ($sekunda == 37) $sekunda2 = 22;
	if ($sekunda == 38) $sekunda2 = 21;
	if ($sekunda == 39) $sekunda2 = 20;
	if ($sekunda == 40) $sekunda2 = 19;
	if ($sekunda == 41) $sekunda2 = 18;
	if ($sekunda == 42) $sekunda2 = 17;
	if ($sekunda == 43) $sekunda2 = 16;
	if ($sekunda == 44) $sekunda2 = 15;
	if ($sekunda == 45) $sekunda2 = 14;
	if ($sekunda == 46) $sekunda2 = 13;
	if ($sekunda == 47) $sekunda2 = 12;
	if ($sekunda == 48) $sekunda2 = 11;
	if ($sekunda == 49) $sekunda2 = 10;
	if ($sekunda == 50) $sekunda2 =  9;
	if ($sekunda == 51) $sekunda2 =  8;
	if ($sekunda == 52) $sekunda2 =  7;
	if ($sekunda == 53) $sekunda2 =  6;
	if ($sekunda == 54) $sekunda2 =  5;
	if ($sekunda == 55) $sekunda2 =  4;
	if ($sekunda == 56) $sekunda2 =  3;
	if ($sekunda == 57) $sekunda2 =  2;
	if ($sekunda == 58) $sekunda2 =  1;
	if ($sekunda == 59) $sekunda2 =  0;

;

?>

<script type='text/javascript'>
<!--

function tick(intMinutes,intSeconds) {

	var minutes, seconds, ap;
  
	timeString = "";

	intSeconds--;
	if (intSeconds <= 0) {
		intMinutes--;
		intSeconds=59;
	}

	if (intMinutes < 0) {
		intMinutes = 5; intSeconds = 0;
	}

	if (intMinutes < 10) {
		minutes = "0"+intMinutes;
	} else {
		minutes = intMinutes;
	}

	if (intSeconds < 10) {
		seconds = ":0"+intSeconds;
	} else {
		seconds = ":"+intSeconds+"&nbsp;";
	}
	ap="";
	timeString = (document.all)? timeString+minutes+seconds+" "+ap:timeString+minutes+seconds+" ";
	var clock = (document.all) ? document.all("Clock") : document.getElementById("Clock");
	clock.innerHTML = timeString;
	(document.all)?window.setTimeout("tick("+intMinutes+","+intSeconds+");", 1000):window.setTimeout("tick("+intMinutes+","+intSeconds+");", 1000);

}

<?php 
echo "tick($minuta2,$sekunda2);"; 
?>

//-->
</script>
