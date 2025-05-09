<?php
require_once 'connection.php';

// Agafa l'ID de la incidència
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empleat = $_POST['empleat'];
    $prioritat = $_POST['Prioritat'];


    // Assignar el tècnici PRioritat a la incidència
    $conn->query("UPDATE Incidencies SET Empleat = '$empleat' WHERE ID = $id");
    $conn->query("UPDATE Incidencies SET Prioritat = '$prioritat' WHERE ID = $id");


    echo "<p>Incidència assignada correctament!</p>";
    echo '<a href="incidencies_no_assignades.php">Tornar al llistat</a>';
    exit;
}

// Llistar empleats  y Prioritats 
$empleats = $conn->query("SELECT DNI, Nom FROM Empleat");
$Prioritats = $conn->query("SELECT ID, Nivel_de_Prioritat FROM Prioritat");

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Assignar Incidència</title>
    <link rel="stylesheet" href="proyecte.css">

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

<form method="POST">
<h1>Assignar Tècnic/Prioritat a la Incidència <?= htmlspecialchars($id) ?></h1>

    <label for="empleat">Selecciona un tècnic:</label>
    <select name="empleat" id="empleat">
        <option value="">Selecciona un tècnic</option>
        <?php while ($emp = $empleats->fetch_assoc()): ?>
            <option value="<?= $emp['DNI'] ?>"><?= $emp['Nom'] ?></option>
        <?php endwhile; ?>
    </select>
    <label for="Prioritat">Selecciona una Prioritat:</label>
    <select name="Prioritat" id="Prioritat">
        <option value="">Selecciona una Prioritat</option>
        <?php while ($pri = $Prioritats->fetch_assoc()): ?>
            <option value="<?= $pri['ID'] ?>"><?= $pri['Nivel_de_Prioritat'] ?></option>
        <?php endwhile; ?>
    </select>
    <br><br>
    

    <button type="submit">Assignar</button>
</form>
</fieldset>

</body>
</html>
