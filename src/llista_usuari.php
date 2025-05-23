<?php
require_once 'connection.php';
require 'connection_Mongo.php';  // Incluir la función para registrar logs

registrarLog('/llistat_usuari.php');

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llistat d'incidències</title>
    <link rel="stylesheet" href="proyecte.css">
    <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
<header>
    <div class="btn-group">
        <button type="button" class="btn btn-primary"><a href="usuari.php">PAGINA USUARI</a></button>
        <button type="button" class="btn btn-primary"><a href="crear.php">FORMULARI DE INICIDÈNCIES</a></button>

    </div> 
    <h1>LLISTAT DE INCIDÈNCIES</h1>
</header>

<fieldset class="filtre">
    <form method="GET" action="">
        <label for="filtreEstat">Filtrar per estat:</label>
        <select id="filtreEstat" name="estat">
            <option value="">Tots</option>
            <option value="No Fet" <?= ($_GET['estat'] ?? '') == 'No Fet' ? 'selected' : '' ?>>No Fet</option>
            <option value="En Proces" <?= ($_GET['estat'] ?? '') == 'En Proces' ? 'selected' : '' ?>>En Proces</option>
            <option value="Fet" <?= ($_GET['estat'] ?? '') == 'Fet' ? 'selected' : '' ?>>Fet</option>
        </select>

        <label for="filtrePrioritat">Filtrar per prioritat:</label>
        <select id="filtrePrioritat" name="prioritat">
            <option value="">Tots</option>
            <option value="Alta" <?= ($_GET['prioritat'] ?? '') == 'Alta' ? 'selected' : '' ?>>Alta</option>
            <option value="Mitja" <?= ($_GET['prioritat'] ?? '') == 'Mitja' ? 'selected' : '' ?>>Mitja</option>
            <option value="Baixa" <?= ($_GET['prioritat'] ?? '') == 'Baixa' ? 'selected' : '' ?>>Baixa</option>
        </select>

        <button type="submit">Aplicar filtre</button>
    </form>
</fieldset>

<fieldset class="llistat">
<?php
$estat = $_GET['estat'] ?? '';
$prioritat = $_GET['prioritat'] ?? '';


$sql = "SELECT i.ID, u.Nom AS UsuariNom , epl.Nom AS NomEmpleat, d.Nom_Departament,
               e.Estat AS EstatText, p.Nivel_de_Prioritat AS PrioritatText,
               i.Descripcio, i.Fecha
        FROM Incidencies i
        LEFT JOIN Usuari u ON i.Usuari = u.DNI
        LEFT JOIN Empleat epl ON i.Empleat = epl.DNI
        LEFT JOIN Departament d ON i.Departament = d.ID
        LEFT JOIN Estat e ON i.Estat = e.ID
        LEFT JOIN Prioritat p ON i.Prioritat = p.ID
        WHERE 1";

if ($estat !== '') {
    $sql .= " AND e.Estat = '" . $conn->real_escape_string($estat) . "'";
}
if ($prioritat !== '') {
    $sql .= " AND p.Nivel_de_Prioritat = '" . $conn->real_escape_string($prioritat) . "'";
}

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='ticket'>";
        echo "<div class='ID'><strong>ID:</strong> " . htmlspecialchars($row["ID"]) . "</div>";
        echo "<div class='camp'><strong>Usuari:</strong> " . htmlspecialchars($row["UsuariNom"] ?? 'No trobat') . "</div>";
        echo "<div class='camp'><strong>Empleat:</strong> " . htmlspecialchars($row["NomEmpleat"] ?? 'No assignat') . "</div>";
        echo "<div class='camp'><strong>Departament:</strong> " . htmlspecialchars($row["Nom_Departament"] ?? 'No assignat') . "</div>";
        echo "<div class='camp'><strong>Estat:</strong> " . htmlspecialchars($row["EstatText"] ?? 'Sense estat') . "</div>";
        echo "<div class='camp'><strong>Prioritat:</strong> " . htmlspecialchars($row["PrioritatText"] ?? 'Sense prioritat') . "</div>";
        echo "<div class='camp'><strong>Data:</strong> " . htmlspecialchars($row["Fecha"]) . "</div>";
        echo "<div class='camp'><strong>Descripció:</strong></div>";
        echo "<div class='descripcio'>" . nl2br(htmlspecialchars($row["Descripcio"])) . "</div>";
        echo "</div>"; 
    }
      
} else {
    echo "<p>No hi ha cap incidència amb aquests filtres.</p>";
}

$conn->close();
?>
</fieldset>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const tickets = document.querySelectorAll('.ticket');

  tickets.forEach(ticket => {
    ticket.addEventListener('mouseenter', () => {
      ticket.style.transition = 'background-color 0.3s ease';
      ticket.style.backgroundColor = 'rgba(200, 200, 200, 0.3)';
    });

    ticket.addEventListener('mouseleave', () => {
      ticket.style.backgroundColor = '';
    });
  });
});
</script>
 <script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('h1').classList.add('animar');
});
</script>
</body>
</html>
