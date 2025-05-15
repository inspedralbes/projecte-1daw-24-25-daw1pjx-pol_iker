<?php
require_once 'connection.php';

// Consulta SQL preparada per obtenir les incidències obertes
$sql = "
SELECT 
    E.Nom AS Tecnic,
    I.ID AS Incidencia,
    I.Fecha AS DataInici,
    P.Nivel_de_Prioritat AS Prioritat,
    SUM(TIME_TO_SEC(AI.temp_requeit)) / 60 AS TempsMinuts
FROM Incidencies I
JOIN Empleat E ON I.Empleat = E.DNI
JOIN Prioritat P ON I.Prioritat = P.ID
LEFT JOIN actuacio_de_incidencia AI ON I.ID = AI.id_incidencia
WHERE I.Estat <> 3
GROUP BY E.Nom, I.ID, I.Fecha, P.Nivel_de_Prioritat
ORDER BY 
    E.Nom,
    FIELD(P.Nivel_de_Prioritat, 'Alta', 'Mitja', 'Baix'),
    I.Fecha
";

$result = $conn->query($sql);  // Executem la consulta
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
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
    <h1>FORMULARI DE INICIDÈNCIES</h1>
</header>

<fieldset>

<?php if ($result && $result->num_rows > 0): ?>
    <table class ="taula_informes">
        <thead>
            <tr>
                <th>Tècnic</th>
                <th>Incidència</th>
                <th>Data inici</th>
                <th>Prioritat</th>
                <th>Temps (min)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): 
                $tempsTotal = $row['TempsMinuts'] ?? 0;
                $minuts = floor($tempsTotal);
                $segons = round(($tempsTotal - $minuts) * 60);
                $tempsFormat = sprintf("%02d:%02d", $minuts, $segons);
            ?>
            <tr>
                <td><?= htmlspecialchars($row['Tecnic'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['Incidencia'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['DataInici'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['Prioritat'] ?? '') ?></td>
                <td><?= $tempsFormat ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hi ha dades.</p>
<?php endif; ?>

<?php
if ($result) { $result->free(); }
$conn->close();
?>

</fieldset>

</body>
</html>
