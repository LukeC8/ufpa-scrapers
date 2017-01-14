<?php

require('ScraperNews.php');

$url_noticias = "https://www.portal.ufpa.br/imprensa/todasNoticias.php";
$url_eventos = "https://www.portal.ufpa.br/imprensa/todosEventos.php";
$url_editais = "https://www.portal.ufpa.br/imprensa/todosEditais.php";

//------------------------------
//resposta em JSON para Noticias
//------------------------------
$scraper = new ScraperNews($url);

echo $scraper->scrapePage() . "\n\n";

unset($scraper);

//------------------------------
//resposta em JSON para Eventos
//------------------------------
$scraper = new ScraperNews($url);

echo $scraper->scrapePage() . "\n\n";

unset($scraper);

//------------------------------
//resposta em JSON para Editais
//------------------------------
$scraper = new ScraperNews($url);

echo $scraper->scrapePage() . "\n\n";

?>
