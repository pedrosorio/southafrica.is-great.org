<!DOCTYPE html>
<html lang="pt">
<head>
<title>This time for Africa!</title>
<meta charset="iso-8859-1" />
<link rel="shortcut icon" href="A.ico">
<?php if(!isset($_POST['bet'])) echo '<meta http-equiv="refresh" content="120; url=http://southafrica.isgreat.org/mundial.php" />'; ?>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body class='black'>

<?php
/*
 * Main page, shows games, results,
 * update database after games finish and...
 * handle logins =D (i.e. big mess)
 */
function mostra_jogos($result) {  //function that displays games and (if finished) results
	while($row = mysql_fetch_array($result)) {
		$str = '<br /><br /><a href="http://southafrica.isgreat.org/jogo.php?jogo='. $row['jogo'] . '">' . $row['team1'];
		if($row['done'])
			$str = $str . ' ' . $row['golo1'] . ' - ' . $row['golo2'] . ' ';
		else
			$str = $str . ' - ';
		echo $str . $row['team2'] . '<br />' . $row['start'] . '</a>';
	}
}


session_start();
if(isset($_SESSION['nome'])) {
$con=mysql_connect($_SESSION['database_server'],$_SESSION['username'],$_SESSION['password']);

mysql_select_db($_SESSION['database'],$con);
	$result = mysql_fetch_array(mysql_query("SELECT * FROM 2010_Users WHERE nome='".$_SESSION['nome']."' "));
        $_SESSION['pontos'] = $result[pontos];
	echo $_SESSION['nome']." - ".$_SESSION['pontos']." pontos<br />";
}
else {
	if(!isset($_POST[nome])) {
		exit('<a href="http://southafrica.isgreat.org">Por favor registar/fazer login</a>');
	}
  
  //include file with database configuration
  include('config.php');
  
  $con=mysql_connect($_SESSION['database_server'],$_SESSION['username'],$_SESSION['password']);

  mysql_select_db($_SESSION['database'],$con);
	$result = mysql_query("SELECT * FROM 2010_Users WHERE nome='" .$_POST['nome']."' ");
	$row = mysql_fetch_array($result);
  
  //never use md5 again
	if($row[pass] == md5('xpto'.$_POST[pass])) {
		$_SESSION['nome'] = $_POST[nome];
		$_SESSION['pontos'] = $row['pontos'];
		echo $_SESSION['nome']." - ".$_SESSION['pontos']." pontos<br />";
		$_SESSION['nexts'] = $row['nexts'];
		date_default_timezone_set("Europe/Lisbon");
		$now=date("Y-m-d H:i:s");
    
    //big brother
		mysql_query("INSERT INTO 2010_Cons (nome,ip,hora) VALUES ('".$_SESSION['nome']."','".$_SERVER['REMOTE_ADDR']."','".$now."')"); 
	}
	else {    session_destroy();
		exit('<a href="http://southafrica.isgreat.org">Nome de utilizador/password incorrectos por favor tente novamente</a>');
	}
}

if(isset($_POST['nexts'])) {
	mysql_query("UPDATE 2010_Users SET nexts = ".$_POST['nexts']." WHERE nome='".$_SESSION['nome']."'");
	$_SESSION['nexts'] = $_POST['nexts'];
}
if(isset($_POST['champion']))
        mysql_query("UPDATE 2010_Users SET champion = '".$_POST['champion']."' WHERE nome='".$_SESSION['nome']."'");

if(isset($_POST['poll']))
        mysql_query("UPDATE 2010_Users SET poll = '".$_POST['poll']."' WHERE nome='".$_SESSION['nome']."'");

if(isset($_POST['bet'])) {
	$jogo = $_POST['jogo'];
	if(mysql_fetch_array($res = mysql_query("SELECT * FROM 2010_Apostas WHERE jogo='".$jogo."' AND nome='".$_SESSION['nome']."' ")))	
		mysql_query("DELETE FROM 2010_Apostas WHERE jogo='".$jogo."' AND nome='".$_SESSION['nome']."' ");
	else
		mysql_query("UPDATE 2010_Users SET apostas = apostas + 1 WHERE nome='".$_SESSION['nome']."' "); 
	mysql_query("INSERT INTO 2010_Apostas (jogo,nome,aposta) VALUES ('".$jogo."','".$_SESSION['nome']."','".$_POST['bet']."')",$con);
}
?>
<a href="http://southafrica.isgreat.org/table.php">Tabela de classificações</a>
<br />
<a href="http://southafrica.isgreat.org/apostas.php">As minhas apostas</a>
<br />
<a href="http://southafrica.isgreat.org/grupos.php">Classificações dos Grupos</a>
<br />
<a href="http://southafrica.isgreat.org/regulamento.html">Regulamento</a>
<br />
<a href="http://southafrica.isgreat.org/logout.php">Logout</a>
<center>


<?php
date_default_timezone_set("Europe/Lisbon");
$now = time();
//Former champion betting, now commented, beautiful xD
echo '<b>Sondagem terminada, o público decidiu, está decidido - quem acertar no campeão ganha 7 pontos!</b><br /><br />';
/*
  echo '<form action="" method="post"><select name="poll">';
$arr = array(5,7,9,12,15);
$user = mysql_fetch_array(mysql_query("SELECT poll FROM 2010_Users WHERE nome='".$_SESSION[nome]."'"));
foreach($arr as $val) {
   $r = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM 2010_Users WHERE poll='".$val."'"));
   $votos = $r['COUNT(*)'];
   echo '<option value="'.$val.'" ';
   if($user[poll]==$val)
      echo 'selected';
   echo '>'.$val.' pontos - '.$votos;
   if ($votos==1)
      echo ' voto</option>';
   else
      echo ' votos</option>';
}
echo '</select><input type="submit" value="OK" /></form><br /><br />';   
   

echo '<b><a href="http://southafrica.isgreat.org/campeao.php">Apostas para o campeão terminadas</a></b><br /><br />';
echo '<form action="" method="post"><select name="champion">';
$user = mysql_fetch_array(mysql_query("SELECT champion FROM 2010_Users WHERE nome='".$_SESSION[nome]."'"));
$result = mysql_query("SELECT team FROM 2010_Grupos ORDER BY team ASC");
while($row = mysql_fetch_array($result)) {
   echo '<option value="'.$row[team].'" ';
   if($user[champion]==$row[team])
      echo 'selected';
   echo '>'.$row[team].'</option>';
}
echo '</select><input type="submit" value="OK" /></form><br /><br />';*/


date_default_timezone_set("Europe/Lisbon");
$now2 = date('Y-m-d H:i:s');
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE done=0 AND start<'".$now2."'");
if(mysql_num_rows($result)) {
	echo "<b>-Jogos a Decorrer-</b><br />";
	$ch = curl_init("http://www.google.pt/search?q=world+cup+2010");
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$out = curl_exec($ch);
	curl_close($ch);
	while($row = mysql_fetch_array($result)) {
           $dt1 = $row[start];
           $y1 = substr($dt1,0,4);
           $m1 = substr($dt1,5,2);
           $d1 = substr($dt1,8,2);
           $h1 = substr($dt1,11,2);
           $i1 = substr($dt1,14,2);
           $s1 = substr($dt1,17,2); 
           $st = date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));
		$str = $row[team1];
		$jogo = $row[jogo];
 		if(($now - $st) > 300) {
                        $lol = $now - $st;
			echo 'Live Score - ';
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
                        echo "<br />";
                        $str = $str . ' ' . $sc1 . ' - ' . $sc2 . ' ';
			if($p4 && $p5 && ($p5 > $p4)) {
                                if($ppen=strpos($sc1,"(")) {
                                   $len = strpos($sc1,")") - $ppen - 1;
                                   $sc1 = (int)$sc1 + substr($sc1,$ppen+1,$len);
                                   $ppen=strpos($sc2,"(");
                                   $len = strpos($sc2,")")- $ppen - 1;
                                   $sc2 = (int)$sc2 + substr($sc2,$ppen+1,$len);
                                   echo $sc1 . " " . $sc2;
                                }
				$sc1 = (int)$sc1; $sc2=(int)$sc2; 
				if($sc1 == $sc2)
					$bet = 'X';
				if($sc1 > $sc2)
					$bet = '1';
				if($sc1 < $sc2)
					$bet = '2';
				$row[done]=1;
				$row[resultado] = $bet;
				$row[golo1] = $sc1;
				$row[golo2] = $sc2;
				mysql_query("UPDATE 2010_Jogos SET done = 1, resultado = '$bet', golo1 = '$sc1', golo2 = '$sc2' WHERE jogo = '$jogo'",$con);
                                if(strlen($row[grupos])==1) {
                                   mysql_query("UPDATE 2010_Grupos SET jogos = jogos + 1, golosM = golosM + ".$sc1.", golosS = golosS + ".$sc2.", golosD = golosD + ".($sc1 - $sc2)." WHERE team='".$row[team1]."'");
                                   mysql_query("UPDATE 2010_Grupos SET jogos = jogos + 1, golosM = golosM + ".$sc2.", golosS = golosS + ".$sc1.", golosD = golosD + ".($sc2 - $sc1)." WHERE team='".$row[team2]."'");
                                   if($bet=='1')
                                      mysql_query("UPDATE 2010_Grupos SET pontos = pontos + 3 WHERE team='".$row[team1]."'");
                                   if($bet=='2')
                                      mysql_query("UPDATE 2010_Grupos SET pontos = pontos + 3 WHERE team='".$row[team2]."'");
                                   if($bet=='X')
                                      mysql_query("UPDATE 2010_Grupos SET pontos = pontos + 1 WHERE team='".$row[team1]."' OR team='".$row[team2]."'");
   			        }
                                $res=mysql_query("SELECT * FROM 2010_Apostas WHERE jogo = '$jogo'",$con);
                         	while($r = mysql_fetch_array($res)) {		
					if($r[aposta] == $bet) {
						if(strlen($row[grupos])>1)
							mysql_query("UPDATE 2010_Users SET pontos = pontos + 3, certas = certas + 1, conc = conc + 1 WHERE nome = '$r[nome]'",$con);
						else
							mysql_query("UPDATE 2010_Users SET pontos = pontos + 1, certas = certas + 1, conc = conc + 1 WHERE nome = '$r[nome]'",$con);
					}
					else
						mysql_query("UPDATE 2010_Users SET conc = conc + 1 WHERE nome = '$r[nome]'",$con);
				}
			}
		}
		else {
			echo "Live scores a partir dos 5 minutos de jogo<br />";
			$str = $str . ' - ';
		}
                if(strlen($str) > 200)
                   $str = $row[team1] . " (fail a sacar o live score, fazer refresh) ";
		echo '<a href="http://southafrica.isgreat.org/jogo.php?jogo='.$row[jogo].'">'.$str . $row[team2] . '<br />' . $row[start] . '</a><br /><br />';
	}
}
?>    


<b>-Próximos Joguinhos-</b>
<form action="" method="post"><table><tr><td>Ver:</td>
<td><select name="nexts">
<?php
$user = mysql_fetch_array(mysql_query("SELECT nexts FROM 2010_Users WHERE nome='".$_SESSION['nome']."'"));
for ($i = 2; $i <= 8; $i=$i+2) {
   echo '<option value="'.$i.'"';
   if($user['nexts']==$i)
      echo 'selected';
   echo '>'.$i.'</option>';
}
echo '</select></td><td><input type="submit" value="OK" /></td></tr></table></form>';
date_default_timezone_set("Europe/Lisbon");
$now = date('Y-m-d H:i:s');
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE done=0 AND start>'".$now."' ORDER BY start LIMIT 0, ".$_SESSION[nexts]);
$ct = 1;
echo '<table>'; 
while($row = mysql_fetch_array($result)) {
	if ($ct%2 == 1)
		echo '<tr>';
	echo '<td width="25%">';
	echo '<center>';
	if(!( $tb = mysql_fetch_array(mysql_query("SELECT aposta FROM 2010_Apostas WHERE jogo=".$row[jogo]." AND nome='".$_SESSION[nome]."'"))))
		echo '<FONT COLOR="#FF0000">vvv Aposta agora vvv</FONT>';
	echo '<br /><a href="http://southafrica.isgreat.org/jogo.php?jogo='.$row[jogo].'">'.$row[team1].' - '.$row[team2].'<br />'.$row[start].'</a>';
	echo '<form action="" method="post">';
	echo '<table border="1">';
	echo '<tr><td>1</td>';
        if (strlen($row['grupos'])==1)
           echo '<td>X</td>';
        echo '<td>2</td></tr>';
	echo '<tr><td><center><input type="radio" name="bet" value="1" ';
        if($tb['aposta']== '1')
           echo 'checked';
        if (strlen($row['grupos'])==1) {
           echo '></td><td><center><input type="radio" name="bet" value="X" ';
           if($tb['aposta'] == 'X')
              echo 'checked';
        }
        echo '></td><td><center><input type="radio" name="bet" value="2" ';
        if($tb['aposta'] == '2')
           echo 'checked';
        echo '></td></tr>';
	echo '</table>';
	echo '<input type="hidden" name="jogo" value="'.$row['jogo'].'">';
	echo '<input type="submit" />';
	echo '</form>';
	echo '</center>';
	echo '</td>';
	if ($ct%2 == 0)
		echo '</tr>';
	$ct++;
}
echo '</table>';
echo '<br /><br />';

echo '<b>Final</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='FINAL' ORDER BY start");
mostra_jogos($result);
echo '<br /><br />';

echo '<b>3º/4º</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='LOSERS' ORDER BY start");
mostra_jogos($result);
echo '<br /><br />';

echo '<b>Meias</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='MEIAS' ORDER BY start");
mostra_jogos($result);
echo '<br /><br />';

echo '<b>Quartos</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='QUARTOS' ORDER BY start");
mostra_jogos($result);
echo '<br /><br />';


echo '<b>Oitavos</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='OITAVOS' ORDER BY start");
mostra_jogos($result);
echo '<br /><br />';

echo '<table border="1">';
echo '<tr>';


echo '<td width="25%">';
echo '<center>';
echo '<b>Grupo A</b>';

$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='A' ORDER BY start");
mostra_jogos($result);
echo '</td>';

echo '<td width="25%">';
echo '<center>';
echo '<b>Grupo B</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='B' ORDER BY start");
mostra_jogos($result);
echo '</td>';

echo '<td width="25%">';
echo '<center>';
echo '<b>Grupo C</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='C' ORDER BY start");
mostra_jogos($result);
echo '</td>';

echo '<td width="25%">';
echo '<center>';
echo '<b>Grupo D</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='D' ORDER BY start");
mostra_jogos($result);
echo '</td>';

echo '</tr>';
echo '<tr>';

echo '<td width="25%">';
echo '<center>';
echo '<b>Grupo E</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='E' ORDER BY start");
mostra_jogos($result);
echo '</td>';


echo '<td width="25%">';
echo '<center>';
echo '<b>Grupo F</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='F' ORDER BY start");
mostra_jogos($result);
echo '</td>';


echo '<td width="25%">';
echo '<center>';
echo '<b>Grupo G</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='G' ORDER BY start");
mostra_jogos($result);
echo '</td>';


echo '<td width="25%">';
echo '<center>';
echo '<b>Grupo H</b>';
$result = mysql_query("SELECT * FROM 2010_Jogos WHERE grupos='H' ORDER BY start");
mostra_jogos($result);
echo '</td>';
echo '</tr>';
echo '</table>';
?>

<a href="http://games.adultswim.com/robot-unicorn-attack-twitchy-online-game.html">Easter Egg</a>
