<?php
require_once 'connection.php';

// Consultar incidencias no asignadas (Empleat es NULL)
$sql = "SELECT *
        FROM Incidencies i
        WHERE i.Empleat IS NULL";  // Filtrar solo incidencias sin técnico asignado

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
            <button type="button" class="btn btn-primary"><a href="llista.php">LLISTA DE INICIDÈNCIES</a></button>

        </div> 
        <h1>FORMULARI DE INICIDÈNCIES </h1>
    </header>



<fieldset>

<?php if ($result->num_rows > 0): ?>
    <table >
        <tr>
            <th>ID</th>
            <th>Usuari</th>
            <th>Empleat</th>
            <th>Departament</th>
            <th>Descripció</th>
            <th>Estat</th>
            <th>Data</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["ID"]) ?></td>
                <td><?= htmlspecialchars($row["Usuari"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["Empleat"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["Departament"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["Descripcio"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["Estat"] ?? '') ?></td>
                <td><?= htmlspecialchars($row["Fecha"] ?? '') ?></td>
                <td><a style="color: blue;" href="assignar_incidencia.php?id=<?= $row["ID"] ?>">Assignar</a></td>
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


