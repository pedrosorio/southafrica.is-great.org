<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="sorttable.js"></script>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>


<body class='black'>


<?php/*
 * Page that shows the classification
 * of the 8 groups in the world cup
 */
session_start();
if(isset($_SESSION['nome'])) {
	echo "<a href='http://southafrica.isgreat.org/mundial.php'>Página principal</a></br>" . $_SESSION['nome']." - ".$_SESSION['pontos']." pontos</br><a href='http://southafrica.isgreat.org/logout.php'>Logout</a>";
}
else
	exit('<a href="http://southafrica.isgreat.org">Por favor registar/fazer login</a>');
$con=mysql_connect($_SESSION['database_server'],$_SESSION['username'],$_SESSION['password']);

mysql_select_db($_SESSION['database'],$con);
echo '<center><table>';
echo '<tr>';
for($g="A";$g<="H";$g++) {
   echo '<td width="25%">';
   echo '<center></br></br>Grupo '.$g.'</br></br>';
   echo '<table border="1">';
   echo '<tr><td>Equipa</td><td>Jgs</td><td>Pts</td><td>GM</td><td>GS</td><td>DG</td></tr>';
   $result = mysql_query("SELECT * FROM 2010_Grupos WHERE grupo='".$g."' ORDER BY pontos DESC, golosD DESC, golosM DESC");
   while($row = mysql_fetch_array($result))
      echo '<tr><td>'.$row['team'].'</td><td>'.$row['jogos'].'</td><td>'.$row['pontos'].'</td><td>'.$row['golosM'].'</td><td>'.$row['golosS'].'</td><td>'.$row['golosD'].'</td></tr>';
   echo '</table>';
   if($g == "D")
      echo '</tr><tr>';
}
?>
