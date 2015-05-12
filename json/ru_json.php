<?php

/*********************************************************************
 * Aplicativo UFPA - lado servidor
 * json.php
 * 
 * 	-Descricao do arquivo:
 *		- Arquivo consulta uma tabela e exibe os dados da tabela
 *		- no formato de 'codificacao' json, adequado para o aplicativo
 * 		- android fazer leitura desses dados de uma forma rapida e eficaz
 * 	
 * 	-Criado em: 
 *		- Sab 19 de Outubro de 2013 (nice day, why???)rs
 * 	- Ultima modificacao:
 *		-			()
 *	-Autor:
 *		lucas.correa@itec.ufpa.br
 *
 *	-Bugs Encontrados:
 *
 *
 *	-Sujestoes:
 *
 *
 *************************************************************************************/
include 'config.php';

//  estabelecend uma conexao
$link = mysql_connect($mysql_host, $mysql_user, $mysql_password);

//$dia = array("Almoco" => "almoco", "Janta" => "janta");

//array que irah armazenar os dados codificados para o json
$cardapio = array(array("Almoco" => " ", "Janta" => " "),
                  array("Almoco" => " ", "Janta" => " "),
                  array("Almoco" => " ", "Janta" => " "),
                  array("Almoco" => " ", "Janta" => " "),
                  array("Almoco" => " ", "Janta" => " "),
                  array("Data" => " ", "Offset" => " "));

//se a conexao falhar
if(!$link){
	echo "falha ao conectar com o servidor de banco de dados";
	exit;
}

//selecionando base de dados
$table = mysql_select_db($mysql_database, $link);

if($table == 0)
	echo "conexao falhou";
else
{
	//query de consulta
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	$query = "SELECT * FROM cardapio";

	//executa query (envia)
	$resultado = mysql_query($query);
	$linhas = mysql_num_rows($resultado);
	if(!$resultado)
		echo "nada";
	else
	{
		//mostra o resultado para o usuario
                $row = mysql_fetch_array($resultado);
		
		$almo = (string) $row['almoco'];
		$jan  = (string) $row['jantar'];
		
		$cardapio[0]['Almoco'] = (strlen($almo)>15)? $almo: "Não Haverá Almoço    :(";
		$cardapio[0]['Janta']  = (strlen($jan)>15)? $jan: "Não Haverá Jantar    :(";
		$cardapio[5]['Data']   = $row['data'];
		
		for($i=1;$i<$linhas;$i++)
		{
			//pega cada linha do resultado
			$row = mysql_fetch_array($resultado);
			
			$almo = (string) $row['almoco'];
			$jan  = (string) $row['jantar'];
			
			$cardapio[$i]['Almoco'] = (strlen($almo)>15)? $almo: "Não Haverá Almoço    :(";
			
			$cardapio[$i]['Janta']  = (strlen($jan)>15)? $jan: "Não Haverá Jantar    :(";
                        
		}
               
              print $json = json_encode($cardapio);
            
                
	}
        
}	
mysql_close($link);


?>
