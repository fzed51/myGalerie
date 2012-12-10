<?php

require_once ('manager.php');

$acces = $droits->setMulti('ADMIN', 'USER');

myDebug::trace("$droits");

$db = New MyDB();

$personne = new Personne();

MyDebug::traceVar($droits->getMulti($acces), 'acces');

if ($droits->checkIn($acces, $personne->getDroit())) {
    
$db->setup();

?>
<!DOCTYPE HTML>
<html lang="fr-FR" >
<head>
	<meta charset="UTF-8" >
	<title>mon album photo</title>
</head>
<body>
	<div id="master">
		<header>
			<h1>Mon album photo</h1>
			<nav><a href=""></a></nav>
		</header>
		<div id="content">
			<ul class="menu">
				<li><a href="">Accéder aux photos</a></li>
				<li><a href="">Mes photos</a></li>
				<li><a href="">Mes collection</a></li>
				<li><a href="">Mes groupes</a></li>
				<li><a href="">Mes visiteur</a></li>
			</ul>
		</div>
	</div>
</body>
</html>
    <?php

}

?>