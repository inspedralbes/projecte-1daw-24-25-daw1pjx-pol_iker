<?php

$servername = "db"; 
$username = "usuari"; 
$password = "paraula_de_pas"; 
$dbname = "incidencia"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
