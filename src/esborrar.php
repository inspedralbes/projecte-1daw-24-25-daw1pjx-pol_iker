<?php
// Incluye la conexión a la base de datos
require_once 'connection.php';
require 'connection_Mongo.php';  // Incluir la función para registrar logs

// Registrar el log antes de cualquier salida HTML
registrarLog('/esborrar.php');

// Comprobar si se ha enviado el formulario de borrado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ID'];

    // Verificar que el ID sea válido (un número entero)
    if (is_numeric($id)) {

        // Eliminar las actuaciones asociadas primero
        $sql_actuacions = "DELETE FROM actuacio_de_incidencia WHERE id_incidencia = ?";
        $stmt_act = $conn->prepare($sql_actuacions);
        $stmt_act->bind_param("i", $id);

        // Ejecutar la consulta para eliminar las actuaciones
        if (!$stmt_act->execute()) {
            echo "<p class='error'>Error al eliminar actuacions: " . htmlspecialchars($stmt_act->error) . "</p>";
        }
        $stmt_act->close();

        // Ahora eliminar la incidencia de la tabla Incidencies
        $sql = "DELETE FROM Incidencies WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        // Ejecutar la consulta para eliminar la incidencia
        if ($stmt->execute()) {
            $stmt->close();
            
            echo "<p>Redirigiendo a esborrada.php...</p>";
            // Redirigir a una página de confirmación de borrado
            header("Location: esborrada.php");
            exit(); // Importante para evitar que el script continúe ejecutándose
        } else {
            echo "<p class='error'>Error al esborrar l'incidència: " . htmlspecialchars($stmt->error) . "</p>";
            $stmt->close();
        }
    } else {
        // Si el ID no es válido, mostrar un error
        echo "<p class='error'>ID no vàlid.</p>";
    }
}

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
            <button type="button" class="btn btn-primary"><a href="index.php">PAGINA INICIAL</a></button>
            <button type="button" class="btn btn-primary"><a href="llista.php">LLISTA DE INICIDÈNCIES</a></button>
            <button type="button" class="btn btn-primary"><a href="crear.php">FORMULARI INCIDENCIES</a></button>
        </div>
        <h1>ESBORRAR INCIDÈNCIA</h1>
    </header>

    <?php
    // Mostrar el formulario de confirmación si se ha recibido el ID por GET
    if (isset($_GET['ID'])) {
        $id = $_GET['ID'];

        // Verificar si el ID es válido
        if (is_numeric($id)) {
            // Consultar la incidencia a eliminar
            $sql = "SELECT ID FROM Incidencies WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Mostrar el formulario de confirmación de borrado
                echo "<form method='POST' action='esborrar.php'>";
                echo "<fieldset><h1>ELIMINAR INCIDÈNCIA</h1>";
                echo "<p>Estàs segur que vols eliminar aquesta incidència amb ID: ". htmlspecialchars($id) . "?</p>";
                echo "<input type='hidden' name='ID' value='" . htmlspecialchars($id) . "'>";
                echo "<button style='display:inline-block; margin-top:10px; background-color:red; color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>Sí, esborrar</button>";
                echo "</fieldset>";
                echo "</form>";
            } else {
                echo "<p class='error'>No s'ha trobat l'incidència amb ID: " . htmlspecialchars($id) . "</p>";
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
