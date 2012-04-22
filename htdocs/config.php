<?php
/*
 * Defines the session variables responsible for
 * the connection to the database
 * keep this out of "public" eyes
 */
session_start();

if(isset($_SESSION[nome])) {

$_SESSION['database_server'] = "your_sql_server.com";
$_SESSION['username'] = "your_username";
$_SESSION['password'] = "your_password";
$_SESSION['database'] = "your_database";

}

else

	exit('<a href="http://southafrica.isgreat.org">Por favor registar/fazer login</a>');

?>
