<?php
require_once 'connection.php';

// Consultar incidencias no asignadas (Empleat es NULL)
$sql = "SELECT *
        FROM Incidencies i
        WHERE i.Empleat IS NULL";  // Filtrar solo incidencias sin técnico asignado

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Incidències No Assignades</title>
</head>
<body>

<h1>Incidències No Assignades</h1>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["ID"] . " - Usuari: " . $row ["Usuari"] .  " - Empleat: " . $row ["Empleat"] . " - Departament: " . $row ["Departament"] . " - Descripció: " . $row["Descripcio"] . " - Estat: " . $row ["Estat"] . " - Fecha: " . $row ["Fecha"] .  "<br>";
    }
} else {
    echo "No hi ha incidències sense tècnic assignat.";
}

$conn->close();
?>

</body>
</html>
