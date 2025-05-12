<?php
require_once 'connection.php';

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
        WHERE i.Empleat IS NULL and Prioritat IS NULL";

 // Filtrar solo incidencias sin técnico asignado

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
            <button type="button" class="btn btn-primary"><a href="index.php">PAGINA INICIAL</a></button>
            <button type="button" class="btn btn-primary"><a href="llista.php">LLISTA DE INICIDÈNCIES</a></button>
        </div> 
        <h1>FORMULARI DE INICIDÈNCIES </h1>
    </header>



<fieldset>

<?php if ($result->num_rows > 0): ?>
   <table class="tabla_asignar">
    <tr>
        <th>ID</th>
        <th>Usuari</th>
        <th>Empleat</th>
        <th>Departament</th>
        <th>Descripció</th>
        <th>Estat</th>
        <th>Prioritat</th>
        <th>Data</th>
        <th>Acció</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row["ID"]) ?></td>
            <td><?= htmlspecialchars($row["Usuari"] ?? '') ?></td>
            <td><?= htmlspecialchars($row["Empleat"] ?? 'No assignat') ?></td>
            <td><?= htmlspecialchars($row["Departament"] ?? '') ?></td>
            <td><?= htmlspecialchars($row["Descripcio"] ?? '') ?></td>
            <td><?= htmlspecialchars($row["Estat"] ?? '') ?></td>
            <td><?= htmlspecialchars($row["Nivel_de_Prioritat"] ?? 'No assignat') ?></td>
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





