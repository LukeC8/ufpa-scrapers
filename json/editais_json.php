<?php
include '../../config.php';

header('Content-Type: text/html; charset=CP1252');

$link = dbConnect();

mysqli_set_charset($link, 'UTF8');

$editais = array(array("Edital" => " ", "Link" => " "),
						array("Edital" => " ", "Link" => " "),
						array("Edital" => " ", "Link" => " "),
						array("Edital" => " ", "Link" => " "),
						array("Edital" => " ", "Link" => " "),
						array("Edital" => " ", "Link" => " "),
						array("Edital" => " ", "Link" => " "),
						array("Edital" => " ", "Link" => " "),
						array("Edital" => " ", "Link" => " "),
						array("Edital" => " ", "Link" => " "));

if(!$link){
	echo "falha ao conectar com o bando de dados";
	exit;
}

	$query = "SELECT * FROM `editais`";
	mysqli_query($link, "SET NAMES 'utf8'");
	mysqli_query($link, 'SET character_set_connection=utf8');
	mysqli_query($link, 'SET character_set_client=utf8');
	mysqli_query($link, 'SET character_set_results=utf8');
	//executa query (envia)
	$resultado = mysqli_query($link, $query);
	$linhas = mysqli_num_rows($resultado);
	if(!$resultado)
		echo "nada";
	else
	{
		for($i=0;$i<$linhas;$i++)
		{
			//mostra o resultado para o usuario
			$row = mysqli_fetch_array($resultado);
    		$editais[$i]['Edital'] =  $row['edital'];
			$editais[$i]['Link'] = (string) $row['link'];
		}
		
		echo $json = json_encode($editais);
		
	}
	
mysqli_close($link);

?>
