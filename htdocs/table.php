<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="sorttable.js"></script>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body class='black'>

<?php/*
 * Page that displays a table with user statistics
 * such as correct bets etc.
 */ 
session_start();
if(isset($_SESSION['nome'])) {
	echo "<a href='http://southafrica.isgreat.org/mundial.php'>Página principal</a></br>" . $_SESSION['nome']." - ".$_SESSION['pontos']." pontos</br><a href='http://southafrica.isgreat.org/logout.php'>Logout</a>";
}
else
	exit('<a href="http://southafrica.isgreat.org">Por favor registar/fazer login</a>');
$con=mysql_connect($_SESSION['database_server'],$_SESSION['username'],$_SESSION['password']);

mysql_select_db($_SESSION['database'],$con);
date_default_timezone_set("Europe/Lisbon");
$now=date("Y-m-d H:i:s");
$r = mysql_fetch_array(mysql_query("SELECT * FROM 2010_Misc"));
$update=0;
if(substr($now,8,2) != substr($r['lastupdate'],8,2)) {
   mysql_query("UPDATE 2010_Misc SET lastupdate = '".$now."' WHERE lastupdate = '".$r['lastupdate']."'");
   $update=1;
}
$result = mysql_query("SELECT * FROM 2010_Users ORDER BY pontos DESC, conc ASC");
echo "<center><table border='1' class='sortable'>";
echo "<tr><th>Nome</th><th>Pontos</th><th>Apostas</th><th>Concluídas</th><th>Certas</th><th>%</th><th>dp/dt</th><th>Addiction index</th></tr>";
$p=1;
while($row = mysql_fetch_array($result)) {
        if($update) {
           mysql_query("UPDATE 2010_Users SET last = pos WHERE nome = '".$row['nome']."'");
           $row['last'] = $row['pos'];
        }
        if($p != $row['pos'])
           mysql_query("UPDATE 2010_Users SET pos = '".$p."' WHERE nome = '".$row['nome']."'");
        if($row['nome'] == $_SESSION['nome'])
           echo "<tr class='blue'><td>".$row['nome']."</td><td>".$row['pontos']."</td><td>".$row['apostas']."</td><td>".$row['conc']."</td><td>".$row['certas']."</td><td align='right'>";
	else
           echo "<tr><td>".$row['nome']."</td><td>".$row['pontos']."</td><td>".$row['apostas']."</td><td>".$row['conc']."</td><td>".$row['certas']."</td><td align='right'>";
	if($row['conc'] == 0 )
		$di = 0;
	else
		$di = ($row['certas'] / $row['conc'])*100;
	printf("%01.2f",$di);
	echo "</td><td><center><img alt='' ";
        if($p < $row['last'])
           echo "src='up.jpeg' ";
        if($p == $row['last'])
           echo "src='same.jpeg' ";
        if($p > $row['last'])
           echo "src='down.jpeg' ";
        echo "width='20' height='20'/></center></td><td><center>";
        $adi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM 2010_Cons WHERE nome = '".$row['nome']."'"));
        echo $adi['COUNT(*)']."</center></td></tr>";
        $p = $p+1;
}
echo "</table>";
echo "(Clicar nos nomes das colunas para ordenar - by <a href='http://www.kryogenix.org/code/'>Stuart Langridge</a>)";
?>	
