<?php
require_once 'connection.php';
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Llistat d'incidències</title>
</head>
<body>
    <h1>Llistat d'incidències</h1>

    
    <?php
    $sql = "SELECT i.ID, u.Nom AS EmpleatNom, d.Nom_Departament, e.Estat AS EstatText, i.Descripcio, i.Fecha
            FROM Incidencies i
            JOIN usuari u ON i.Empleat = u.DNI
            JOIN Departament d ON i.Departament = d.ID
            JOIN Estat e ON i.Estat = e.ID";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p><strong>ID:</strong> " . $row["ID"] . "<br>";
            echo "<strong>Empleat:</strong> " . htmlspecialchars($row["EmpleatNom"]) . "<br>";
            echo "<strong>Departament:</strong> " . htmlspecialchars($row["Nom_Departament"]) . "<br>";
            echo "<strong>Estat:</strong> " . htmlspecialchars($row["EstatText"]) . "<br>";
            echo "<strong>Descripció:</strong> " . htmlspecialchars($row["Descripcio"]) . "<br>";
            echo "<strong>Data:</strong> " . htmlspecialchars($row["Fecha"]) . "<br>";
            echo "<a href='esborrar.php?id=" . $row["ID"] . "'>Esborrar</a></p><hr>";
        }
    } else {
        echo "<p>No hi ha dades a mostrar.</p>";
    }

    $conn->close();
    ?>

    <div id="menu">
        <hr>
        <p><a href="index.php">Portada</a></p>
        <p><a href="crear.php">formulari</
