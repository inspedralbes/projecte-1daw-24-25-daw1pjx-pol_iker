<?php
require_once 'connection.php';
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Llistat d'incidències</title>
    <link rel="stylesheet" href="proyecte.css">
     <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<header>
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><a href="index.php">PAGINA INICIAL</a></button>
            <button type="button" class="btn btn-primary"><a href="crear.php">FORMULARI DE INICIDÈNCIES</a></button>
        </div> 
        <h1>LLISTAT DE INICIDÈNCIES</h1>
    </header>
<body>
    <fieldset class = "llistat">
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
            echo "<a href='esborrar.php?id=" . $row["ID"] . "'><button style='background-color:red; color:white; border:none; padding:8px 12px; border-radius:5px;'>Esborrar</button></a><hr>";

        }
    } else {
        echo "<p>No hi ha dades a mostrar.</p>";
    }

    $conn->close();
    ?>
    </fieldset>