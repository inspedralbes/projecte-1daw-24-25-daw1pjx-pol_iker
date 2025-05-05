<?php

$servername = "db"; 
$username = "usuari"; 
$password = "paraula_de_pas"; 
$dbname = "incidencia"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Error de connexió: " . $conn->connect_error);
}


$sql = "SELECT id, nom, cognom, departament, numero_pc, tipus_incidencia, descripcio FROM incidencies";
$result = $conn->query($sql);


echo "<h1>Llistat d'Incidències</h1>";
if ($result->num_rows > 0) {
   
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Cognom</th>
                <th>Departament</th>
                <th>Número de PC</th>
                <th>Tipus d'incidència</th>
                <th>Descripció</th>
            </tr>";
    
  
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["id"]) . "</td>
                <td>" . htmlspecialchars($row["nom"]) . "</td>
                <td>" . htmlspecialchars($row["cognom"]) . "</td>
                <td>" . htmlspecialchars($row["departament"]) . "</td>
                <td>" . htmlspecialchars($row["numero_pc"]) . "</td>
                <td>" . htmlspecialchars($row["tipus_incidencia"]) . "</td>
                <td>" . htmlspecialchars($row["descripcio"]) . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hi ha dades per mostrar.</p>";
}


$conn->close();
?>
