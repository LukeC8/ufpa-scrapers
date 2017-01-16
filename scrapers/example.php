<?php

require('ScraperNews.php');
require('ScraperRU.php');

$url_noticias = "https://www.portal.ufpa.br/imprensa/todasNoticias.php";
$url_eventos = "https://www.portal.ufpa.br/imprensa/todosEventos.php";
$url_editais = "https://www.portal.ufpa.br/imprensa/todosEditais.php";
$url_cardapio = "http://ru.ufpa.br/index.php?option=com_content&view=article&id=7";

//------------------------------
//resposta em JSON para Noticias
//------------------------------
$scraper = new ScraperNews($url_noticias);

echo $scraper->scrapePage() . "\n\n";

//------------------------------
//resposta em JSON para Eventos
//------------------------------
$scraper->changeURL($url_eventos);

echo $scraper->scrapePage() . "\n\n";

//------------------------------
//resposta em JSON para Editais
//------------------------------
$scraper->changeURL($url_editais);

echo $scraper->scrapePage() . "\n\n";

//---------------------------------
//resposta em JSON para o Cardapio
//---------------------------------
$scraperRU = new ScraperRU($url_cardapio);

echo $scraperRU->scrapePage() . "\n\n";
 
?>
