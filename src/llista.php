<?php
// Configuración de la conexión a la base de datos
$servername = "db"; // Cambia esto si tu servicio se llama diferente
$username = "usuari"; // Usuario de la base de datos
$password = "paraula_de_pas"; // Contraseña de la base de datos
$dbname = "incidencia"; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Error de connexió: " . $conn->connect_error);
}

// Consulta SQL para obtener todas las incidencias
$sql = "SELECT id, nom, cognom, departament, numero_pc, tipus_incidencia, descripcio FROM incidencies";
$result = $conn->query($sql);

// Mostrar los resultados
echo "<h1>Llistat d'Incidències</h1>";
if ($result->num_rows > 0) {
    // Crear una tabla para mostrar las incidencias
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
    
    // Mostrar cada fila de datos
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

// Cerrar la conexión
$conn->close();
?>
