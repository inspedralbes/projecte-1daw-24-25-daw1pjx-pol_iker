<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connection.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ID'];
    if (is_numeric($id)) {
        $sql = "DELETE FROM Incidencies WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: esborrada.html");
            exit();
        } else {
            echo "<p class='error'>Error al esborrar l'incidència: " . htmlspecialchars($stmt->error) . "</p>";
            $stmt->close();
        }
    } else {
        echo "<p class='error'>ID no vàlid.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llistat</title>
    <link rel="stylesheet" href="proyecte.css">
</head>

<body>
<header>
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><a href="index.php">PAGINA INICIAL</a></button>
            <button type="button" class="btn btn-primary"><a href="llista.php">LLISTA DE INICIDÈNCIES</a></button>
            <button type="button" class="btn btn-primary"><a href="crear.php">FORMULARI INCIDENCIES</a></button>
        </div> 
        <h1>ESBORRAR INCIDENCIA </h1>
    </header>

    <!-- Tots els esborrats han de tenir conformació
 Per tant, primer hem de mostrar LA CASA, i aleshores tornar preguntar
 si realment la vol esborrar
 El primer cop rebem l'id de la casa via GET, i el segon cop via POST
 Això és una (de les moltes formes) de fer la doble confirmació
-->

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si el formulari s'ha enviat (mètode POST), procedim a esborrar la casa 
        $id = $_POST['ID'];
        // Comprovar si l'ID és un número vàlid
        if (is_numeric($id)) {
            // Preparar la consulta SQL per esborrar la casa
            $sql = "DELETE FROM Incidencies WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            // Executar la consulta i comprovar si s'ha esborrat correctament
            if ($stmt->execute()) {
                echo "<p class='info'>Incidencia esborrada amb èxit!</p>";
            } else {
                echo "<p class='error'>Error al esborrar l'incidencia: " . htmlspecialchars($stmt->error) . "</p>";
            }

            // Tancar la declaració
            $stmt->close();
        } else {
            echo "<p class='error'>ID no vàlid.</p>";
        }
    } elseif (isset($_GET['ID'])) {
        // Comprovar si s'ha rebut  l'ID de la casa via GET (a la URL esborrar.php?id=XXX)
        $id = $_GET['ID'];
        // Comprovar si l'ID és un número vàlid
        if (is_numeric($id)) {
            // Preparar la consulta SQL per obtenir la casa a esborrar
            $sql = "SELECT ID FROM Incidencies WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Comprovar si s'ha trobat la casa
            if ($result->num_rows > 0) {
                // Mostrar la casa a esborrar
                $row = $result->fetch_assoc();

                // Mostrar el formulari, que s'enviarà per POST, per confirmar l'esborrat
                echo "<form method='POST' action='esborrar.php'>";
                echo "<fieldset><h1>ELIMINAR INCIDENCIA</h1>";
                echo "<p>ID INCIDENCIA: ". htmlspecialchars($row["ID"]) . "";
                echo "<br>";
                echo "<input type='hidden' name='ID' value='" . htmlspecialchars($row["ID"]) . "'>";
                echo "<button style='display:inline-block; margin-top:10px; background-color:red; color:white; text-decoration:none; padding:8px 12px; border-radius:5px;><input type='submit ' value='Sí, esborrar'>Sí, esborrar</button>";
                echo "</fieldset>";
                echo "</form>";
            } else {
                echo "<p class='error'>No s'ha trobat l'incidencia amb ID: " . htmlspecialchars($id) . "</p>";
            }
        } else {
            echo "<p class='error'>ID no vàlid.</p>";
        }
    } else {
        echo "<p class='error'>No s'ha especificat cap ID.</p>";
    }
    ?>
</body>

</html>