<?php
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar la actualización
    $id = $_POST['ID'];
    $descripcio = $_POST['Descripcio'];
    $estat = $_POST['Estat'];

    // Consulta para actualizar la incidencia
    $sql = "UPDATE Incidencies SET Descripcio = ?, Estat = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $descripcio, $estat, $id); // El tipo es "isii" porque Departament es un número (ID), Descripcio es texto, Estat es un número e ID es un número

    if ($stmt->execute()) {
        // Si la actualización fue exitosa, redirigimos al usuario a la página "esborrada.html"
        $stmt->close();
        header("Location: confirmat.html");
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
    <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
    <header>
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><a href="index.php">PAGINA INICIAL</a></button>
            <button type="button" class="btn btn-primary"><a href="crear.php">FORMULARI DE INCIDÈNCIES</a></button>
            <button type="button" class="btn btn-primary"><a href="llista.php">LLISTA DE INICIDÈNCIES</a></button>
        </div> 
        <h1>MODIFICAR INCIDÈNCIA</h1>
    </header>

    <form method="POST" id="form">
        <fieldset>
            <h1>Accions ID <?php echo htmlspecialchars($row['ID']); ?></h1>

            <input type="hidden" name="ID" value="<?php echo htmlspecialchars($row['ID']); ?>">

            

            <label for="Descripcio">Descripció Accions:</label><br>
            <textarea name="Descripcio" id="Descripcio" rows="4" cols="50"><?php echo htmlspecialchars($row['Descripcio']); ?></textarea><br><br>

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

<script>
        document.getElementById('form').addEventListener('submit', function(event) {
            const Departament = document.getElementById('Departament').value.trim();
            const Descripcio = document.getElementById('Descripcio').value.trim();
            const Estat = document.getElementById('Estat').value.trim();
            let errors = [];

            if (Descripcio === '') {
                errors.push("Has d'escriure una descripció.");
            }
            else if (Descripcio.length < 20) {
            errors.push("La descripcio ha de tenir com a mínim 20 caràcters.");
            }

            if (Estat === '') {
                errors.push("Has de posar-li un estat a la incidencia");
            }

            if (errors.length > 0) {
                alert(errors.join('\n'));
                event.preventDefault();
            }
        });
    </script>
</body>
</html>