<?php
/*-----------------------------------------------
 * Projeto UFPA Scrapers
 *-----------------------------------------------
 * Arquivo: ScraperNews.php
 *
 * Descricao: Classe para acessar os dados da
 *            paginas de noticias, eventos e editais.
 *
 * Autor: lucas.correa[at]itec.ufpa.br
 * Data de Criacao: 13/01/17
 * versao: 1.0
 *
 * Changes:
 *  16/01/17 - $ul_lists => $ulLists
 *  14/01/17 - Add changeURL method
 *  16/05/19 - Add compatibility with new HTML Layout
 *
 *----------------------------------------------*/

require_once('News.php');
require_once('Scraper.php');

class ScraperNews extends Scraper
{
    private $news;

    function __construct($url)
    {
        parent::__construct($url);

        $this->news = array();
    }

    function changeURL($newURL)
    {
        parent::changeURL($newURL);

        unset($this->news);

        $this->news = array();
    }

    function scrapePage($dataFormat = Scraper::RETURN_FORMAT_JSON)
    {
        try
        {
            $webPage = $this->getWebPage();

            $this->loadHTML($webPage);

            $noticias = $this->pageDom->getElementById("adminForm");

            foreach ($noticias->getElementsByTagName("div") as $noticia) 
                if ($noticia->getAttribute("class") === "tileItem")
                    $this->news[] = new News($noticia);
        }
        catch (Exception $e)
        {
            echo $e->getMessage() . "\n";
        }
        finally
        {
            if ($dataFormat != Scraper::RETURN_FORMAT_JSON)
                return $this->news; //Array

            return json_encode($this->news); //JSON 
        }
    }
}

?>
