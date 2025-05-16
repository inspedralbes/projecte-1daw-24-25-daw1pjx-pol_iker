<?php
require_once 'connection.php';
require 'connection_Mongo.php';  // Incluir la función para registrar logs

registrarLog('/modificar.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ID'];

  
    if (!empty($_POST['NovaDescripcio']) && !empty($_POST['Temps']) && !empty($_POST['NouEstat'])) {
        $nova_descripcio = $_POST['NovaDescripcio'];
        $temps = $_POST['Temps'];
        $nou_estat = $_POST['NouEstat'];

      
        $sql_max = "SELECT COALESCE(MAX(linia_incidencia), 0) + 1 AS next_linia 
                    FROM actuacio_de_incidencia 
                    WHERE id_incidencia = ?";
        $stmt_max = $conn->prepare($sql_max);
        $stmt_max->bind_param("i", $id);
        $stmt_max->execute();
        $result_max = $stmt_max->get_result();
        $row_max = $result_max->fetch_assoc();
        $linia_incidencia = $row_max['next_linia'];
        $stmt_max->close();

      
        $sql_insert = "INSERT INTO actuacio_de_incidencia (id_incidencia, linia_incidencia, descripcio, temp_requeit, estat_incidencia)
                       VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iissi", $id, $linia_incidencia, $nova_descripcio, $temps, $nou_estat);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    
    header("Location: confirmat.php");
    exit();
} elseif (isset($_GET['ID'])) {
    $id = $_GET['ID'];

    if (is_numeric($id)) {
        
        $estats = [];
        $estat_sql = "SELECT ID, Estat FROM Estat";
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
    <title>Afegir actuació tècnica</title>
    <link rel="stylesheet" href="proyecte.css">
    <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
<header>
 
  <div class="btn-group">
    <button><a href="llista.php">LLISTAT DE INCIDÈNCIES</a></button>
    <h1>Actuacions tècniques de la incidència</h1>
  </div>
</header>

    <form method="POST" id="form">
        <fieldset>
            <h1>Incidència ID <?php echo htmlspecialchars($id); ?></h1>
            <input type="hidden" name="ID" value="<?php echo htmlspecialchars($id); ?>">

            <h3>Afegir actuació tècnica</h3>

            <label for="NovaDescripcio">Descripció tècnica:</label><br>
            <textarea name="NovaDescripcio" id="NovaDescripcio" rows="3" cols="50"></textarea><br><br>

            <label for="Temps">Temps requerit:</label>
            <input type="time" name="Temps" id="Temps"><br><br>

            <label for="NouEstat">Estat de l'actuació:</label><br>
            <select name="NouEstat" id="NouEstat" required>
                <option value="">-- Selecciona un estat --</option>
                <?php
                foreach ($estats as $estat) {
                    echo "<option value='" . htmlspecialchars($estat['ID']) . "'>" . htmlspecialchars($estat['Estat']) . "</option>";
                }
                ?>
            </select><br><br>
            <input type="submit" value="Desar canvis" style="background-color: rgb(31, 122, 140); color:white; padding:8px 12px; border:none; border-radius:5px;">

        </fieldset>

      
    </form>

    <script>
        document.getElementById('form').addEventListener('submit', function(event) {
            const NovaDescripcio = document.getElementById('NovaDescripcio').value.trim();
            const Temps = document.getElementById('Temps').value.trim();
            const NouEstat = document.getElementById('NouEstat').value.trim();
            let errors = [];

            if (NovaDescripcio === '') {
                errors.push("Has d'escriure una descripció tècnica.");
            }

            if (NouEstat === '') {
                errors.push("Has de seleccionar un estat per l'actuació.");
            }

            if(Temps === '')
                errors.push("Has de posar el temps requerit.");
            }   

            if (errors.length > 0) {
                alert(errors.join('\n'));
                event.preventDefault();
            }
        });
    </script>
</body>
</html>

<?php
    } else {
        echo "<p class='error'>ID no vàlid.</p>";
    }
} else {
    echo "<p class='error'>No s'ha especificat cap ID.</p>";
}
?>
