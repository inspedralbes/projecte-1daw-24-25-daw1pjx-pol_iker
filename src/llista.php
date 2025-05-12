<?php
require_once 'connection.php';
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Llistat d'incidències</title>
    <link rel="stylesheet" href="proyecte.css">
    <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
<header>
    <div class="btn-group">
        <button type="button" class="btn btn-primary"><a href="index.php">PAGINA INICIAL</a></button>
        <button type="button" class="btn btn-primary"><a href="crear.php">FORMULARI DE INICIDÈNCIES</a></button>
        <button type="button" class="btn btn-primary"><a href="asignar.php">FORMULARI DE INICIDÈNCIES NO ASIGNADES</a></button>
    </div> 
    <h1>LLISTAT DE INCIDÈNCIES</h1>
</header>

<!-- Filtres amb JavaScript -->
<fieldset class="filtre">
    <label for="filtreEstat">Filtrar per estat:</label>
    <select id="filtreEstat">
        <option value="">Tots</option>
        <option value="No Fet">No Fet</option>
        <option value="En Proces">En Proces</option>
        <option value="Fet">Fet</option>
    </select>

    <label for="filtrePrioritat">Filtrar per prioritat:</label>
    <select id="filtrePrioritat">
        <option value="">Tots</option>
        <option value="Alta">Alta</option>
        <option value="Mitja">Mitja</option>
        <option value="Baixa">Baixa</option>
    </select>
</fieldset>

<fieldset class="llistat">
<?php
$sql = "SELECT i.ID, u.Nom AS UsuariNom , epl.Nom AS NomEmpleat, d.Nom_Departament,
               e.Estat AS EstatText, p.Nivel_de_Prioritat AS PrioritatText,
               i.Descripcio, i.Fecha
        FROM Incidencies i
        JOIN Usuari u ON i.Usuari = u.DNI
        LEFT JOIN Empleat epl ON i.Empleat = epl.DNI
        JOIN Departament d ON i.Departament = d.ID
        JOIN Estat e ON i.Estat = e.ID
        JOIN Prioritat p ON i.Prioritat = p.ID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='incidencia' data-estat='" . htmlspecialchars($row["EstatText"]) . "' data-prioritat='" . htmlspecialchars($row["PrioritatText"]) . "'>";
        echo "<p><strong>ID:</strong> " . $row["ID"] . "<br>";
        echo "<strong>Usuari:</strong> " . htmlspecialchars($row["UsuariNom"]) . "<br>";
        echo "<strong>Empleat:</strong> " . htmlspecialchars($row["NomEmpleat"] ?? 'No assignat') . "<br>";
        echo "<strong>Departament:</strong> " . htmlspecialchars($row["Nom_Departament"]) . "<br>";
        echo "<strong>Estat:</strong> " . htmlspecialchars($row["EstatText"]) . "<br>";
        echo "<strong>Prioritat:</strong> " . htmlspecialchars($row["PrioritatText"]) . "<br>";
        echo "<strong>Descripció:</strong> " . htmlspecialchars($row["Descripcio"]) . "<br>";
        echo "<strong>Data:</strong> " . htmlspecialchars($row["Fecha"]) . "<br>";
        echo "<a href='esborrar.php?ID=" . $row["ID"] . "' style='display:inline-block; margin-top:10px; margin-right:10px; background-color:red; color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>Esborrar</a>";
        echo "<a href='modificar.php?ID=" . $row["ID"] . "' style='display:inline-block; margin-top:10px; background-color: rgb(31, 122, 140); color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>Modificar</a><hr>";
        echo "</p></div>";
    }
} else {
    echo "<p>No hi ha cap incidència per mostrar.</p>";
}

$conn->close();
?>
</fieldset>

<!-- JavaScript del filtre -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtreEstat = document.getElementById('filtreEstat');
    const filtrePrioritat = document.getElementById('filtrePrioritat');
    const incidencies = document.querySelectorAll('.incidencia');

    function filtrar() {
        const estatSeleccionat = filtreEstat.value;
        const prioritatSeleccionada = filtrePrioritat.value;

        incidencies.forEach(incidencia => {
            const estat = incidencia.getAttribute('data-estat');
            const prioritat = incidencia.getAttribute('data-prioritat');

            const mostrar =
                (estatSeleccionat === '' || estat === estatSeleccionat) &&
                (prioritatSeleccionada === '' || prioritat === prioritatSeleccionada);

            incidencia.style.display = mostrar ? 'block' : 'none';
        });
    }

    filtreEstat.addEventListener('change', filtrar);
    filtrePrioritat.addEventListener('change', filtrar);
});
</script>
</body>
</html>
