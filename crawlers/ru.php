<?php

echo "/**************************************************************<br>\n";
echo "Aplicativo ufpa - lado servidor<br>\n";
echo "<br>\n";
echo "<blockquote>Descricao do arquivo:<br>\n";
echo "<blockquote>	- Le a pagina do cardapio do ru e salva\n";
echo "<br>	- o cardapio da semana no banco de dados</blockquote>\n";
echo "<br>	Autor: lucas.correa[at]itec.ufpa.br\n";
echo "<br>	Criado em: Dom 06 de Out de 2013\n";
echo "<br>	Versao do script: 2.0\n";
echo "<br>	obs: o html do ru eh uma verdadeira bagunca, se o responsavel\n";
echo "<br>	colocar mais de uma linha em branco antes do cardapio do dia ser encontrado, temos um pequeno bug, para corrigi-lo devemos utilizar um loop para ficar lendo a linha até que esta contenha mais de 10bytes\n";
echo "</blockquote>\n";
echo "**************************************************************/<br>\n";

include 'config.php';

$ch = curl_init();

// informar URL e outras funções ao CURL
curl_setopt($ch, CURLOPT_URL, "http://www.ru.ufpa.br/");
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Acessar a URL e salvar em uma variavel
$pgRu = curl_exec($ch);
curl_close($ch);

//echo $pgRu; //[debug]

$texto_limpo = strip_tags($pgRu, '<address>');

//tenta reduzidr o arquivo
$texto_limpo = strstr($texto_limpo, 'Cardápio d');


//divide o texto em linhas
$textInLines = explode("\n", $texto_limpo);


/****************************************************
	-inicializa conexao com bd
****************************************************/
$link = mysqli_connect($mysqli_host, $mysqli_user, $mysqli_password, $mysqli_database);

if(mysqli_connect_errno($link))
{
	echo "conexao com o bd falhou\n";
	exit;
}

mysqli_query($link,"SET NAMES 'utf8'");
mysqli_query($link,'SET character_set_connection=utf8');
mysqli_query($link,'SET character_set_client=utf8');
mysqli_query($link,'SET character_set_results=utf8');

//busca pela primeira o correncia de data
$data = strip_tags($textInLines[0]);
echo $data;
//echo $DATA = fgets($arquivo, 1000); 
$sql_data = sprintf("UPDATE `cardapio` SET `data`='%s'", $data);
mysqli_query($link,$sql_data);

echo "<br><br><hr>";
$dias = array('SEGUNDA', 'TERÇA','QUARTA', 'QUINTA', 'SEXTA');

//vai de linha em linha procurando
for($i = 0, $linha = 1; $i<5; $linha++)
{
	$pos = strpos($textInLines[$linha], $dias[$i]);
	if($pos === false)
	{
		continue;
	}
	
	echo $dias[$i]."<br>\n";
	
	while(strlen($textInLines[$linha]) < 15)
		$linha++;	
	$ALMOCO = (string) str_replace("</a","\n</a", $textInLines[$linha]);
	$ALMOCO = str_replace("\n\n",'',strip_tags($ALMOCO));
	$ALMOCO = str_replace("  ",'', $ALMOCO);
	echo "Almoco: ".$ALMOCO."<br>\n";
	
	$linha++;	/// avanca para pegar o jantar, por mais que nao esteja faminto!
	
	while(strlen($textInLines[$linha]) < 15)
		$linha++;	
	$JANTAR = (string) str_replace("</a","\n</a", $textInLines[$linha]);
	$JANTAR = str_replace("\n\n",'',strip_tags($JANTAR));
	$JANTAR = str_replace("  ",'', $JANTAR);
	echo "Janta: ".$JANTAR."<br><br>\n";

	//insere dados em uma linha do bd
	$sql = sprintf("UPDATE `cardapio` SET `almoco`='%s', `jantar`='%s' WHERE `dias`='%s'\n", addslashes($ALMOCO), addslashes("$JANTAR"),addslashes($dias[$i]));
	//echo "<br><br>";
	//---------------------------------------------------------------------
	$result = mysqli_query($link,$sql) or die(mysqli_error($link));
	if(!$result)
	{
		echo "erro [03]";
		exit;
	}
	//---------------------------------------------------------------------
	$i++;
}

mysqli_close($link);

?>
