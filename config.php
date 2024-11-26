<?php 
$servername = "localhost";
$username = "anu"; 
$password = "Passw0rd";
$dbname = "pood"; 

// Loome ühenduse
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Kontrollime ühendust
if ($mysqli->connect_error) {
    die("Ühenduse viga: " . $mysqli->connect_error);
}
?>
