<html>
<Head>
<title>Galaxy Forces - Kanał #galaxy na ircnet.pl</title>
<td background="../images/top.gif">
	<center><img src="../images/logo.gif" width="200" height="80" hspace="0" vspace="0" border="0" alt="Galaxy Forces" /></center>

<br />
</td>
<body bgcolor="black" text="white" link="#F4A460" vlink="#F4A460" alink="F4A460>  
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
</head>


<div align="center">

<?
extract($_GET); 
extract($_POST);

 $login = '';

 $login=str_replace("!","",$login);
 $login=str_replace(" ","_",$login);
 $login=str_replace("3.14","pi",$login);
 $login=str_replace(".","",$login);
 $login=str_replace("?","",$login);
 $login=str_replace("(","",$login);
 $login=str_replace(")","",$login);
 $login=str_replace("","s",$login);
 $login=str_replace("ń","n",$login);
 $login=str_replace("[","",$login);
 $login=str_replace("]","",$login);
 $login=str_replace("-","_",$login);

if ($login == '') {

echo'
 <form action="index.php" method="POST">
 Login: <input type="text" value="'.$login.'" name="login">
 <input type="submit" value="Enter">
</form>';

} else {

echo'

<applet code=IRCApplet.class archive="irc.jar,pixx.jar" width=640 height=400>
<param name="CABINETS" value="irc.cab,securedirc.cab,pixx.cab">

// ustawienia usera i serwera
<param name="nick" value="'.$login.'">
<param name="alternatenick" value="'.$login.'?">
<param name="name" value="'.$login.'-GF">

// Serwer
<param name="host" value="katowice.ircnet.pl">
<param name="quitmessage" value="GF The Best!!!">

// język
<param name="languageencoding" value="utf-16">
<param name="language" value="polish">
<param name="coding" value="3">

// Komendy
<param name="command1" value="join #galaxy">

// Jakie? pierdóły
<param name="asl" value="true">
<param name="useinfo" value="false">

// Dodatkowe funkcje
<param name="pixx:nickfield" value="true">
<param name="pixx:timestamp" value="true">

<param name="pixx:highlight" value="true">
<param name="pixx:highlightnick" value="true">

<param name="style:floatingasl" value="false">

// Styl & wygld
<param name="gui" value="pixx">


<param name="style:sourcefontrule1" value="all all Dialog 13">

</applet>';

}

// <center><A HREF="stat/index.php" target=blank>Zobacz statystyki kanału</A></center>
//<center><A HREF="stat/stat2.html" target=blank>Zobacz tabele z gadułami ;)</A></center>
//</div>

//</body>
//</html>