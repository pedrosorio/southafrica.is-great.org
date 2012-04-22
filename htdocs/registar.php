<html>
<head>
<meta http-equiv="refresh" content="3;url=http://southafrica.isgreat.org/index.php" />
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body class='black'>


<?php/*
 * Page that registers new users
 */
session_start();

include('config.php');
if ($con=mysql_connect($_SESSION['database_server'],$_SESSION['username'],$_SESSION['password'])) 
	{
	$nome=mysql_real_escape_string($_POST[nome]);
  
  //md5 =X
	$pass=md5('xpto'.$_POST[pass]);
	mysql_select_db($_SESSION['database'],$con);
	$result = mysql_query("SELECT * FROM 2010_Users WHERE nome='" . $nome . "' ");
	if (mysql_fetch_array($result))
		{
		exit("Já existe alguém registado com esse nome!");
		}
	$query="INSERT INTO 2010_Users (nome, pass, pontos) VALUES ('".$nome."','".$pass."',0)";
	if (mysql_query($query,$con))
		{
		echo "Registado/a com sucesso!!!";
		}
			else
		{
		echo "=( Problemas";
		}
	}
else 
	{
	echo 'Nã ligou à data base =X';
	}

?>
