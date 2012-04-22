<html>
<head>
<meta http-equiv="refresh" content="3;url=http://southafrica.isgreat.org/insere_jogo.html" />
</head>

<?php/*
 * Script that parses the form
 * for inserting the games in the DB,
 * this is supposed to be in some
 * sort of backoffice
 * not accessible to the public
 */
 
 session_start();

  if(!isset($_SESSION['nome']) || $_SESSION['nome']!='your_admin_name') {

    exit('<a href="http://southafrica.isgreat.org">Por favor registar/fazer login</a>');
  
  }
 
if ($con=mysql_connect($_SESSION['database_server'],$_SESSION['username'],$_SESSION['password']))
	{
	mysql_select_db($_SESSION['database'],$con);
	$query="INSERT INTO 2010_Jogos (team1,team2,grupos,start) VALUES (".$_POST['team1'].",".$_POST['team2'].",".$_POST['grupos'].",".$_POST['start'].")";
	if (mysql_query($query,$con))
		{
		echo "A BOMBAR CRLH";
		}
		else
		{
		echo "=( Problemas";
		}
	}
else 
	{
	echo "Nã ligou à base de dados =X";
	}
?>
