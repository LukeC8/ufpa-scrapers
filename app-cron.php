<?php

//--------------------------------------------------
//-	Script para atualizar servidor do app
//--------------------------------------------------

$url_crawler = Array (
		"URL/crawlers/editais.php", 
		"URL/crawlers/eventos.php",
		"URL/crawlers/noticias.php");

$ch = curl_init();

for ($i=0; $i < 3; $i++)
{
	curl_setopt($ch, CURLOPT_URL, $url_crawler[$i]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$pg = curl_exec($ch);

	echo "running script " . $url_crawler[$i] . " = ";

	if(!$pg)
		echo "fail!";
	else
		echo "Success!";

	echo "\n";
}

?>
