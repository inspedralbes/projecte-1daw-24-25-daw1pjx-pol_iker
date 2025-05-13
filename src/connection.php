<?php

$servername = "daw.inspedralbes.cat"; 
$username = "a24ikematgar_incidencia"; 
$password = ":8VBky{Uq9D^W-xk"; 
$dbname = "a24ikematgar_incidencia"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
