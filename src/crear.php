<?php
require "connection.php";
require 'connection_Mongo.php';  // Incluir la función para registrar logs

registrarLog('/crear.php');

// Verificar si los headers ya se han enviado
if (headers_sent($file, $line)) {
    die("⚠ Error: Headers already sent in $file on line $line");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nom = $_POST['nom'];
    $departament_text = trim($_POST['departament']);
    $descripcio = $_POST['descripcio'];

    $dept_stmt = $conn->prepare("SELECT ID FROM Departament WHERE Nom_Departament = ?");
    $dept_stmt->bind_param("s", $departament_text);
    $dept_stmt->execute();
    $dept_result = $dept_stmt->get_result();
    if ($dept_row = $dept_result->fetch_assoc()) {
        $departament_id = $dept_row['ID'];
    } else {
        die("Error: Departament no vàlid.");
    }

    $estat_id = 1;

    $check_user_stmt = $conn->prepare("SELECT DNI FROM Usuari WHERE DNI = ?");
    $check_user_stmt->bind_param("s", $nom);
    $check_user_stmt->execute();
    $user_result = $check_user_stmt->get_result();
    if (!$user_result->fetch_assoc()) {
        $insert_user_stmt = $conn->prepare("INSERT INTO Usuari (DNI, Nom) VALUES (?, ?)");
        $insert_user_stmt->bind_param("ss", $nom, $nom);
        $insert_user_stmt->execute();
        $insert_user_stmt->close();
    }
    $check_user_stmt->close();

    $empleat_dni = null;
    $stmt = $conn->prepare("INSERT INTO Incidencies (Estat, Empleat, Usuari, Departament, Descripcio, Fecha) VALUES (?, ?, ?, ?, ?, CURDATE())");
    $stmt->bind_param("iisss", $estat_id, $empleat_dni, $nom, $departament_id, $descripcio);

    if ($stmt->execute()) {
        header("Location: confirmat.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulari de Incidències</title>
    <link rel="stylesheet" href="proyecte.css">
    <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
    <header>
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><a href="usuari.php">USUARI</a></button>
            <button type="button" class="btn btn-primary"><a href="llista_usuari.php">LLISTA D'INCIDÈNCIES</a></button>
        </div> 
        <h1>FORMULARI D'INCIDÈNCIES</h1>
    </header>
    <fieldset>
        <form method="POST" id="form" action="crear.php">
            <label for="nom">Nom:</label><br>
            <input type="text" name="nom" id="nom"><br>

            <label for="departament">Departament:</label><br>
            <select name="departament" id="departament">
                <option value="">-- Selecciona --</option>
                <option value="Informàtica">Informàtica</option>
                <option value="Administració">Administració</option>
                <option value="RRHH">RRHH</option>
                <option value="Logística">Logística</option>
            </select><br><br>

            <label for="descripcio">Descripció de la incidència:</label><br>
            <textarea rows="5" cols="50" style="resize: none;" name="descripcio" id="descripcio"></textarea><br><br>

            <button type="submit" id="btnEnviar">ENVIAR</button>
        </form>
    </fieldset>

    <script>
        document.getElementById('form').addEventListener('submit', function(event) {
            const nom = document.getElementById('nom').value.trim();
            const departament = document.getElementById('departament').value.trim();
            const descripcio = document.getElementById('descripcio').value.trim();
            let errors = [];

            if (nom === '') {
                errors.push("Has de posar un nom.");
            }

            if (departament === '') {
                errors.push("Has de seleccionar un departament.");
            }

            if (descripcio === '') {
                errors.push("Has d'escriure una descripció.");
            }
            else if (descripcio.length < 20) {
            errors.push("La descripcio ha de tenir com a mínim 20 caràcters.");
            }
            

            if (errors.length > 0) {
                alert(errors.join('\n'));
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
