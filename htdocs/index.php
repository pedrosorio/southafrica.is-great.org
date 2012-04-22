<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<body class='black'>


<?php/*
 * Login page
 */
session_start();
session_destroy();
?>

<b>Login:</b>
<form action="mundial.php" method="post">
<table>
<tr><td>Nome:</td><td><input type="text" name="nome" /></td></tr>
<tr><td>Password:</td><td><input type="password" name="pass" /></td></tr>
</table>
<input type="submit" />
</form>
</br></br>

<b>Registar</b>
<form action="registar.php" method="post">
<table>
<tr><td>Nome:</td><td><input type="text" name="nome" /></td></tr>
<tr><td>Password:</td><td><input type="password" name="pass" /></td></tr>
</table>
<input type="submit" />
</form>
</br></br>
