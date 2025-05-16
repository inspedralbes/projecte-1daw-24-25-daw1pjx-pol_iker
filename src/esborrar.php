<?php
// Iniciar sesión lo más arriba posible
session_start();

// Incluir conexiones
require_once 'connection.php';
require_once 'connection_Mongo.php';

// Registrar log
registrarLog('/esborrar.php');

// Buffer de salida para evitar errores con headers
ob_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ID'];

    if (is_numeric($id)) {
        // Eliminar actuaciones
        $sql_actuacions = "DELETE FROM actuacio_de_incidencia WHERE id_incidencia = ?";
        $stmt_act = $conn->prepare($sql_actuacions);
        $stmt_act->bind_param("i", $id);
        $stmt_act->execute();
        $stmt_act->close();

        // Eliminar incidencia
        $sql = "DELETE FROM Incidencies WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: esborrada.php");
            exit();
        } else {
            echo "<p class='error'>Error al esborrar l'incidència: " . htmlspecialchars($stmt->error) . "</p>";
            $stmt->close();
        }
    } else {
        echo "<p class='error'>ID no vàlid.</p>";
    }
}

ob_end_flush(); // Enviar salida
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Incidència</title>
    <link rel="stylesheet" href="proyecte.css">
</head>
<body>
    <header>
        <div class="btn-group">
            <button class="btn btn-primary"><a href="index.php">PÀGINA INICIAL</a></button>
            <button class="btn btn-primary"><a href="llista.php">LLISTA D'INCIDÈNCIES</a></button>
            <button class="btn btn-primary"><a href="crear.php">FORMULARI INCIDÈNCIES</a></button>
        </div>
        <h1>ESBORRAR INCIDÈNCIA</h1>
    </header>

    <?php
    if (isset($_GET['ID']) && is_numeric($_GET['ID'])) {
        $id = $_GET['ID'];

        $sql = "SELECT ID FROM Incidencies WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<form method='POST' action='esborrar.php'>";
            echo "<fieldset><h1>ELIMINAR INCIDÈNCIA</h1>";
            echo "<p>Estàs segur que vols eliminar aquesta incidència amb ID: ". htmlspecialchars($id) . "?</p>";
            echo "<input type='hidden' name='ID' value='" . htmlspecialchars($id) . "'>";
            echo "<button style='margin-top:10px; background-color:red; color:white; padding:8px 12px; border-radius:5px;'>Sí, esborrar</button>";
            echo "</fieldset></form>";
        } else {
            echo "<p class='error'>No s'ha trobat l'incidència amb ID: " . htmlspecialchars($id) . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='error'>No s'ha especificat cap ID o és invàlid.</p>";
    }
    ?>
</body>
</html>
