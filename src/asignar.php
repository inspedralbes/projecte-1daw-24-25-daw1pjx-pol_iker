<?php
require_once 'connection.php';

// Consultar incidencias no asignadas (Empleat es NULL)
$sql = "SELECT i.ID, 
               u.Nom AS Usuari, 
               epl.Nom AS Empleat, 
               d.Nom_Departament AS Departament, 
               e.Estat AS Estat, 
               i.Descripcio, 
               i.Fecha,
               p.Nivel_de_Prioritat AS Prioritat
        FROM Incidencies i
        JOIN Usuari u ON i.Usuari = u.DNI
        LEFT JOIN Empleat epl ON i.Empleat = epl.DNI
        JOIN Departament d ON i.Departament = d.ID
        JOIN Estat e ON i.Estat = e.ID
        LEFT JOIN Prioritat p ON i.Prioritat = p.ID
        WHERE i.Empleat IS NULL AND i.Prioritat IS NULL";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <?php
// Incluye la conexión a la base de datos
require_once 'connection.php';

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
            // Redirigir a una página de confirmación de borrado
            header("Location: esborrada.html");
            exit();
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
            <button type="button" class="btn btn-primary"><a href="llista.php">LLISTA DE INICIDÈNCIES</a></button>
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

    <title>Incidències No Assignades</title>
    <link rel="stylesheet" href="proyecte.css">
     <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
<header>
        <div class="btn-group">
        <button type="button" class="btn btn-primary"><a href="admin.php">ADMIN</a></button>
            <button type="button" class="btn btn-primary"><a href="llista_admin.php">LLISTA DE INICIDÈNCIES</a></button>

        </div> 
        <h1>FORMULARI DE INICIDÈNCIES </h1>
    </header>



<fieldset>

<?php if ($result->num_rows > 0): ?>
    <table class="llistat_no_asignades">
        <tr>
            <th>ID</th>
            <th>Usuari</th>
            <th>Empleat</th>
            <th>Departament</th>
            <th>Descripció</th>
            <th>Estat</th>
            <th>Prioritat</th>
            <th>Data</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["ID"]) ?></td>
                <td><?= htmlspecialchars($row["Usuari"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["Empleat"] ?? 'NO ASIGNAT') ?></td>
                <td><?= htmlspecialchars($row["Departament"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["Descripcio"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["Estat"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["Prioritat"] ?? 'NO ASIGNAT') ?></td>
                <td><?= htmlspecialchars($row["Fecha"] ?? '') ?></td>
                <td><a style="color: blue;" href="assignar_incidencia.php?id=<?= $row["ID"] ?>">Assignar</a></td>
                </tr>

        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No hi ha incidències sense tècnic assignat.</p>
<?php endif; ?>

<?php $conn->close(); ?>
</fieldset>

</body>
</html>


