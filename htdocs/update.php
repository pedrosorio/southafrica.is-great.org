<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<?php
/*
 * In case something went wrong
 * updates database based on number of bets
 */
session_start();
$con=mysql_connect($_SESSION['database_server'],$_SESSION['username'],$_SESSION['password']);

mysql_select_db($_SESSION['database'],$con);
$res = mysql_query("SELECT * FROM 2010_Users");
while($row = mysql_fetch_array($res)) {
	$r = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM 2010_Apostas WHERE nome='$row[nome]'"));
	$ap = $r['COUNT(*)'];
	echo $ap.'</br>';
	mysql_query("UPDATE 2010_Users SET apostas='$ap' WHERE nome='$row[nome]'");
}
lol
?>
