<?php

function showStartPage () {
   echo getHeader();
   echo '<p>Willkommen, ' . $GLOBALS['username'] . ' bei der Schl&uuml;sselverwaltung.</p>';

   echo getFooter();
}

function showLoginPage () {
   echo getHeader();
   echo '<p>Du musst Dich einloggen!.</p>';

   echo getFooter();
}

function getHeader () {
   $result = '<html>
<head>
<title>skeymanager</title>
<style type="text/css" id="bla">
a {
    text-decoration:none;
}

.label {
    font-size:1.5em;
}

.sublabel {

}

.labelinactive {
   font-size:1.5em;
	color:grey;

}

.sublabelinactive {
	color:grey;
}

</style>
</head>
<body id="seite" bgcolor="#FFFFFF" link="black" vlink="black" alink="red">';

   return $result;

}

function getFooter () {
   $result = '</body>
</html>';

   return $result;

}

?>
