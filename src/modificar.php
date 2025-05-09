<?php
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar la actualización
    $id = $_POST['ID'];
    $departament = $_POST['Departament'];
    $descripcio = $_POST['Descripcio'];
    $estat = $_POST['Estat'];

    // Consulta para actualizar la incidencia
    $sql = "UPDATE Incidencies SET Departament = ?, Descripcio = ?, Estat = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $departament, $descripcio, $estat, $id); // El tipo es "isii" porque Departament es un número (ID), Descripcio es texto, Estat es un número e ID es un número

    if ($stmt->execute()) {
        // Si la actualización fue exitosa, redirigimos al usuario a la página "esborrada.html"
        $stmt->close();
        header("Location: modificat.html");
        exit();  // Aseguramos que el código siguiente no se ejecute después de la redirección
    } else {
        echo "<p class='error'>Error en actualitzar: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
} elseif (isset($_GET['ID'])) {
    // Mostrar el formulario con los datos actuales
    $id = $_GET['ID'];

    if (is_numeric($id)) {
        // Consulta para obtener los datos de la incidencia
        $sql = "SELECT * FROM Incidencies WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Obtener los departamentos para el select
            $departaments = [];
            $dept_sql = "SELECT ID, Nom_Departament FROM Departament";
            $dept_result = $conn->query($dept_sql);
            if ($dept_result && $dept_result->num_rows > 0) {
                while ($dept_row = $dept_result->fetch_assoc()) {
                    $departaments[] = $dept_row;
                }
            }
             // Obtener los estados para el select
            $estats = [];
            $estat_sql = "SELECT ID, Estat FROM Estat"; // Aquí asumo que la tabla Estat tiene los campos ID y Nom_Estat
            $estat_result = $conn->query($estat_sql);
            if ($estat_result && $estat_result->num_rows > 0) {
                while ($estat_row = $estat_result->fetch_assoc()) {
                    $estats[] = $estat_row;
                }
            }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Incidència</title>
    <link rel="stylesheet" href="proyecte.css">
</head>
<body>
    <header>
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><a href="index.php">PAGINA INICIAL</a></button>
            <button type="button" class="btn btn-primary"><a href="llista.php">LLISTA DE INICIDÈNCIES</a></button>
            <button type="button" class="btn btn-primary"><a href="crear.php">FORMULARI INCIDÈNCIES</a></button>
        </div> 
        <h1>MODIFICAR INCIDÈNCIA</h1>
    </header>

    <form method="POST">
        <fieldset>
            <h1>Editar Incidència ID <?php echo htmlspecialchars($row['ID']); ?></h1>

            <input type="hidden" name="ID" value="<?php echo htmlspecialchars($row['ID']); ?>">

            <label for="Departament">Departament:</label><br>
            <select name="Departament" id="Departament" required>
                <option value="">-- Selecciona --</option>
                <?php
                foreach ($departaments as $dept) {
                    $selected = ($dept['ID'] == $row['Departament']) ? "selected" : "";
                    echo "<option value='" . htmlspecialchars($dept['ID']) . "' $selected>" . htmlspecialchars($dept['Nom_Departament']) . "</option>";
                }
                ?>
            </select><br><br>

            <label for="Descripcio">Descripció:</label><br>
            <textarea name="Descripcio" id="Descripcio" rows="4" cols="50"><?php echo htmlspecialchars($row['Descripcio']); ?></textarea><br><br>

                        <!-- Selección de Estat -->
            <label for="Estat">Estat Incidència:</label><br>
            <select name="Estat" id="Estat" required>
                <option value="">-- Selecciona un estat --</option>
                <?php
                foreach ($estats as $estat) {
                    $selected = ($estat['ID'] == $row['Estat']) ? "selected" : "";
                    echo "<option value='" . htmlspecialchars($estat['ID']) . "' $selected>" . htmlspecialchars($estat['Estat']) . "</option>";
                }
                ?>
            </select><br><br>
            <input type="submit" value="Desar canvis" style="background-color: rgb(31, 122, 140); color:white; padding:8px 12px; border:none; border-radius:5px;">
        </fieldset>
    </form>

<?php
        } else {
            echo "<p class='error'>Incidència no trobada.</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='error'>ID no vàlid.</p>";
    }
} else {
    echo "<p class='error'>No s'ha especificat cap ID.</p>";
}
?>

</body>
</html>