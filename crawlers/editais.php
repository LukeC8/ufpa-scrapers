	<?php
	//header('Content-Type: text/html; charset=CP1252');
	echo "/**************************************************************<br>";
	echo "Aplicativo ufpa - lado servidor<br>";
	echo "<br>";
	echo "<blockquote>Descricao do arquivo:<br>";
	echo "<blockquote>	- Le a pagina de editais";
	echo "<br>	- Adiciona os editais mais recentes no banco de dados</blockquote>";
	echo "<br>	Autor: lucas.correa@itec.ufpa.br";
	echo "<br>	Criado em: Dom 23 de Fev de 2014";
	echo "<br>	Versao do script: 1.2";
	echo "<br>	obs: Script sem uso de operacoes envolvendo arquivos";
	echo "</blockquote>";
	echo "**************************************************************/<br><br>";
	
	include 'config.php';
	
	$ocorrencias[40];	// matriz com 20 linhas e 2 colunas

	//inicializa variavel curl
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "http://www.portal.ufpa.br/imprensa/todosEditais.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$pg = curl_exec($ch);

	if(!$pg)
	{
		echo "Não foi possível acessar a página";
		exit();
	}

	$link = mysqli_connect($mysqli_host, $mysqli_user, $mysqli_password, $mysqli_database);

	if(mysqli_connect_errno($link))
	{
			echo "conexao com o bd falhou";
				exit;
	}

	mysqli_set_charset($link, 'UTF8');
	
	//retirar algumas tags desnecessarias e vai para a
	$texto_limpo = strstr(strip_tags($pg, '<li><a></a></li>'), "<li><a href=");
		
	//organizar leitura da pagina em linhas
	$text_inLines = explode("\n", $texto_limpo);
	
	$ocorrencia = 0;
	//percorre por algumas linhas em busca de ocorrencias
	for($i = 0; $i < 123 && $ocorrencia < 20; $i+=6, $ocorrencia++)
	{
			
		if(strlen($text_inLines[$i]) < 15)
		{
			//echo "Fim de leitura";
			break;
		}	
		$array = explode('"', $text_inLines[$i]);
		
		$array[1] = (string) str_replace("\r", '', $array[1]);
    	$array[1] = (string) str_replace("\n", '', $array[1]);
    	$array[1] = (string) str_replace("\t", '', $array[1]);
		
		$ocorrencias[$ocorrencia*2] = $array[1];
		
		$text_format = $text_inLines[$i+1].strstr($text_inLines[$i+2], " - ");
		
		$text_format = (string) str_replace("\r", '', $text_format);
    	$text_format = (string) str_replace("\n", '', $text_format);
    	$text_format = (string) str_replace("\t", '', $text_format);
    	$text_format = (string) str_replace("- -", '-', $text_format);
		$ocorrencias[($ocorrencia*2) + 1] = $text_format;
	
		//echo "<br>";
		//echo "<br>";
	}
	echo "<br>\núltima ocorrencia".$ocorrencia;
	
	for($i = 0; $i < $ocorrencia; $i++)
	{
		//echo "<br> codigo sql gerado = ";
		$tex_cod = iconv("CP1252", "UTF-8", addslashes($ocorrencias[($i*2)+1]));
		
		 $sql = sprintf("INSERT `editais`(`id`, `edital`, `link`) VALUES(%d, '%s', '%s') ON DUPLICATE KEY UPDATE edital='%s', link='%s'\n", $i+1, $tex_cod, $ocorrencias[$i*2], $tex_cod, $ocorrencias[$i*2]); 
		//echo "<br><br>";
		//---------------------------------------------------------------------
		$result = mysqli_query($link,$sql) or die(mysqli_error($link));
			if(!$result)
		{
			echo "erro [03]";
			break;
		}
	}
	mysqli_close($link);
	
?>

