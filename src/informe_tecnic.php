<?php
require_once 'connection.php';


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

$result = $conn->query($sql); 
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
        <button type="button" class="btn btn-primary"><a href="asignar.php">ASIGNACIO DE INICIDÈNCIES</a></button>
        <button type="button" class="btn btn-primary"><a href="logs.php">LOGS</a></button>

    </div> 
    <h1>INFORMES DE TECNIC</h1>
</header>

<fieldset>

<fieldset class="filtre">
    <label for="filtreEmpleat">Filtrar per Empleat:</label>
    <select id="filtreEmpleat">
        <option value="">Tots</option>
        <option value="Ricardo">Ricardo</option>
        <option value="Joel">Joel</option>
        <option value="Iker">Iker</option>
    </select> 
</fieldset>


<?php if ($result && $result->num_rows > 0): ?>
    <table id="taulaInformes" class ="taula_informes">
        <thead>
            <tr class="incidencia">

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
      <tr class="incidencia" data-empleat="<?= htmlspecialchars($row['Tecnic'] ?? '') ?>">

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
<p id="noDataMessage" style="display:none; color: red; font-weight: bold;">
  No hi ha dades amb aquest empleat.
</p>

</fieldset>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtreEmpleat = document.getElementById('filtreEmpleat');
    const incidencies = document.querySelectorAll('tbody tr.incidencia');
    const noDataMessage = document.getElementById('noDataMessage');
    const taulaInformes = document.getElementById('taulaInformes');

    filtreEmpleat.addEventListener('change', function () {
        const empleatSeleccionat = filtreEmpleat.value;
        let visibleCount = 0;

        incidencies.forEach(incidencia => {
            const empleat = incidencia.getAttribute('data-empleat');
            const mostrar = (empleatSeleccionat === '' || empleat === empleatSeleccionat);
            incidencia.style.display = mostrar ? 'table-row' : 'none';
            if (mostrar) visibleCount++;
        });

        if (visibleCount === 0) {
            noDataMessage.style.display = 'block';
            taulaInformes.style.display = 'none';
        } else {
            noDataMessage.style.display = 'none';
            taulaInformes.style.display = 'table';
        }
    });
});
</script>



</body>
</html>
