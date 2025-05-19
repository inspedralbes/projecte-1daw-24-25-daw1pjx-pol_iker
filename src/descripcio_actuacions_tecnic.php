<?php
require_once 'connection.php';
require 'connection_Mongo.php'; 
registrarLog('/descripcio_actuacions_tecnic.php');

$id_incidencia = $_GET['ID'] ?? null;

if (!$id_incidencia || !is_numeric($id_incidencia)) {
    echo "<p>ID d'incidència no vàlid.</p>";
    exit;
}

$sql = "SELECT linia_incidencia, descripcio, temp_requeit, data_actuacio 
        FROM actuacio_de_incidencia 
        WHERE id_incidencia = ? 
        ORDER BY linia_incidencia ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_incidencia);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Descripcions de la Incidència <?php echo htmlspecialchars($id_incidencia); ?></title>
    <link rel="stylesheet" href="proyecte.css">
</head>
<body>
    <header>
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><a href="llista.php">LLISTAT DE INCIDÈNCIES</a></button>
        </div>
        <h1>Descripcions tècniques de la incidència ID <?php echo htmlspecialchars($id_incidencia); ?></h1>
    </header>

    <fieldset class="llistat">
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>Línia:</strong> " . htmlspecialchars($row["linia_incidencia"]) . "<br>";
        echo "<strong>Descripció del tècnic:</strong> " . htmlspecialchars($row["descripcio"]) . "<br>";
        echo "<strong>Temps requerit:</strong> " . htmlspecialchars($row["temp_requeit"]) . " minuts<br>";
        echo "<strong>Data de l'actuació:</strong> " . htmlspecialchars($row["data_actuacio"]) . "<br><hr>";
        
    }
} else {
    echo "<p>No hi ha actuacions tècniques per aquesta incidència.</p>";
}

$stmt->close();
$conn->close();
?>
    </fieldset>
     <script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('h1').classList.add('animar');
});
</script>
</body>
</html>
