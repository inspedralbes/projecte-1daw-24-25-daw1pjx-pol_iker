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
    $sql = "SELECT i.ID, u.Nom AS UsuariNom , epl.Nom AS NomEmpleat, d.Nom_Departament, e.Estat AS EstatText, i.Descripcio, i.Fecha
            FROM Incidencies i
            JOIN Usuari u ON i.Usuari = u.DNI
            LEFT JOIN Empleat epl ON i.Empleat = epl.DNI
            JOIN Departament d ON i.Departament = d.ID
            JOIN Estat e ON i.Estat = e.ID";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p><strong>ID:</strong> " . $row["ID"] . "<br>";
            echo "<strong>Usuari:</strong> " . htmlspecialchars($row["UsuariNom"]) . "<br>";
            echo "<strong>Empleat:</strong> " . htmlspecialchars($row["NomEmpleat"] ?? 'No assignat') . "<br>";
            echo "<strong>Departament:</strong> " . htmlspecialchars($row["Nom_Departament"]) . "<br>";
            echo "<strong>Estat:</strong> " . htmlspecialchars($row["EstatText"]) . "<br>";
            echo "<strong>Descripció:</strong> " . htmlspecialchars($row["Descripcio"]) . "<br>";
            echo "<strong>Data:</strong> " . htmlspecialchars($row["Fecha"]) . "<br>";
            echo "<a href='asignar.php?ID=" . $row["ID"] . "' style='display:inline-block; margin-top:10px; margin-right:10px; background-color:green; color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>Asignar</a>";
            echo "<a href='esborrar.php?ID=" . $row["ID"] . "' style='display:inline-block; margin-top:10px; margin-right:10px; background-color:red; color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>Esborrar</a>";
            echo "<a href='modificar.php?ID=" . $row["ID"] . "' style='display:inline-block; margin-top:10px;  background-color: rgb(31, 122, 140); color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>Modificar</a><hr>";
        }
    } else {
        echo "<p>No hi ha dades a mostrar.</p>";
    }

    $conn->close();
    ?>
    </fieldset>
