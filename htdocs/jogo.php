<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body class='black'>
<?php/*
 * Handles bets on a game before it starts
 * And fetching the result with curl after
 */
session_start();
if(isset($_SESSION['nome'])) {
	echo "<a href='http://southafrica.isgreat.org/mundial.php'>Página principal</a></br>" . $_SESSION['nome']." - ".$_SESSION['pontos']." pontos</br><a href='http://southafrica.isgreat.org/logout.php'>Logout</a>";
}
else
	exit('<a href="http://southafrica.isgreat.org">Por favor registar/fazer login</a>');
echo "<center>";
$jogo = $_GET['jogo'];
$con=mysql_connect($_SESSION['database_server'],$_SESSION['username'],$_SESSION['password']);

mysql_select_db($_SESSION['database'],$con);
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE jogo='".$jogo."' ");
$row = mysql_fetch_array($result);
date_default_timezone_set("Europe/Lisbon");
$now = time();
$dt1 = $row[start];
$y1 = substr($dt1,0,4);
$m1 = substr($dt1,5,2);
$d1 = substr($dt1,8,2);
$h1 = substr($dt1,11,2);
$i1 = substr($dt1,14,2);
$s1 = substr($dt1,17,2); 
$st = date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));

if(isset($_POST['bet'])) {  if($now > $st)    echo "Querias... =P</br>";  else {    if(mysql_fetch_array($res = mysql_query("SELECT * FROM 2010_Apostas WHERE jogo='".$jogo."' AND nome='".$_SESSION['nome']."' ")))	
      mysql_query("DELETE FROM 2010_Apostas WHERE jogo='".$jogo."' AND nome='".$_SESSION['nome']."' ");
    else
      mysql_query("UPDATE 2010_Users SET apostas = apostas + 1 WHERE nome='".$_SESSION['nome']."' "); 
    mysql_query("INSERT INTO 2010_Apostas (jogo,nome,aposta) VALUES (".$_GET['jogo'].",".$_SESSION['nome'].",".$_POST['bet'].")",$con);
  }
}
$str = $row['team1'];
if($row['done'])
	$str = $str . ' ' . $row['golo1'] . ' - ' . $row['golo2'] . ' ';
else {
	if($now - $st > 300) {
		echo 'Live Score - ';
		$ch = curl_init("http://www.google.pt/search?q=world+cup+2010");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$out = curl_exec($ch);
		curl_close($ch);
		$p1 = strpos($out,$row['team1']);
		$p2 = strpos($out,"align=right>",$p1);
		$p3 = strpos($out,"<",$p2);
		$sc1 = substr($out,$p2+12,$p3-$p2-12);
		$p2 = strpos($out,"align=left>",$p3);
		$p3 = strpos($out,"<",$p2);
		$sc2 = substr($out,$p2+11,$p3-$p2-11);
                $p4 = strpos($out,"Resumo",$p1);
                $p5 = strpos($out,"<tr>",$p1);
                $p6 = strpos($out,"&#39",$p1);
                if($p6) {
                    $p7 = strpos($out,"(",$p6-5);
                    $aiui = substr($out,$p7+1,$p6-$p7+3);
                    if(strlen($aiui) > 200)
                       $aiui = "Fail a sacar o minuto de jogo (provavelmente intervalo)";
                    echo $aiui;
                }
                echo "</br>";
                $str = $str . ' ' . $sc1 . ' - ' . $sc2 . ' ';
                if(strlen($str) > 200)
                   $str = $row['team1'] . " (fail a sacar o live score, fazer refresh) ";
	}
	else {
           if($now > $st)
              echo "Live scores a partir dos 5 minutos de jogo";
	   $str = $str . ' - ';
        }
}
echo $str . $row[team2] . '</br>' . $row[start] . '</br></br></br>';

$result = mysql_query("SELECT * FROM 2010_Apostas WHERE jogo='".$jogo."' ");
while($aposta = mysql_fetch_array($result)) {
	switch($aposta['aposta']) {
		case '1':
			$team = $row['team1']; break;
		case '2':
			$team = $row['team2']; break;
		case 'X':
			$team = "Empate"; break;
	}
	$str = $aposta['nome'] . " - " . $team . "</br>";
	if($aposta['nome'] == $_SESSION['nome']) {
		$str = "<FONT COLOR='#0000FF'>" .$str. "</FONT>";
	}
	if($row['done'] && $row['resultado'] == $aposta['aposta'])
		$str = "<b>" .$str. "</b>";
	echo $str;
}
?>

</br></br>
<?php
if($st-$now>0) {
   echo '<form action="" method="post">';
   echo '<table border="1">';
   echo '<tr><td>'. $row['team1'] . '  </td>';
   if(strlen($row['grupos'])==1)
      echo '<td>   Empate   </td>';
   echo '<td>  '. $row['team2'] .'</td></tr>';
   echo '<tr><td><center><input type="radio" name="bet" value="1"></td>';
   if(strlen($row['grupos'])==1)
      echo '<td><center><input type="radio" name="bet" value="X"></td>';
   echo '<td><center><input type="radio" name="bet" value="2"></td></tr></table><input type="submit" /></form>';
}
else {
	if($row['done'] == 0)
		echo "Jogo iniciado terminaram as apostas!";
	else {
		echo "Jogo terminado</br>";
		switch($row['resultado']) {
			case '1':
				$res = $row['team1']." vence!"; break;
			case '2':
				$res = $row['team2']." vence!"; break;
			case 'X':
				$res = "Empate!"; break;
		}
		echo $res;
	}
}
?>
