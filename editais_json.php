<?php
include 'config.php';

header('Content-Type: text/html; charset=CP1252');

$db = mysql_connect($mysql_host, $mysql_user, $mysql_password);

mysql_set_charset('UTF8', $db);

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

if(!$db){
	echo "falha ao conectar com o bando de dados";
	exit;
}


$table = mysql_select_db($mysql_database, $db);


if($table == 0)
	echo "conexao falhou";
else
{
	$query = "SELECT * FROM `editais`";
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	//executa query (envia)
	$resultado = mysql_query($query);
	$linhas = mysql_num_rows($resultado);
	if(!$resultado)
		echo "nada";
	else
	{
		
		for($i=0;$i<$linhas;$i++)
		{
			//mostra o resultado para o usuario
			$row = mysql_fetch_array($resultado);
    		$editais[$i]['Edital'] =  $row['edital'];
			$editais[$i]['Link'] = (string) $row['link'];
		}
		
		echo $json = json_encode($editais);
		
	}
	
}

mysql_close($db);

?>
