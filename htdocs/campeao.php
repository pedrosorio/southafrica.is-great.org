<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>


<body class='black'>
<?php/*
 * Page that shows the bets
 * for the champion
 */
session_start();
if(isset($_SESSION['nome'])) {
	echo "<a href='http://southafrica.isgreat.org/mundial.php'>Página principal</a></br>" . $_SESSION['nome']." - ".$_SESSION['pontos']." pontos</br><a href='http://southafrica.isgreat.org/logout.php'>Logout</a>";
}
else
	exit('<a href="http://southafrica.isgreat.org">Por favor registar/fazer login</a>');
$con=mysql_connect($_SESSION['database_server'],$_SESSION['username'],$_SESSION['password']);

mysql_select_db($_SESSION['database'],$con);
echo "<center>";
$teams = mysql_query("SELECT * FROM 2010_Grupos");
while($team = mysql_fetch_array($teams)) {
   $users = mysql_query("SELECT * FROM 2010_Users WHERE champion = '".$team['team']."'");
   $named = 0;
   while($user = mysql_fetch_array($users)) {
      if($named == 0) {
         $named = 1;
         echo "</br><b>".$team['team']."</b></br>";
      }
      echo $user['nome']."</br>";
   }
}
?>
