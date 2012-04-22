<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<?php
/*
 * In case something went wrong
 * updates database based on game results
 */


session_start();

$con=mysql_connect($_SESSION['database_server'],$_SESSION['username'],$_SESSION['password']);

mysql_select_db($_SESSION['database'],$con);
mysql_query("UPDATE 2010_Users SET pontos=0, certas=0, conc=0");
$res = mysql_query("SELECT * FROM 2010_Jogos WHERE done=1");
while($row = mysql_fetch_array($res)) {
	$r = mysql_query("SELECT * FROM 2010_Apostas WHERE jogo='$row[jogo]'");
        while($ap = mysql_fetch_array($r)) {
           if($ap[aposta] == $row[resultado]) {
              if(strlen($row[grupos]) > 1)
                 mysql_query("UPDATE 2010_Users SET pontos = pontos + 3, certas = certas + 1, conc = conc + 1 WHERE nome = '$ap[nome]'");
              else
                 mysql_query("UPDATE 2010_Users SET pontos = pontos + 1, certas = certas + 1, conc = conc + 1 WHERE nome = '$ap[nome]'");
           }
           else
              mysql_query("UPDATE 2010_Users SET conc = conc + 1 WHERE nome = '$ap[nome]'");
        }
}
?>
lol
