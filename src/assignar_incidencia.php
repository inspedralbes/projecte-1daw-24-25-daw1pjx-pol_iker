<?php
require_once 'connection.php';
require 'connection_Mongo.php';  

registrarLog('/asignar_incidencia.php');



$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empleat = $_POST['empleat'] ?? '';
    $prioritat = $_POST['Prioritat'] ?? '';

    if (empty($empleat) || empty($prioritat)) {
        echo "<p class='error'>Cal seleccionar un tècnic i una prioritat.</p>";
    } else {
        $stmt = $conn->prepare("UPDATE Incidencies SET Empleat = ?, Prioritat = ? WHERE ID = ?");
        $stmt->bind_param("ssi", $empleat, $prioritat, $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: confirmat.php");
            exit(); 
        } else {
            echo "<p class='error'>Error en actualitzar: " . htmlspecialchars($stmt->error) . "</p>";
            $stmt->close();
        }
    }
}

$empleats = $conn->query("SELECT DNI, Nom , Rol_ID FROM Empleat WHERE Rol_ID = 2 " );
$Prioritats = $conn->query("SELECT ID, Nivel_de_Prioritat FROM Prioritat");

?>

<script>


</script>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Assignar Incidència</title>
    <link rel="stylesheet" href="proyecte.css">
    <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">

</head>
<body>
<header>
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><a href="index.php">PAGINA INICIAL</a></button>
            <button type="button" class="btn btn-primary"><a href="llista.php">LLISTA DE INICIDÈNCIES</a></button>
            <button type="button" class="btn btn-primary"><a href="asignar.php">LLISTA DE INICIDÈNCIES NO ASSIGNADES</a></button>
        </div> 
        <h1>FORMULARI DE INICIDÈNCIES </h1>
    </header>



<fieldset>

<form method="POST" id="form">
<h1>Assignar Tècnic/Prioritat a la Incidència <?= htmlspecialchars($id) ?></h1>

    <label for="empleat">Selecciona un tècnic:</label>
    <select name="empleat" id="empleat">
        <option value="">Selecciona un tècnic</option>
        <?php while ($emp = $empleats->fetch_assoc()): ?>
            <option value="<?= $emp['DNI'] ?>"><?= $emp['Nom'] ?></option>
        <?php endwhile; ?>
    </select> <br>
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

<script>
        document.getElementById('form').addEventListener('submit', function(event) {
            const empleat = document.getElementById('empleat').value.trim();
            const Prioritat = document.getElementById('Prioritat').value.trim();
            let errors = [];

            if (empleat === '') {
                errors.push("Has de selecionar un tècnic.");
            }

            if (Prioritat === '') {
                errors.push("Has de seleccionar un nivell de prioritat");
            }

            if (errors.length > 0) {
                alert(errors.join('\n'));
                event.preventDefault();
            }
        });
    </script>
     <script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('h1').classList.add('animar');
});
</script>
</body>
</html>
