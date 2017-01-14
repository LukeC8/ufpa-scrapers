<?php

require('News.php');

class ScraperNews
{
    private $curlObject;
    private $news;
    private $page_dom;

    function __construct($url)
    {
        $this->curlObject = curl_init();

        curl_setopt($this->curlObject, CURLOPT_URL, $url);
        curl_setopt($this->curlObject, CURLOPT_RETURNTRANSFER, true);

        $this->news = array();

        $this->page_dom = new DOMDocument();
        $this->page_dom->validateOnParse = true;
    }

    function __destruct()
    {
        curl_close($this->curlObject);
    }

    function getWebPage()
    {
        $curlReturn = curl_exec($this->curlObject);
        $httpCode = curl_getinfo($this->curlObject, CURLINFO_HTTP_CODE);

        if (!$curlReturn or $httpCode != "200")
            throw new Exception("Erro ao acessar página");

        return $curlReturn;
    }

    function loadHTML($page)
    {
        if (!@$this->page_dom->loadHTML($page))
            throw new Exception("Erro ao gerar objeto DOMDocument a partir da página obtida");
    }

    function scrapePage($returnFormat = "JSON")
    {
        try
        {
            $webPage = $this->getWebPage();

            $this->loadHTML($webPage);

            $ul_lists = $this->page_dom->getElementById('todasNoticias')->getElementsByTagName('ul');

            foreach($ul_lists as $ul)
                foreach($ul->getElementsByTagName('li') as $li)
                    foreach($li->getElementsByTagName('a') as $a)
                        $this->news[] = new News($a->nodeValue, $a->getAttribute('href'));
        }
        catch (Exception $e)
        {
            echo $e->getMessage() . "\n";
        }
        finally
        {
            return $returnFormat === "JSON" ? json_encode($this->news) : $this->news;

        }
    }
}

?>

