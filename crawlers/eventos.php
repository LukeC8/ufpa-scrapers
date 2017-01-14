<?php

/*---------------------------------------------
 * - Projeto Crawler UFPA
 *---------------------------------------------
 * autor: lucas.correa@itec.ufpa.br
 * 
 * descricao:
 *  Pega noticias do site da ufpa e retorna
 *  as 20 ultimas noticias em um json.
 *
 * versao 2.0
 *
 */

require('News.php');

$url_eventos = "https://www.portal.ufpa.br/imprensa/todosEventos.php";
$news = array();

//---------------------------
//acessa a pagina de noticias
//---------------------------
$curl_object = curl_init();
curl_setopt($curl_object, CURLOPT_URL, $url_eventos);
curl_setopt($curl_object, CURLOPT_RETURNTRANSFER, true);

$curl_return = curl_exec($curl_object);

$http_code = curl_getinfo($curl_object, CURLINFO_HTTP_CODE);

if($http_code != "200") // se na pagina obtida for encontrado a string ERRO
{
    echo "[{}]";
    exit(1);
}

curl_close($curl_object);

//------------------------
//obtem os dados da pagina
//------------------------
$page_dom = new DOMDocument();
$page_dom->validateOnParse = true;

if (!@$page_dom->loadHTML($curl_return))
{
    echo "[{}]";
    exit(1);
}

$ul_lists = $page_dom->getElementById('todasNoticias')->getElementsByTagName('ul');

foreach($ul_lists as $ul)
    foreach($ul->getElementsByTagName('li') as $li)
        foreach($li->getElementsByTagName('a') as $a)
            $news[] = new News($a->nodeValue, $a->getAttribute('href'));

//-----------
//response
//-----------
echo json_encode($news);

?>

