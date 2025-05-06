<?php

$servername = "db"; 
$username = "usuari"; 
$password = "paraula_de_pas"; 
$dbname = "incidencia"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de connexió: " . $conn->connect_error);
}

$sql = "SELECT ID, Estat, Empleat, Departament, Descripcio, Fecha FROM Incidencies";
$result = $conn->query($sql);

echo "<h1>Llistat d'Incidències</h1>";
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Empleat</th>
                <th>Departament</th>
                <th>Tipus d'incidència</th>
                <th>Descripció</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        // Aquí ajustamos los nombres de las columnas a los que realmente existen
        echo "<tr>
                <td>" . htmlspecialchars($row["ID"]) . "</td>
                <td>" . htmlspecialchars($row["Empleat"]) . "</td>
                <td>" . htmlspecialchars($row["Departament"]) . "</td>
                <td>" . htmlspecialchars($row["Estat"]) . "</td> <!-- Ajustado 'tipus_incidencia' a 'Estat' -->
                <td>" . htmlspecialchars($row["Descripcio"]) . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hi ha dades per mostrar.</p>";
}

$conn->close();
?>


