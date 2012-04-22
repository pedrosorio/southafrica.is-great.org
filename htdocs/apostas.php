<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="sorttable.js"></script>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>


<body class='black'>
<?php/*
 * Page that list all the bets
 * made by the current user
 * and their outcomes
 */
session_start();
if(isset($_SESSION['nome'])) {
	echo "<a href='http://southafrica.isgreat.org/mundial.php'>Página principal</a></br>" . $_SESSION['nome']." - ".$_SESSION['pontos']." pontos</br><a href='http://southafrica.isgreat.org/logout.php'>Logout</a>";
}
else
	exit('<a href="http://southafrica.isgreat.org">Por favor registar/fazer login</a>');
  
$con=mysql_connect($_SESSION['database_server'],$_SESSION['username'],$_SESSION['password']);
mysql_select_db($_SESSION['database'],$con);
echo "</br><center><table>";
$apostas=mysql_query("SELECT * FROM 2010_Apostas WHERE nome='".$_SESSION['nome']."'");
while($aposta = mysql_fetch_array($apostas)) {
   $jogo=mysql_fetch_array(mysql_query("SELECT * FROM 2010_Jogos WHERE jogo='".$aposta['jogo']."'"));
   $str = $jogo['team1'];
   if($aposta['aposta'] == '1')
      $str = "<u>" . $str . "</u>";
   $str = "<td align='right'>" . $str . "</td><td align='center'>";
   if($aposta['aposta'] == 'X')
      $str = $str . "<u>";
   if($jogo[done])
      $str = $str . " " . $jogo['golo1'] . " - " . $jogo['golo2'] . " ";
   else
     $str = $str . " - ";
   if($aposta['aposta']== 'X')
      $str = $str . "</u>";
   $str = $str . "</td><td align='left'>";
   if($aposta['aposta']== '2')
      $str = $str . "<u>" . $jogo['team2'] . "</u>";
   else
      $str = $str . $jogo['team2'];
   $str = $str . "</td></tr>";
   if($jogo['done'] && $jogo['resultado']==$aposta['aposta'])
      $str = "<tr class='blue'>" . $str;
   else
      $str = "<tr>" . $str;
   echo $str;   
}
echo "</table>";
?>
      
