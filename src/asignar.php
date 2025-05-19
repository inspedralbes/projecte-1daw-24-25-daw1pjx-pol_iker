<?php
require_once 'connection.php';
require 'connection_Mongo.php';  // Incluir la función para registrar logs

registrarLog('/asignar.php');

// Consultar incidencias no asignadas (Empleat es NULL)
$sql = "SELECT i.ID, 
               u.Nom AS Usuari, 
               epl.Nom AS Empleat, 
               d.Nom_Departament AS Departament, 
               e.Estat AS Estat, 
               i.Descripcio, 
               i.Fecha,
               p.Nivel_de_Prioritat AS Prioritat
        FROM Incidencies i
        JOIN Usuari u ON i.Usuari = u.DNI
        LEFT JOIN Empleat epl ON i.Empleat = epl.DNI
        JOIN Departament d ON i.Departament = d.ID
        JOIN Estat e ON i.Estat = e.ID
        LEFT JOIN Prioritat p ON i.Prioritat = p.ID
        WHERE i.Empleat IS NULL AND i.Prioritat IS NULL";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASIGNACIO DE INCIDENCIES</title>
    <link rel="stylesheet" href="proyecte.css">
     <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
<header>
        <div class="btn-group">
        <button type="button" class="btn btn-primary"><a href="admin.php">ADMIN</a></button>
        <button type="button" class="btn btn-primary"><a href="llista_admin.php">LLISTA DE INICIDÈNCIES</a></button>
        <button type="button" class="btn btn-primary"><a href="informe_tecnic.php">INFORMES</a></button>
        <button type="button" class="btn btn-primary"><a href="logs.php">LOGS</a></button>

        </div> 
        <h1>ASIGNACIO DE INCIDENCIES</h1>
    </header>



<fieldset>

<?php if ($result->num_rows > 0): ?>
    <table class="llistat_no_asignades">
        <tr>
            <th>ID</th>
            <th>Usuari</th>
            <th>Empleat</th>
            <th>Departament</th>
            <th>Descripció</th>
            <th>Estat</th>
            <th>Prioritat</th>
            <th>Data</th>
            <th>Acció</th>  <!-- Nueva columna para el botón -->

        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["ID"]) ?></td>
                <td><?= htmlspecialchars($row["Usuari"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["Empleat"] ?? 'NO ASIGNAT') ?></td>
                <td><?= htmlspecialchars($row["Departament"] ?? '') ?></td>
                <td><?= nl2br(htmlspecialchars($row["Descripcio"] ?? '')) ?></td>
                <td><?= htmlspecialchars($row["Estat"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["Prioritat"] ?? 'NO ASIGNAT') ?></td>
                <td><?= htmlspecialchars($row["Fecha"] ?? '') ?></td>
                <td><a class="btn-assignar" href="assignar_incidencia.php?id=<?= $row["ID"] ?>">Assignar</a></td>

            </tr>

        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No hi ha incidències sense tècnic assignat.</p>
<?php endif; ?>

<?php $conn->close(); ?>
</fieldset>

</body>
</html>

