<?php
require "connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nom = $_POST['nom'];
    $departament_text = trim($_POST['departament']);
    $descripcio = $_POST['descripcio'];

    // 1. Obtenir l’ID del departament
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

    // 3. Inserir usuari si no existeix (opcional)
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

    // 4. Inserir la incidència
    $empleat_dni = null;
    $stmt = $conn->prepare("INSERT INTO Incidencies (Estat, Empleat, Usuari, Departament, Descripcio, Fecha) VALUES (?, ?, ?, ?, ?, CURDATE())");
    $stmt->bind_param("iisss", $estat_id, $empleat_dni, $nom, $departament_id, $descripcio);


    if ($stmt->execute()) {
        header("Location: validacioFormulari.html");
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulari de Incidències</title>
    <link rel="stylesheet" href="proyecte.css">
    <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
    <header>
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><a href="index.php">PAGINA INICIAL</a></button>
            <button type="button" class="btn btn-primary"><a href="llista.php">LLISTA DE INICIDÈNCIES</a></button>
        </div> 
        <h1>FORMULARI DE INICIDÈNCIES </h1>
    </header>

    <fieldset>
        <form method="POST" action="crear.php">
            <label for="nom">Nom:</label><br>
            <input type="text" name="nom" id="nom" required><br>
            
            
            <label for="departament">Departament:</label> <br>
            <select name="departament" id="departament" required>
                <option value="">-- Selecciona --</option>
                <option value="Informàtica">Informàtica</option>
                <option value="Administració">Administració</option>
                <option value="RRHH">RRHH</option>
                <option value="Logística">Logística</option>
            </select> <br><br>

            <label for="descripcio">Descripció de la incidència:</label> <br>
            <textarea rows="5" cols="50" style="resize: none;" name="descripcio" id="descripcio" required></textarea>
            <br><br>

            <button type="submit">ENVIAR</button>
        </form>
    </fieldset>
</body>
</html>

