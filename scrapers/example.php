<?php

require('ScraperNews.php');
require('ScraperRU.php');

$url_noticias = "https://www.portal.ufpa.br/index.php/ultimas-noticias2";

$url_editais_e_bolsas = [
    "Estagios" => "https://portal.ufpa.br/index.php/editais-e-bolsas/155-vagas-estagio",
    "EditaisAdm" => "https://portal.ufpa.br/index.php/editais-e-bolsas/161-ultimas-bolsas/editais-administracao",
    "EditaisCon" => "https://portal.ufpa.br/index.php/editais-e-bolsas/157-ultimas-bolsas/editais-concursos",
    "EditaisEsp" => "https://portal.ufpa.br/index.php/editais-e-bolsas/151-ultimas-bolsas/editais-especializacao",
    "EditaisMes" => "https://portal.ufpa.br/index.php/editais-e-bolsas/152-ultimas-bolsas/editais-mestrado",
    "EditaisDoc" => "https://portal.ufpa.br/index.php/editais-e-bolsas/153-ultimas-bolsas/editais-doutorado",
    "EditaisPdt" => "https://portal.ufpa.br/index.php/editais-e-bolsas/156-ultimas-bolsas/editais-pos-doutorado",
    "EditaisExt" => "https://portal.ufpa.br/index.php/editais-e-bolsas/160-ultimas-bolsas/editais-ensino-pesquisa-extensao"
];


$url_cardapio = "http://ru.ufpa.br/index.php?option=com_content&view=article&id=7";

//------------------------------
//resposta em JSON para Noticias
//------------------------------
$scraper = new ScraperNews($url_noticias);

echo $scraper->scrapePage() . "\n\n";

//------------------------------
//resposta em JSON para Editais
//------------------------------
foreach($url_editais_e_bolsas as $url_editais) {
    
    $scraper->changeURL($url_editais);

    echo $scraper->scrapePage() . "\n\n";
}

//---------------------------------
//resposta em JSON para o Cardapio
//---------------------------------
$scraperRU = new ScraperRU($url_cardapio);


echo $scraperRU->scrapePage() . "\n\n";
 
?>
