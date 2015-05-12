<?php
include 'config.php';

header('Content-Type: text/html; charset=CP1252');

$db = mysql_connect($mysql_host, $mysql_user, $mysql_password);

mysql_set_charset('UTF8', $db);

$noticias = array(array("Noticia" => " ", "Link" => " "),
						array("Noticia" => " ", "Link" => " "),
						array("Noticia" => " ", "Link" => " "),
						array("Noticia" => " ", "Link" => " "),
						array("Noticia" => " ", "Link" => " "),
						array("Noticia" => " ", "Link" => " "),
						array("Noticia" => " ", "Link" => " "),
						array("Noticia" => " ", "Link" => " "),
						array("Noticia" => " ", "Link" => " "),
						array("Noticia" => " ", "Link" => " "));

if(!$db){
	echo "falha ao conectar com o bando de dados";
	exit;
}


$table = mysql_select_db($mysql_database, $db);


if($table == 0)
	echo "conexao falhou";
else
{
	$query = "SELECT * FROM noticias LIMIT 0 , 30";
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
			$string = (string) $row['noticia'];
			$string = (string) str_replace("\r", '', $string);
    		$string = (string) str_replace("\n", '', $string);
    		$string = (string) str_replace("\t", '', $string);
    		$noticias[$i]['Noticia'] =  $string;
			$noticias[$i]['Link'] = (string) $row['link'];
		}
		
		echo $json = json_encode($noticias);
		
	}
	
}

mysql_close($db);

?>
