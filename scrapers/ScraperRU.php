<?php
/*-----------------------------------------------
 * Projeto UFPA Scrapers
 *-----------------------------------------------
 * Arquivo: ScraperRU.php
 *
 * Descricao: Classe para acessar os dados da
 *            pagina de cardapio do RU.
 *
 * Autor: lucas.correa[at]itec.ufpa.br
 * Data de Criacao: 15/01/17
 * versao: 1.0
 *
 * Changes:
 *
 *----------------------------------------------*/

require_once('Scraper.php');

class ScraperRU extends Scraper
{
    private $menu;

    function __construct($url)
    {
        parent::__construct($url);

        //---------------------------
        //formato do JSON de retorno
        //---------------------------
        $this->menu = array(
            "date" => "",
            "days" => array (
                array("", ""),
                array("", ""),
                array("", ""),
                array("", ""),
                array("", "")
            )
        );
    }

    private function cleanStr($str)
    {
        return utf8_encode(str_replace("\r", "", trim($str)));
    }

    //------------------------------------------------------
    //funcao especifica do layout atual da pagina para pegar
    //a data da semana
    //------------------------------------------------------
    private function getWeekDate (DOMElement $table)
    {
        $weekDateLine = $table->getElementsByTagName('tr')->item(1);
        $weekDateElement = $weekDateLine->getElementsByTagName('td');
        $date = $this->cleanStr($weekDateElement->item(0)->nodeValue);

        return explode(" - ", str_replace("\n", " - ", $date))[1];
    }

    //-------------------------------------------------------
    //funcao especifica do layout atual da pagina para pegar
    //o cardapio do dia da semana
    //-------------------------------------------------------
    private function getDayMenu(DOMElement $tbMenu, $day)
    {
        $line = $tbMenu->getElementsByTagName('tr')->item($day + 1)->getElementsByTagName('td');
        $todaysMenu = array();

        for($i = 1; $i < $line->length; $i++)
            $todaysMenu[] = $this->cleanStr($line->item($i)->nodeValue);

        return $todaysMenu;
    }

    //-------------------------------------------------------
    //funcao especifica do layout atual da pagina para pegar
    //o cardapio de toda semana
    //-------------------------------------------------------
    private function getWeekMenu(DOMElement $tbMenu)
    {
        $days = array ();

        for($i = 0; $i < 5; $i++)
            $days[] = $this->getDayMenu($tbMenu, $i);

        return $days;
    }

    function scrapePage($dataFormat = Scraper::RETURN_FORMAT_JSON)
    {
        try
        {
            $webPage = $this->getWebPage();

            $this->loadHTML($webPage);

            $tbMain = $this->pageDom->
                getElementById('centro')->
                getElementsByTagName('table')->item(1)->
                getElementsByTagName('tr')->item(0)->
                getElementsByTagName('td')->item(0)->
                getElementsByTagName('table');

            $tbDate = $tbMain->item(0);
            $tbMenu = $tbMain->item(1);

            $this->menu["date"] = $this->getWeekDate($tbDate);
            $this->menu["days"] = $this->getWeekMenu($tbMenu);
        }
        catch (Exception $e)
        {
            echo $e->getMessage() . "\n";
        }
        finally
        {
            if ($dataFormat != Scraper::RETURN_FORMAT_JSON)
                return $this->menu; //array

            return json_encode($this->menu); //JSON
        }
    }
}

?>
