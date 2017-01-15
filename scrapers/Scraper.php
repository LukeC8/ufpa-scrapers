<?php
/*-----------------------------------------------
 * Projeto UFPA Scrapers
 *-----------------------------------------------
 * Arquivo: Scraper.php
 *
 * Descricao: Classe base para acessar as paginas
 *
 * Autor: lucas.correa[at]itec.ufpa.br
 * Data de Criacao: 14/01/17
 * versao: 1.0
 *
 * Changes:
 *
 *----------------------------------------------*/

abstract class Scraper
{
    const RETURN_FORMAT_JSON = 0;

    protected $curlObject;
    protected $page_dom;

    function __construct($url)
    {
        $this->curlObject = curl_init();

        curl_setopt($this->curlObject, CURLOPT_URL, $url);
        curl_setopt($this->curlObject, CURLOPT_RETURNTRANSFER, true);

        $this->page_dom = new DOMDocument();
        $this->page_dom->validateOnParse = true;
    }

    function __destruct()
    {
        curl_close($this->curlObject);
    }

    protected function loadHTML($page)
    {
        if (!@$this->page_dom->loadHTML($page))
            throw new Exception("Erro ao gerar objeto DOMDocument a partir da página obtida\n");
    }

    function getWebPage()
    {
        $curlReturn = curl_exec($this->curlObject);

        $httpCode = curl_getinfo($this->curlObject, CURLINFO_HTTP_CODE);

        if (!$curlReturn or $httpCode != "200")
            throw new Exception("Erro ao acessar página!\n");

        return $curlReturn;
    }

    function changeURL($newURL)
    {
        curl_setopt($this->curlObject, CURLOPT_URL, $newURL);
    }

    //
    //implements scrapePage method for each particular webpage
    //
    abstract function scrapePage($dataFormat = Scraper::RETURN_FORMAT_JSON);

}

?>
