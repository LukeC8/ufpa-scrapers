<?php
header('Content-Type: text/html; charset=CP1252');
echo "/**************************************************************<br>";
echo "Aplicativo ufpa - lado servidor<br>";
echo "<br>";
echo "<blockquote>Descricao do arquivo:<br>";
echo "<blockquote>	- Le a pagina de noticias e salva";
echo "<br>	- as 10 primeiras noticia da semana no banco de dados</blockquote>";
echo "<br>	Autor: lucas.correa[at]itec.ufpa.br";
echo "<br>	Criado em: Sab 09 de Nov de 2013";
echo "<br>	Versao do script: 2.0";
echo "<br>	obs: nenhuma";
echo "</blockquote>";
echo "**************************************************************/<br><br>";

include 'config.php';

$ch = curl_init();

// pagina de onde sao retiradas as noticias
curl_setopt($ch, CURLOPT_URL, "http://www.portal.ufpa.br/imprensa/todasNoticias.php");

//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Acessar a URL e salvar em pgNoticias
$pgNoticias = curl_exec($ch);
curl_close($ch);

// Retira todas as tags html da pagina, deixando somente as tags <li> e <a>
$texto_limpo = strip_tags($pgNoticias, '<li><a></a></li>');

// abre uma conexao com o banco de dados
$link = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database);

if(mysqli_connect_errno())
{
	echo "conexao com o bd falhou";
	exit;
}

// informa ao servidor do bd que utilizamos a codificacao do texto em utf8
mysql_set_charset('UTF8', $link);

// condicional criado pelo fato de a pagina de noticias ter um bug na listagem
// de noticias que existem no banco de dados
// algumas vezes a pagina de noticias nao abre corretamente
// Erro verificado em 13/11/12
if(strstr($texto_limpo, 'Erro') != null) // se na pagina obtida for encontrado a string ERRO
{
	echo "<br>\nerro ao acessar pagina\n<br>";
}
else // se nao for encontrado erro na pagina
{
	echo "<br> pagina lida com sucesso<br>";

	$texto_limpo = strstr($texto_limpo, "Todas");
	
	$texto_limpo = strstr($texto_limpo, "href=");
	// informa ao banco a codificacao do texto em cada celula do banco
	//--------------------------------------------------
		mysql_query("SET NAMES 'utf8'");
		mysql_query('SET character_set_connection=utf8');
		mysql_query('SET character_set_client=utf8');
		mysql_query('SET character_set_results=utf8');
	//--------------------------------------------------
	
	$textInLines = explode("\n", $texto_limpo);
	
	// vai salvando as 20 primeiras noticias no banco
	for($linha = 0, $noticiasEncontradas = 0; $linha < 119; $linha+=6,$noticiasEncontradas++){
		echo "<br>".$noticiasEncontradas."<br>";
		
		// o texto resultante torna-se um array
		// o que estiver entre aspas torna-se um elemento do array
		$array = explode('"', $textInLines[$linha]);
		
		// pelas observacoeas ao arquivo resultante o segundo elemento do
		// array serah a url da noticia
		$endereco = $array[1];
		echo "<br>Url: ".$endereco;
		//echo "<br>";
		
		// recebe o texto da noticia
		$noticia = $textInLines[$linha+1].$textInLines[$linha+2];
		//echo "<br>";
		echo "<br>Noticia:".$noticia."\n<br>";
		
		$sql = sprintf("INSERT `noticias`(`id`, `noticia`, `link`) VALUES(%d, '%s', '%s') ON DUPLICATE KEY UPDATE noticia='%s', link='%s'\n", $noticiasEncontradas+1, iconv("CP1252", "UTF-8", addslashes($noticia)), addslashes("$endereco"), iconv("CP1252", "UTF-8", addslashes($noticia)), addslashes("$endereco"));
		//echo "<br><br>";
		//---------------------------------------------------------------------
		
		// salva os dados no banco de dados
		$result = mysql_query($sql) or die(mysql_error());

		if(!$result)
		{
			echo "erro [03]";
			exit;
		}
		//---------------------------------------------------------------------
		
		// vai para a proxima noticia (loop)

	}
	
	mysql_close($link);
}


?>
